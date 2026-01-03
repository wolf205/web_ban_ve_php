<?php
// app/controllers/AdminRapController.php
session_start();

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/RapModel.php';
require_once __DIR__ . '/../models/PhongModel.php';

class AdminRapController {

    private $db;
    private $rapModel;
    private $phongModel;
    private $limit = 2; // Số rạp mỗi trang

    // =================================================================
    // 1. HÀM KHỞI TẠO VÀ CẤU HÌNH CƠ BẢN
    // =================================================================

    /**
     * KHỞI TẠO CONTROLLER
     * - Thiết lập kết nối database
     * - Khởi tạo các model cần thiết cho quản lý rạp
     * - Kiểm tra kết nối database thành công
     */
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        if ($this->db === null) throw new \Exception("Không thể kết nối đến CSDL.");

        $this->rapModel = new RapModel($this->db);
        $this->phongModel = new PhongModel($this->db);
    }

    // =================================================================
    // 2. CÁC PHƯƠNG THỨC TIỆN ÍCH VÀ XỬ LÝ DỮ LIỆU
    // =================================================================

    /**
     * HÀM TIỆN ÍCH XỬ LÝ UPLOAD FILE ẢNH
     * - Xử lý upload ảnh rạp từ form
     * - Tạo thư mục nếu chưa tồn tại
     * - Đặt tên file an toàn (thêm timestamp và prefix)
     * - Trả về đường dẫn file hoặc null nếu không có upload
     */
    private function handleFileUpload($fileInputName, $ma_rap_prefix = '') {
        // KIỂM TRA CÓ FILE UPLOAD VÀ KHÔNG CÓ LỖI
        if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] == 0) {
            $uploadDir = 'publics/img/rap/'; // ĐƯỜNG DẪN LƯU ẢNH
            
            // TẠO THƯ MỤC NẾU CHƯA TỒN TẠI
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // LẤY TÊN FILE VÀ TẠO TÊN AN TOÀN
            $fileName = basename($_FILES[$fileInputName]['name']);
            $safeFileName = $ma_rap_prefix . time() . '_' . $fileName; // THÊM TIMESTAMP ĐỂ TRÁNH TRÙNG
            $targetFilePath = $uploadDir . $safeFileName;
            
            // DI CHUYỂN FILE TỪ TEMP ĐẾN THƯ MỤC ĐÍCH
            if (move_uploaded_file($_FILES[$fileInputName]['tmp_name'], $targetFilePath)) {
                return $targetFilePath; // TRẢ VỀ ĐƯỜNG DẪN FILE ĐÃ LƯU
            }
        }
        return null; // KHÔNG CÓ FILE UPLOAD
    }

    /**
     * LẤY BỘ LỌC TỪ REQUEST
     * - Xử lý các tham số GET từ form filter
     * - Loại bỏ giá trị 'all' (tất cả)
     * - Trả về mảng filters để sử dụng trong query
     */
    private function getFilters() {
        $filters = [];
        
        // LỌC THEO THÀNH PHỐ (KHÁC RỖNG VÀ KHÁC 'all')
        if (isset($_GET['thanh_pho']) && $_GET['thanh_pho'] != '' && $_GET['thanh_pho'] != 'all') {
            $filters['thanh_pho'] = $_GET['thanh_pho'];
        }
        
        // LỌC THEO TỪ KHÓA TÌM KIẾM
        if (!empty($_GET['search'])) {
            $filters['search'] = $_GET['search'];
        }
        
        return $filters;
    }

    /**
     * LẤY DỮ LIỆU CƠ BẢN CHO TẤT CẢ CÁC ACTION
     * - Xử lý phân trang (page, limit, offset)
     * - Áp dụng bộ lọc nếu có
     * - Tính toán số trang tổng
     * - Lấy danh sách rạp và thông tin liên quan
     * - Tính số phòng cho mỗi rạp
     */
    private function getBaseData($filters = [], $action = null, $edit_id = null, $rap_to_edit = null) {
        // XỬ LÝ PHÂN TRANG
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page); // ĐẢM BẢO PAGE ÍT NHẤT LÀ 1
        $offset = ($page - 1) * $this->limit;
        
        // LẤY DỮ LIỆU RẠP (CÓ HOẶC KHÔNG CÓ BỘ LỌC)
        if (!empty($filters)) {
            $danhSachRap = $this->rapModel->filterRapPhanTrang(
                $this->limit, 
                $offset, 
                $filters['thanh_pho'] ?? null, 
                $filters['search'] ?? null
            );
            $totalRecords = $this->rapModel->countFilterRap(
                $filters['thanh_pho'] ?? null, 
                $filters['search'] ?? null
            );
        } else {
            $danhSachRap = $this->rapModel->getAllRapPhanTrang($this->limit, $offset);
            $totalRecords = $this->rapModel->countAllRap();
        }
        
        // TÍNH TOÁN PHÂN TRANG
        $totalPages = ceil($totalRecords / $this->limit);
        
        // LẤY DANH SÁCH THÀNH PHỐ DUY NHẤT CHO DROPDOWN FILTER
        $cities = $this->rapModel->getDistinctCities();
        
        // TÍNH SỐ PHÒNG CHO TỪNG RẠP
        foreach ($danhSachRap as $key => $rap) {
            $danhSachRap[$key]['so_phong'] = $this->phongModel->countPhongByRapId($rap['ma_rap']);
        }
        
        // TRẢ VỀ MẢNG DỮ LIỆU ĐẦY ĐỦ CHO VIEW
        return [
            'danhSachRap' => $danhSachRap,
            'cities' => $cities,
            'page' => $page,
            'totalPages' => $totalPages,
            'filter_params' => $filters,
            'action' => $action,
            'edit_id' => $edit_id,
            'rap_to_edit' => $rap_to_edit
        ];
    }

    /**
     * CHUYỂN HƯỚNG VỚI TRẠNG THÁI (DÙNG SESSION)
     * - Lưu trạng thái thành công/lỗi vào session
     * - Xây dựng URL với các tham số
     * - Chuyển hướng và thoát chương trình
     */
    private function redirectWithStatus($success, $actionType) {
        // TẠO MÃ TRẠNG THÁI (success/error)
        $status = $success ? $actionType . '_success' : $actionType . '_error';
        
        // LƯU VÀO SESSION (FLASH MESSAGE)
        $_SESSION['flash_status'] = $status;
        
        // CHUYỂN HƯỚNG VỀ TRANG DANH SÁCH RẠP
        header("Location: index.php?controller=adminRap&action=index");
        exit;
    }

    // =================================================================
    // 3. CÁC ACTION CHÍNH CHO QUẢN LÝ RẠP (CRUD)
    // =================================================================

    /**
     * HIỂN THỊ DANH SÁCH RẠP VỚI BỘ LỌC
     * - Action mặc định (index)
     * - Lấy dữ liệu và hiển thị view
     * - Áp dụng bộ lọc nếu có
     */
    public function index() {
        $filters = $this->getFilters();
        $data = $this->getBaseData($filters);
        
        // CHUYỂN MẢNG THÀNH BIẾN ĐỂ VIEW SỬ DỤNG
        extract($data);
        require_once __DIR__ . '/../views/admin/rap_view.php';
    }

    /**
     * HIỂN THỊ FORM THÊM MỚI RẠP (INLINE)
     * - Đặt action = 'create' để view biết hiển thị form
     * - Vẫn giữ lại các bộ lọc hiện tại
     * - Hiển thị form ngay trên trang danh sách
     */
    public function create() {
        $filters = $this->getFilters();
        $data = $this->getBaseData($filters, 'create');
        
        extract($data);
        require_once __DIR__ . '/../views/admin/rap_view.php';
    }

    /**
     * XỬ LÝ LƯU TRỮ RẠP MỚI
     * - Kiểm tra phương thức POST
     * - Xử lý upload ảnh rạp (nếu có)
     * - Gọi model để thêm rạp
     * - Chuyển hướng với trạng thái
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // XỬ LÝ UPLOAD ẢNH RẠP
                $anh_rap_path = $this->handleFileUpload('anh_rap');
                
                // GỌI MODEL ĐỂ THÊM RẠP
                $success = $this->rapModel->addRap(
                    $_POST['ten_rap'],
                    $_POST['dia_chi'],
                    $_POST['thanh_pho'],
                    $_POST['SDT'],
                    $anh_rap_path, // CÓ THỂ LÀ NULL NẾU KHÔNG CÓ ẢNH
                    $_POST['mo_ta_rap']
                );
                
                $this->redirectWithStatus($success, 'add');
            } catch (Exception $e) {
                $this->redirectWithStatus(false, 'add');
            }
        } else {
            // NẾU KHÔNG PHẢI POST, CHUYỂN VỀ TRANG THÊM MỚI
            header('Location: index.php?controller=adminRap&action=create');
            exit;
        }
    }

    /**
     * HIỂN THỊ FORM SỬA RẠP (INLINE)
     * - Kiểm tra ID rạp hợp lệ
     * - Lấy thông tin rạp cần sửa
     * - Hiển thị form với dữ liệu hiện tại
     */
    public function edit() {
        $edit_id = $_GET['id'] ?? null;
        if (!$edit_id) {
            $this->redirectWithStatus(false, 'not_found');
        }

        $rap_to_edit = $this->rapModel->getRapById($edit_id);
        if (!$rap_to_edit) {
            $this->redirectWithStatus(false, 'not_found');
        }

        $filters = $this->getFilters();
        $data = $this->getBaseData($filters, null, $edit_id, $rap_to_edit);
        
        extract($data);
        require_once __DIR__ . '/../views/admin/rap_view.php';
    }

    /**
     * XỬ LÝ CẬP NHẬT RẠP
     * - Kiểm tra phương thức POST
     * - Validate dữ liệu (thiếu ID)
     * - Xử lý upload ảnh mới (nếu có)
     * - Gọi model để cập nhật
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $ma_rap = $_POST['ma_rap'] ?? null;
                if (!$ma_rap) throw new Exception("Thiếu ID rạp.");

                // XỬ LÝ UPLOAD ẢNH MỚI (THÊM PREFIX ĐỂ TRÁNH TRÙNG)
                $anh_rap_path = $this->handleFileUpload('anh_rap', $ma_rap . '_');
                
                $success = $this->rapModel->updateRap(
                    $ma_rap,
                    $_POST['ten_rap'],
                    $_POST['dia_chi'],
                    $_POST['thanh_pho'],
                    $_POST['SDT'],
                    $anh_rap_path, // NULL NẾU KHÔNG ĐỔI ẢNH
                    $_POST['mo_ta_rap']
                );
                
                $this->redirectWithStatus($success, 'update');
            } catch (Exception $e) {
                $this->redirectWithStatus(false, 'update');
            }
        }
    }

    /**
     * XỬ LÝ XÓA MỘT RẠP
     * - Kiểm tra ID hợp lệ
     * - Xử lý ràng buộc khóa ngoại (phòng, suất chiếu)
     * - Chuyển hướng với trạng thái phù hợp
     */
    public function destroy() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            try {
                $success = $this->rapModel->deleteRap($id);
                if ($success) {
                    $this->redirectWithStatus(true, 'delete');
                } else {
                    // LỖI DO RÀNG BUỘC KHÓA NGOẠI (CÓ PHÒNG HOẶC SUẤT CHIẾU LIÊN QUAN)
                    $_SESSION['flash_status'] = 'delete_error_fk';
                    header("Location: index.php?controller=adminRap&action=index");
                    exit;
                }
            } catch (Exception $e) {
                $this->redirectWithStatus(false, 'delete');
            }
        } else {
            header('Location: index.php?controller=adminRap&action=index');
            exit;
        }
    }
}
?>