<?php
// app/controllers/AdminShowtimeController.php
session_start();

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/LichChieuModel.php';
require_once __DIR__ . '/../models/GheSuatChieuModel.php';
require_once __DIR__ . '/../models/GheModel.php';

class AdminShowtimeController {
    private $db;
    private $suatChieuModel;
    private $gheSuatChieuModel;
    private $gheModel;
    private $limit = 3; // Số suất chiếu mỗi trang

    // =================================================================
    // 1. HÀM KHỞI TẠO VÀ CẤU HÌNH CƠ BẢN
    // =================================================================

    /**
     * KHỞI TẠO CONTROLLER
     * - Thiết lập kết nối database
     * - Khởi tạo các model cần thiết cho quản lý suất chiếu
     * - Kiểm tra kết nối database thành công
     */
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        if ($this->db === null) throw new \Exception("Không thể kết nối đến CSDL.");

        $this->suatChieuModel = new LichChieuModel($this->db);
        $this->gheSuatChieuModel = new GheSuatChieuModel($this->db);
        $this->gheModel = new GheModel($this->db);
    }

    // =================================================================
    // 2. CÁC PHƯƠNG THỨC TRÍCH XUẤT VÀ XỬ LÝ DỮ LIỆU
    // =================================================================

    /**
     * LẤY DỮ LIỆU CƠ BẢN CHO TẤT CẢ CÁC ACTION
     * - Xử lý phân trang (page, limit, offset)
     * - Áp dụng bộ lọc nếu có
     * - Tính toán số trang tổng
     * - Lấy danh sách suất chiếu và thông tin liên quan
     * - Tính thông tin ghế (trống/tổng) cho mỗi suất chiếu
     */
    private function getBaseData($filters = []) {
        // XỬ LÝ PHÂN TRANG
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page); // ĐẢM BẢO PAGE ÍT NHẤT LÀ 1
        $offset = ($page - 1) * $this->limit;

        // LẤY DỮ LIỆU SUẤT CHIẾU VỚI PHÂN TRANG VÀ BỘ LỌC
        $totalRows = $this->suatChieuModel->countSuatChieu($filters);
        $totalPages = ceil($totalRows / $this->limit);
        $danhSachSuatChieu = $this->suatChieuModel->getSuatChieuPhanTrang($filters, $this->limit, $offset);

        // THÊM THÔNG TIN GHẾ CHO TỪNG SUẤT CHIẾU
        foreach ($danhSachSuatChieu as $key => $suatChieu) {
            // ĐẾM SỐ GHẾ TRỐNG CHO SUẤT CHIẾU NÀY
            $soGheTrong = $this->gheSuatChieuModel->countGheTrong($suatChieu['ma_suat_chieu']);
            // ĐẾM TỔNG SỐ GHẾ TRONG PHÒNG
            $tongSoGhe = $this->gheModel->countGheByPhong($suatChieu['ma_phong']);
            
            // THÊM THÔNG TIN VÀO MẢNG SUẤT CHIẾU
            $danhSachSuatChieu[$key]['so_ghe_trong'] = $soGheTrong;
            $danhSachSuatChieu[$key]['tong_so_ghe'] = $tongSoGhe;
        }

        // TRẢ VỀ MẢNG DỮ LIỆU ĐẦY ĐỦ CHO VIEW
        return [
            'danhSachSuatChieu' => $danhSachSuatChieu,
            'danhSachPhim' => $this->suatChieuModel->getAllPhim(),      // CHO DROPDOWN PHIM
            'danhSachPhong' => $this->suatChieuModel->getAllPhong(),    // CHO DROPDOWN PHÒNG
            'danhSachRap' => $this->suatChieuModel->getAllRap(),       // CHO DROPDOWN RẠP (FILTER)
            'page' => $page,
            'totalPages' => $totalPages
        ];
    }

    /**
     * LẤY BỘ LỌC TỪ REQUEST
     * - Xử lý các tham số GET từ form filter
     * - Chỉ thêm vào mảng nếu giá trị không rỗng
     * - Trả về mảng filters để sử dụng trong query
     */
    private function getFilters() {
        $filters = [];
        
        // LỌC THEO NGÀY CHIẾU
        if (!empty($_GET['ngay_chieu'])) {
            $filters['ngay_chieu'] = $_GET['ngay_chieu'];
        }
        
        // LỌC THEO TÊN RẠP
        if (!empty($_GET['ten_rap'])) {
            $filters['ten_rap'] = $_GET['ten_rap'];
        }
        
        // LỌC THEO TÊN PHIM
        if (!empty($_GET['ten_phim'])) {
            $filters['ten_phim'] = $_GET['ten_phim'];
        }
        
        return $filters;
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
        
        // CHUYỂN HƯỚNG VỀ TRANG DANH SÁCH SUẤT CHIẾU
        header("Location: index.php?controller=adminShowtime&action=index");
        exit;
    }

    // =================================================================
    // 3. CÁC ACTION CHÍNH CHO QUẢN LÝ SUẤT CHIẾU (CRUD)
    // =================================================================

    /**
     * HIỂN THỊ DANH SÁCH SUẤT CHIẾU VỚI BỘ LỌC
     * - Action mặc định (index)
     * - Lấy dữ liệu và hiển thị view
     * - Áp dụng bộ lọc nếu có
     */
    public function index() {
        $filters = $this->getFilters();
        $data = $this->getBaseData($filters);
        
        // CHUYỂN MẢNG THÀNH BIẾN ĐỂ VIEW SỬ DỤNG
        extract($data);
        
        require_once __DIR__ . '/../views/admin/showtime_view.php';
    }

    /**
     * HIỂN THỊ FORM THÊM MỚI SUẤT CHIẾU
     * - Lấy dữ liệu cơ bản và thêm action = 'create'
     * - Hiển thị form thêm suất chiếu mới
     * - Giữ lại các bộ lọc hiện tại
     */
    public function create() {
        $filters = $this->getFilters();
        $data = $this->getBaseData($filters);
        
        // THÊM BIẾN ACTION ĐỂ VIEW BIẾT HIỂN THỊ FORM THÊM MỚI
        $data['action'] = 'create';
        
        // CHUYỂN MẢNG THÀNH BIẾN ĐỂ VIEW SỬ DỤNG
        extract($data);
        
        require_once __DIR__ . '/../views/admin/showtime_view.php';
    }

    /**
     * XỬ LÝ THÊM SUẤT CHIẾU MỚI
     * - Kiểm tra phương thức POST
     * - Gọi model để thêm suất chiếu
     * - Chuyển hướng với trạng thái thành công/lỗi
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $success = $this->suatChieuModel->addSuatChieu(
                    $_POST['ma_phim'],
                    $_POST['ma_phong'],
                    $_POST['ngay_chieu'],
                    $_POST['gio_bat_dau'],
                    $_POST['gio_ket_thuc'],
                    $_POST['gia_ve_co_ban']
                );

                $this->redirectWithStatus($success, 'add');
            } catch (Exception $e) {
                // TRƯỜNG HỢP CŨ (CÓ THỂ XÓA SAU KHI TEST)
                header("Location: index.php?controller=adminShowtime&action=index&status=add_error");
                exit;
            }
        }
    }

    /**
     * HIỂN THỊ FORM CHỈNH SỬA SUẤT CHIẾU
     * - Kiểm tra ID suất chiếu hợp lệ
     * - Lấy thông tin suất chiếu cần sửa
     * - Hiển thị form với dữ liệu hiện tại
     */
    public function edit() {
        $edit_id = $_GET['id'] ?? null;
        if (!$edit_id) {
            header('Location: index.php?controller=adminShowtime&action=index');
            exit;
        }

        $suatChieuToEdit = $this->suatChieuModel->getSuatChieuById($edit_id);
        if (!$suatChieuToEdit) {
            header('Location: index.php?controller=adminShowtime&action=index&status=not_found');
            exit;
        }

        $filters = $this->getFilters();
        $data = $this->getBaseData($filters);
        
        // THÊM CÁC BIẾN CHO FORM EDIT
        $data['edit_id'] = $edit_id;
        $data['suatChieuToEdit'] = $suatChieuToEdit;
        
        // CHUYỂN MẢNG THÀNH BIẾN ĐỂ VIEW SỬ DỤNG
        extract($data);
        
        require_once __DIR__ . '/../views/admin/showtime_view.php';
    }

    /**
     * XỬ LÝ CẬP NHẬT SUẤT CHIẾU
     * - Kiểm tra phương thức POST
     * - Gọi model để cập nhật suất chiếu
     * - Chuyển hướng với trạng thái thành công/lỗi
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $success = $this->suatChieuModel->updateSuatChieu(
                    $_POST['ma_suat_chieu'],
                    $_POST['ma_phim'],
                    $_POST['ma_phong'],
                    $_POST['ngay_chieu'],
                    $_POST['gio_bat_dau'],
                    $_POST['gio_ket_thuc'],
                    $_POST['gia_ve_co_ban']
                );

                $this->redirectWithStatus($success, 'update');
            } catch (Exception $e) {
                // TRƯỜNG HỢP CŨ (CÓ THỂ XÓA SAU KHI TEST)
                header("Location: index.php?controller=adminShowtime&action=index&status=update_error");
                exit;
            }
        }
    }

    /**
     * XỬ LÝ XÓA SUẤT CHIẾU
     * - Kiểm tra ID hợp lệ
     * - Gọi model để xóa suất chiếu
     * - Chuyển hướng với trạng thái thành công/lỗi
     */
    public function destroy() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            try {
                $success = $this->suatChieuModel->deleteSuatChieu($id);
                $this->redirectWithStatus($success, 'delete');
            } catch (Exception $e) {
                // TRƯỜNG HỢP CŨ (CÓ THỂ XÓA SAU KHI TEST)
                header("Location: index.php?controller=adminShowtime&action=index&status=delete_error");
                exit;
            }
        } else {
            header('Location: index.php?controller=adminShowtime&action=index');
            exit;
        }
    }
}
?>