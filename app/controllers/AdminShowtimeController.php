<?php
// app/controllers/AdminShowtimeController.php
session_start();

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/LichChieuModel.php';
require_once __DIR__ . '/../models/GheSuatChieuModel.php';
require_once __DIR__ . '/../models/GheModel.php'; // Thêm import GheModel

class AdminShowtimeController {
    private $db;
    private $suatChieuModel;
    private $gheSuatChieuModel;
    private $gheModel; // Thêm property cho GheModel

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        if ($this->db === null) throw new \Exception("Không thể kết nối đến CSDL.");

        $this->suatChieuModel = new LichChieuModel($this->db);
        $this->gheSuatChieuModel = new GheSuatChieuModel($this->db);
        $this->gheModel = new GheModel($this->db); // Khởi tạo GheModel
    }

    /**
     * Hiển thị danh sách suất chiếu
     */
    public function index() {
        $danhSachSuatChieu = $this->suatChieuModel->getAllSuatChieu();
        
        // Lấy số ghế trống và tổng số ghế cho mỗi suất chiếu
        foreach ($danhSachSuatChieu as $key => $suatChieu) {
            $soGheTrong = $this->gheSuatChieuModel->countGheTrong($suatChieu['ma_suat_chieu']);
            $tongSoGhe = $this->gheModel->countGheByPhong($suatChieu['ma_phong']); // Lấy tổng số ghế từ phòng
            
            $danhSachSuatChieu[$key]['so_ghe_trong'] = $soGheTrong;
            $danhSachSuatChieu[$key]['tong_so_ghe'] = $tongSoGhe; // Sử dụng giá trị thực tế
        }

        $danhSachPhim = $this->suatChieuModel->getAllPhim();
        $danhSachPhong = $this->suatChieuModel->getAllPhong();

        require_once __DIR__ . '/../views/showtime_view.php';
    }

    /**
     * Hiển thị form thêm mới
     */
    public function create() {
        $danhSachSuatChieu = $this->suatChieuModel->getAllSuatChieu();
        $danhSachPhim = $this->suatChieuModel->getAllPhim();
        $danhSachPhong = $this->suatChieuModel->getAllPhong();

        // Lấy số ghế trống và tổng số ghế cho mỗi suất chiếu
        foreach ($danhSachSuatChieu as $key => $suatChieu) {
            $soGheTrong = $this->gheSuatChieuModel->countGheTrong($suatChieu['ma_suat_chieu']);
            $tongSoGhe = $this->gheModel->countGheByPhong($suatChieu['ma_phong']); // Lấy tổng số ghế từ phòng
            
            $danhSachSuatChieu[$key]['so_ghe_trong'] = $soGheTrong;
            $danhSachSuatChieu[$key]['tong_so_ghe'] = $tongSoGhe; // Sử dụng giá trị thực tế
        }
        
        $action = 'create';
        
        require_once __DIR__ . '/../views/showtime_view.php';
    }

    /**
     * Xử lý thêm suất chiếu mới
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

                if ($success) {
                    header("Location: index.php?controller=adminShowtime&action=index&status=add_success");
                } else {
                    throw new Exception("Lỗi khi thêm suất chiếu");
                }
            } catch (Exception $e) {
                header("Location: index.php?controller=adminShowtime&action=index&status=add_error");
            }
        }
    }

    /**
     * Hiển thị form chỉnh sửa
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

        $danhSachSuatChieu = $this->suatChieuModel->getAllSuatChieu();
        $danhSachPhim = $this->suatChieuModel->getAllPhim();
        $danhSachPhong = $this->suatChieuModel->getAllPhong();

        // Lấy số ghế trống và tổng số ghế cho mỗi suất chiếu
        foreach ($danhSachSuatChieu as $key => $suatChieu) {
            $soGheTrong = $this->gheSuatChieuModel->countGheTrong($suatChieu['ma_suat_chieu']);
            $tongSoGhe = $this->gheModel->countGheByPhong($suatChieu['ma_phong']); // Lấy tổng số ghế từ phòng
            
            $danhSachSuatChieu[$key]['so_ghe_trong'] = $soGheTrong;
            $danhSachSuatChieu[$key]['tong_so_ghe'] = $tongSoGhe; // Sử dụng giá trị thực tế
        }
        
        $edit_id = $edit_id;
        
        require_once __DIR__ . '/../views/showtime_view.php';
    }

    /**
     * Xử lý cập nhật suất chiếu
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

                if ($success) {
                    header("Location: index.php?controller=adminShowtime&action=index&status=update_success");
                } else {
                    throw new Exception("Lỗi khi cập nhật suất chiếu");
                }
            } catch (Exception $e) {
                header("Location: index.php?controller=adminShowtime&action=index&status=update_error");
            }
        }
    }

    /**
     * Xử lý xóa suất chiếu
     */
    public function destroy() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            try {
                if ($this->suatChieuModel->deleteSuatChieu($id)) {
                    header("Location: index.php?controller=adminShowtime&action=index&status=delete_success");
                } else {
                    header("Location: index.php?controller=adminShowtime&action=index&status=delete_error");
                }
            } catch (Exception $e) {
                header("Location: index.php?controller=adminShowtime&action=index&status=delete_error");
            }
        } else {
            header('Location: index.php?controller=adminShowtime&action=index');
        }
    }
}
?>