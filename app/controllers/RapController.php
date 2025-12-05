<?php
session_start();
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/RapModel.php';
require_once __DIR__ . '/../models/DanhGiaModel.php';
require_once __DIR__ . '/../models/PhimModel.php';

class RapController {
    private $db;
    private $rapModel;
    private $danhGiaModel;
    private $phimModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        if ($this->db === null) throw new \Exception("Không thể kết nối đến CSDL.");

        $this->rapModel = new RapModel($this->db);
        $this->danhGiaModel = new DanhGiaModel($this->db);
        $this->phimModel = new PhimModel($this->db);
    }

    public function showDetails() {
        $ma_rap = $_GET['ma_rap'] ?? $_POST['ma_rap'] ?? '1';
        $selected_rap_id = $ma_rap;

        // KIỂM TRA ĐĂNG NHẬP - SỬA Ở ĐÂY
        // Bạn đang lưu thông tin trong $_SESSION['khach_hang'], không phải $_SESSION['ma_kh']
        $isLoggedIn = isset($_SESSION['khach_hang']) && isset($_SESSION['khach_hang']['ma_kh']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['noi_dung'])) {
            if (!$isLoggedIn) {
                $_SESSION['error'] = "Vui lòng đăng nhập để bình luận!";
                header("Location: index.php?controller=rap&action=showDetails&ma_rap=" . $ma_rap);
                exit;
            } else if (!empty(trim($_POST['noi_dung']))) {
                // Lấy ma_kh từ session khach_hang
                $ma_kh = $_SESSION['khach_hang']['ma_kh'];
                $noi_dung = trim($_POST['noi_dung']);
                
                $result = $this->danhGiaModel->insertDanhGia($ma_rap, $ma_kh, $noi_dung);
                
                if ($result) {
                    $_SESSION['success'] = "Đã gửi bình luận thành công!";
                } else {
                    $_SESSION['error'] = "Có lỗi xảy ra khi gửi bình luận!";
                }
                
                header("Location: index.php?controller=rap&action=showDetails&ma_rap=" . $ma_rap);
                exit;
            } else {
                $_SESSION['error'] = "Vui lòng nhập nội dung bình luận!";
                header("Location: index.php?controller=rap&action=showDetails&ma_rap=" . $ma_rap);
                exit;
            }
        }

        // Lấy thông tin rạp
        $rap = $this->rapModel->getRapById($ma_rap);
        if (!$rap) {
            echo "404 - Không tìm thấy rạp.";
            exit;
        }

        // Lấy danh sách đánh giá
        $danh_gia_list = $this->danhGiaModel->getDanhGiaByRap($ma_rap);
        
        // Lấy phim hot
        $all_hot_movies = $this->phimModel->getPhimHot($ma_rap);
        $hot_movies = array_slice($all_hot_movies, 0, 4);
        
        // Lấy tất cả rạp (cho header)
        $all_raps = $this->rapModel->getAllRap();
        $header_rap_link_template = 'index.php?controller=rap&action=showDetails&ma_rap=__MA_RAP__';
        
        
        // Hiển thị view
        require __DIR__ . '/../views/khach_hang/Rap.php';
    }

    public function index() {
        $this->showDetails();
    }
}
?>