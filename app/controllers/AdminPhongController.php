<?php
// app/controllers/AdminPhongController.php
session_start();

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/PhongModel.php';
require_once __DIR__ . '/../models/GheModel.php';
require_once __DIR__ . '/../models/RapModel.php';

class AdminPhongController {

    private $db;
    private $phongModel;
    private $gheModel;
    private $rapModel;
    private $limit = 3; // Số phòng mỗi trang
    private $gheLimit = 5; // Số ghế mỗi trang

    // =================================================================
    // 1. HÀM KHỞI TẠO VÀ CẤU HÌNH CƠ BẢN
    // =================================================================

    /**
     * KHỞI TẠO CONTROLLER
     * - Kết nối database
     * - Khởi tạo các model cần thiết
     * - Thiết lập các giá trị mặc định
     */
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        if ($this->db === null) throw new \Exception("Không thể kết nối đến CSDL.");

        $this->phongModel = new PhongModel($this->db);
        $this->gheModel = new GheModel($this->db);
        $this->rapModel = new RapModel($this->db);
    }

    // =================================================================
    // 2. CÁC PHƯƠNG THỨC TRÍCH XUẤT VÀ XỬ LÝ DỮ LIỆU
    // =================================================================

    /**
     * LẤY BỘ LỌC TỪ REQUEST CHO PHÒNG
     * - Xử lý các tham số GET từ form filter
     * - Loại bỏ giá trị 'all' (tất cả)
     * - Trả về mảng filters để sử dụng trong query
     */
    private function getPhongFilters() {
        $filters = [];
        
        // LỌC THEO MÃ RẠP (KHÁC RỖNG VÀ KHÁC 'all')
        if (isset($_GET['ma_rap']) && $_GET['ma_rap'] != '' && $_GET['ma_rap'] != 'all') {
            $filters['ma_rap'] = $_GET['ma_rap'];
        }
        
        // LỌC THEO LOẠI MÀN HÌNH (KHÁC RỖNG VÀ KHÁC 'all')
        if (isset($_GET['loai_man_hinh']) && $_GET['loai_man_hinh'] != '' && $_GET['loai_man_hinh'] != 'all') {
            $filters['loai_man_hinh'] = $_GET['loai_man_hinh'];
        }
        
        // LỌC THEO TỪ KHÓA TÌM KIẾM
        if (!empty($_GET['search'])) {
            $filters['search'] = $_GET['search'];
        }
        
        return $filters;
    }

    /**
     * LẤY DỮ LIỆU CƠ BẢN CHO PHÒNG
     * - Xử lý phân trang (page, limit, offset)
     * - Áp dụng bộ lọc nếu có
     * - Tính toán số trang tổng
     * - Lấy danh sách phòng và thông tin liên quan
     */
    private function getBaseData($filters = [], $action = null, $edit_id = null, $phong_to_edit = null) {
        // XỬ LÝ PHÂN TRANG
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page); // ĐẢM BẢO PAGE ÍT NHẤT LÀ 1
        $offset = ($page - 1) * $this->limit;
        
        // LẤY DỮ LIỆU PHÒNG (CÓ HOẶC KHÔNG CÓ BỘ LỌC)
        if (!empty($filters)) {
            $danhSachPhong = $this->phongModel->filterPhongPhanTrang(
                $this->limit, 
                $offset, 
                $filters['ma_rap'] ?? null, 
                $filters['search'] ?? null, 
                $filters['loai_man_hinh'] ?? null
            );
            $totalRecords = $this->phongModel->countFilterPhong(
                $filters['ma_rap'] ?? null, 
                $filters['search'] ?? null, 
                $filters['loai_man_hinh'] ?? null
            );
        } else {
            $danhSachPhong = $this->phongModel->getAllPhongWithRapPhanTrang($this->limit, $offset);
            $totalRecords = $this->phongModel->countAllPhong();
        }
        
        // TÍNH TOÁN PHÂN TRANG
        $totalPages = ceil($totalRecords / $this->limit);
        
        // LẤY DỮ LIỆU CHO DROPDOWN VÀ BỘ LỌC
        $danhSachRap = $this->rapModel->getAllRap();
        $loai_man_hinh_list = $this->phongModel->getDistinctScreenTypes();
        
        // TÍNH SỐ LƯỢNG GHẾ CHO TỪNG PHÒNG
        foreach ($danhSachPhong as $key => $phong) {
            $danhSachPhong[$key]['so_luong_ghe'] = $this->gheModel->countGheByPhong($phong['ma_phong']);
        }
        
        // TRẢ VỀ MẢNG DỮ LIỆU ĐẦY ĐỦ CHO VIEW
        return [
            'danhSachPhong' => $danhSachPhong,
            'danhSachRap' => $danhSachRap,
            'loai_man_hinh_list' => $loai_man_hinh_list,
            'page' => $page,
            'totalPages' => $totalPages,
            'filter_params' => $filters,
            'action' => $action,
            'edit_id' => $edit_id,
            'phong_to_edit' => $phong_to_edit
        ];
    }

    /**
     * LẤY DỮ LIỆU CƠ BẢN CHO GHẾ
     * - Dành cho trang quản lý ghế của một phòng cụ thể
     * - Xử lý phân trang riêng cho danh sách ghế
     * - Kiểm tra sự tồn tại của phòng
     */
    private function getGheBaseData($ma_phong, $action = null, $ghe_to_edit = null) {
        // XỬ LÝ PHÂN TRANG CHO GHẾ
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page);
        $offset = ($page - 1) * $this->gheLimit;

        // KIỂM TRA VÀ LẤY THÔNG TIN PHÒNG
        $selected_phong_info = $this->phongModel->getPhongByMa($ma_phong);
        if (!$selected_phong_info) {
            throw new Exception("Không tìm thấy phòng!");
        }

        // LẤY DANH SÁCH GHẾ VỚI PHÂN TRANG
        $danhSachGhe = $this->gheModel->getAllGheByPhongPhanTrang($ma_phong, $this->gheLimit, $offset);
        $totalRecords = $this->gheModel->countGheByPhongTotal($ma_phong);
        $totalPages = ceil($totalRecords / $this->gheLimit);
        
        // TRẢ VỀ MẢNG DỮ LIỆU CHO VIEW GHẾ
        return [
            'selected_phong_info' => $selected_phong_info,
            'danhSachGhe' => $danhSachGhe,
            'page' => $page,
            'totalPages' => $totalPages,
            'action' => $action,
            'ghe_to_edit' => $ghe_to_edit
        ];
    }

    // =================================================================
    // 3. PHƯƠNG THỨC CHUYỂN HƯỚNG VÀ THÔNG BÁO
    // =================================================================

    /**
     * CHUYỂN HƯỚNG VỚI TRẠNG THÁI (DÙNG SESSION)
     * - Lưu trạng thái thành công/lỗi vào session
     * - Xây dựng URL với các tham số
     * - Chuyển hướng và thoát chương trình
     */
    private function redirectWithStatus($success, $actionType, $controller = 'adminPhong', $action = 'index', $params = []) {
        // TẠO MÃ TRẠNG THÁI (success/error)
        $status = $success ? $actionType . '_success' : $actionType . '_error';
        
        // LƯU VÀO SESSION (FLASH MESSAGE)
        $_SESSION['flash_status'] = $status;
        
        // XÂY DỰNG URL CƠ BẢN
        $url = "index.php?controller=" . $controller . "&action=" . $action;
        
        // THÊM CÁC THAM SỐ BỔ SUNG (NẾU CÓ)
        foreach ($params as $key => $value) {
            $url .= "&" . $key . "=" . $value;
        }
        
        // CHUYỂN HƯỚNG VÀ THOÁT
        header("Location: " . $url);
        exit;
    }

    // =================================================================
    // 4. CÁC ACTION CHÍNH CHO QUẢN LÝ PHÒNG
    // =================================================================

    /**
     * HIỂN THỊ DANH SÁCH PHÒNG VỚI BỘ LỌC
     * - Action mặc định (index)
     * - Lấy dữ liệu và hiển thị view
     */
    public function index() {
        $filters = $this->getPhongFilters();
        $data = $this->getBaseData($filters);
        
        // CHUYỂN MẢNG THÀNH BIẾN ĐỂ VIEW SỬ DỤNG
        extract($data);
        require_once __DIR__ . '/../views/admin/phong_view.php';
    }

    /**
     * HIỂN THỊ FORM THÊM MỚI PHÒNG
     * - Đặt action = 'create' để view biết hiển thị form
     * - Vẫn giữ lại các bộ lọc hiện tại
     */
    public function create() {
        $filters = $this->getPhongFilters();
        $data = $this->getBaseData($filters, 'create');
        
        extract($data);
        require_once __DIR__ . '/../views/admin/phong_view.php';
    }

    /**
     * XỬ LÝ LƯU TRỮ PHÒNG MỚI
     * - Kiểm tra phương thức POST
     * - Gọi model để thêm phòng
     * - Chuyển hướng với trạng thái
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $success = $this->phongModel->addPhong(
                    $_POST['ten_phong'],
                    $_POST['ma_rap'],
                    $_POST['loai_man_hinh']
                );
                
                $this->redirectWithStatus($success, 'add');
            } catch (Exception $e) {
                $this->redirectWithStatus(false, 'add');
            }
        } else {
            header('Location: index.php?controller=adminPhong&action=create');
            exit;
        }
    }

    /**
     * HIỂN THỊ FORM SỬA PHÒNG
     * - Kiểm tra ID phòng hợp lệ
     * - Lấy thông tin phòng cần sửa
     * - Hiển thị form với dữ liệu hiện tại
     */
    public function edit() {
        $edit_id = $_GET['id'] ?? null;
        if (!$edit_id) {
            $this->redirectWithStatus(false, 'not_found');
        }

        $phong_to_edit = $this->phongModel->getPhongByMa($edit_id);
        if (!$phong_to_edit) {
            $this->redirectWithStatus(false, 'not_found');
        }

        $filters = $this->getPhongFilters();
        $data = $this->getBaseData($filters, null, $edit_id, $phong_to_edit);
        
        extract($data);
        require_once __DIR__ . '/../views/admin/phong_view.php';
    }

    /**
     * XỬ LÝ CẬP NHẬT PHÒNG
     * - Kiểm tra phương thức POST
     * - Validate dữ liệu (thiếu ID)
     * - Gọi model để cập nhật
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $ma_phong = $_POST['ma_phong'] ?? null;
                if (!$ma_phong) throw new Exception("Thiếu ID phòng.");
                
                $success = $this->phongModel->updatePhong(
                    $ma_phong,
                    $_POST['ten_phong'],
                    $_POST['ma_rap'],
                    $_POST['loai_man_hinh']
                );
                
                $this->redirectWithStatus($success, 'update');
            } catch (Exception $e) {
                $this->redirectWithStatus(false, 'update');
            }
        }
    }

    /**
     * XỬ LÝ XÓA MỘT PHÒNG
     * - Kiểm tra ID hợp lệ
     * - Xử lý ràng buộc khóa ngoại (nếu có)
     * - Chuyển hướng với trạng thái phù hợp
     */
    public function destroy() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            try {
                $success = $this->phongModel->deletePhong($id);
                if ($success) {
                    $this->redirectWithStatus(true, 'delete');
                } else {
                    // LỖI DO RÀNG BUỘC KHÓA NGOẠI (CÓ GHẾ HOẶC SUẤT CHIẾU)
                    $_SESSION['flash_status'] = 'delete_error_fk';
                    header("Location: index.php?controller=adminPhong&action=index");
                    exit;
                }
            } catch (Exception $e) {
                $this->redirectWithStatus(false, 'delete');
            }
        } else {
            header('Location: index.php?controller=adminPhong&action=index');
            exit;
        }
    }

    // =================================================================
    // 5. CÁC ACTION CHO QUẢN LÝ GHẾ
    // =================================================================

    /**
     * HIỂN THỊ QUẢN LÝ GHẾ CỦA MỘT PHÒNG
     * - Kiểm tra mã phòng hợp lệ
     * - Lấy danh sách ghế của phòng
     * - Hiển thị trang quản lý ghế
     */
    public function manageSeats() {
        $ma_phong = $_GET['ma_phong'] ?? null;
        if (!$ma_phong) {
            $this->redirectWithStatus(false, 'not_found');
        }

        try {
            $data = $this->getGheBaseData($ma_phong);
            
            extract($data);
            require_once __DIR__ . '/../views/admin/ghe_view.php';
        } catch (Exception $e) {
            $this->redirectWithStatus(false, 'not_found');
        }
    }

    /**
     * HIỂN THỊ FORM THÊM GHẾ MỚI
     * - Đặt action = 'create_ghe' để view biết hiển thị form thêm ghế
     */
    public function createGhe() {
        $ma_phong = $_GET['ma_phong'] ?? null;
        if (!$ma_phong) {
            $this->redirectWithStatus(false, 'not_found');
        }

        try {
            $data = $this->getGheBaseData($ma_phong, 'create_ghe');
            
            extract($data);
            require_once __DIR__ . '/../views/admin/ghe_view.php';
        } catch (Exception $e) {
            $this->redirectWithStatus(false, 'not_found');
        }
    }

    /**
     * XỬ LÝ THÊM GHẾ MỚI
     * - Validate mã phòng
     * - Gọi model để thêm ghế
     * - Chuyển hướng về trang quản lý ghế
     */
    public function storeGhe() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $ma_phong = $_POST['ma_phong'] ?? null;
                if (!$ma_phong) throw new Exception("Thiếu ID phòng.");

                $success = $this->gheModel->addGhe(
                    $ma_phong,
                    $_POST['vi_tri'],
                    $_POST['loai_ghe'],
                    $_POST['trang_thai']
                );
                
                $params = ['ma_phong' => $ma_phong];
                $this->redirectWithStatus($success, 'add_ghe', 'adminPhong', 'manageSeats', $params);
            } catch (Exception $e) {
                $params = ['ma_phong' => $_POST['ma_phong']];
                $this->redirectWithStatus(false, 'add_ghe', 'adminPhong', 'manageSeats', $params);
            }
        }
    }

    /**
     * HIỂN THỊ FORM SỬA GHẾ
     * - Kiểm tra mã ghế và mã phòng hợp lệ
     * - Lấy thông tin ghế cần sửa
     * - Hiển thị form với dữ liệu hiện tại
     */
    public function editGhe() {
        $ma_ghe = $_GET['ma_ghe'] ?? null;
        $ma_phong = $_GET['ma_phong'] ?? null;
        
        if (!$ma_ghe || !$ma_phong) {
            $this->redirectWithStatus(false, 'not_found');
        }

        try {
            $ghe_to_edit = $this->gheModel->getGheById($ma_ghe);
            if (!$ghe_to_edit) {
                throw new Exception("Không tìm thấy ghế!");
            }
            
            $data = $this->getGheBaseData($ma_phong, null, $ghe_to_edit);
            
            extract($data);
            require_once __DIR__ . '/../views/admin/ghe_view.php';
        } catch (Exception $e) {
            $params = ['ma_phong' => $ma_phong];
            $this->redirectWithStatus(false, 'not_found', 'adminPhong', 'manageSeats', $params);
        }
    }

    /**
     * XỬ LÝ CẬP NHẬT GHẾ
     * - Validate mã ghế và mã phòng
     * - Gọi model để cập nhật ghế
     */
    public function updateGhe() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $ma_ghe = $_POST['ma_ghe'] ?? null;
                $ma_phong = $_POST['ma_phong'] ?? null;
                
                if (!$ma_ghe || !$ma_phong) throw new Exception("Thiếu thông tin.");

                $success = $this->gheModel->updateGhe(
                    $ma_ghe,
                    $_POST['vi_tri'],
                    $_POST['loai_ghe'],
                    $_POST['trang_thai']
                );
                
                $params = ['ma_phong' => $ma_phong];
                $this->redirectWithStatus($success, 'update_ghe', 'adminPhong', 'manageSeats', $params);
            } catch (Exception $e) {
                $params = ['ma_phong' => $_POST['ma_phong']];
                $this->redirectWithStatus(false, 'update_ghe', 'adminPhong', 'manageSeats', $params);
            }
        }
    }

    /**
     * XỬ LÝ XÓA GHẾ
     * - Kiểm tra mã ghế và mã phòng hợp lệ
     * - Gọi model để xóa ghế
     * - Chuyển hướng về trang quản lý ghế
     */
    public function destroyGhe() {
        $ma_ghe = $_GET['ma_ghe'] ?? null;
        $ma_phong = $_GET['ma_phong'] ?? null;
        
        if ($ma_ghe && $ma_phong) {
            try {
                $success = $this->gheModel->deleteGhe($ma_ghe);
                $params = ['ma_phong' => $ma_phong];
                $this->redirectWithStatus($success, 'delete_ghe', 'adminPhong', 'manageSeats', $params);
            } catch (Exception $e) {
                $params = ['ma_phong' => $ma_phong];
                $this->redirectWithStatus(false, 'delete_ghe', 'adminPhong', 'manageSeats', $params);
            }
        } else {
            header('Location: index.php?controller=adminPhong&action=index');
            exit;
        }
    }
}
?>