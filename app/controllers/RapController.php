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
        $selected_rap_id = $_GET['ma_rap'] ?? '1';
        $ma_rap = $_GET['ma_rap'] ?? '1';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['noi_dung'])) {
            session_start();
            if (isset($_SESSION['ma_kh']) && !empty(trim($_POST['noi_dung']))) {
                $ma_kh = $_SESSION['ma_kh'];
                $noi_dung = trim($_POST['noi_dung']);
                $this->danhGiaModel->insertDanhGia($ma_rap, $ma_kh, $noi_dung);
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit;
            }
        }

        $rap = $this->rapModel->getRapById($ma_rap);
        if (!$rap) {
            echo "404 - Không tìm thấy rạp.";
            exit;
        }

        $danh_gia_list = $this->danhGiaModel->getDanhGiaByRap($ma_rap);
        $all_hot_movies = $this->phimModel->getPhimHot($ma_rap);
        $hot_movies = array_slice($all_hot_movies, 0, 4);
        $all_raps = $this->rapModel->getAllRap();
        $header_rap_link_template = 'index.php?controller=rap&action=showDetails&ma_rap=__MA_RAP__';
        
        require __DIR__ . '/../views/Rap.php';
    }

    public function index() {
        $this->showDetails();
    }
}
?>
