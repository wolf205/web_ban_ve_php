<?php
// app/controllers/TrangChuController.php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/PhimModel.php';
require_once __DIR__ . '/../models/RapModel.php';

class TrangChuController {
    
    private $db;
    private $phimModel;
    private $rapModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        
        $this->phimModel = new PhimModel($this->db);
        $this->rapModel = new RapModel($this->db);
    }

    public function index() {
        // Lấy mã rạp từ URL, mặc định là rạp '1'
        $selected_rap_id = $_GET['ma_rap'] ?? '1';

        // Lấy dữ liệu cho Banner (Phim Hot)
        // (Chúng ta giả định banner là phim hot chung, không lọc theo rạp)
        $banners = $this->phimModel->getPhimHot();

        // Lấy dữ liệu phim cho mục tabs, ĐÃ LỌC THEO RẠP
        $phimDangChieu = $this->phimModel->getPhimDangChieu($selected_rap_id);
        $phimSapChieu = $this->phimModel->getPhimSapChieu($selected_rap_id);
        $suatChieuDacBiet = $this->phimModel->getPhimHot($selected_rap_id); // Phim hot cũng lọc theo rạp

        // Lấy dữ liệu cho header
        $all_raps = $this->rapModel->getAllRap();
        $rap = $this->rapModel->getRapById($selected_rap_id); // Lấy rạp đang chọn

        session_start();
    
        // Tải View
        require_once __DIR__ . '/../views/trang_chu_view.php';

}
}
?>