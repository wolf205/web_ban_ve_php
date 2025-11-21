<?php
// app/controllers/PhimController.php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/PhimModel.php';
require_once __DIR__ . '/../models/LichChieuModel.php';
require_once __DIR__ . '/../models/RapModel.php';

class PhimController {

    private $db;
    private $phimModel;
    private $lichChieuModel;
    private $rapModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();

        $this->phimModel = new PhimModel($this->db);
        $this->lichChieuModel = new LichChieuModel($this->db);
        $this->rapModel = new RapModel($this->db);
    }

    /**
     * Action 1: Hiển thị trang danh sách Phim (ĐÃ LỌC THEO RẠP)
     */
    public function index() {
        // Lấy mã rạp từ URL, mặc định là rạp '1'
        $selected_rap_id = $_GET['ma_rap'] ?? '1';

        // Lấy dữ liệu cho cả 3 tab, ĐÃ LỌC THEO RẠP
        $phimDangChieu = $this->phimModel->getPhimDangChieu($selected_rap_id);
        $phimSapChieu = $this->phimModel->getPhimSapChieu($selected_rap_id);
        $suatChieuDacBiet = $this->phimModel->getPhimHot($selected_rap_id);

        // Lấy dữ liệu cho header
        $all_raps = $this->rapModel->getAllRap();
        $rap = $this->rapModel->getRapById($selected_rap_id); // Lấy rạp đang chọn
        $header_rap_link_template = 'index.php?controller=phim&action=index&ma_rap=__MA_RAP__';
        // Tải View
        require_once __DIR__ . '/../views/Phim_view.php';
    }

    /**
     * Action 2: Hiển thị trang Chi Tiết Phim (ĐÃ LỌC LỊCH CHIẾU THEO RẠP)
     */
    public function detail() {
        // Lấy ID Phim VÀ MÃ RẠP từ URL
        if (!isset($_GET['id']) || !isset($_GET['ma_rap'])) {
            die("Lỗi: Thiếu ID phim hoặc Mã rạp.");
        }
        $ma_phim = $_GET['id'];
        $ma_rap = $_GET['ma_rap']; // <-- Lấy mã rạp

        // Lấy dữ liệu chi tiết phim (vẫn như cũ)
        $phim = $this->phimModel->getPhimById($ma_phim);
        if (!$phim) die("Lỗi: Phim không tồn tại.");

        // Lấy lịch chiếu, LỌC THEO CẢ PHIM VÀ RẠP
        $suatChieuCuaPhim = $this->lichChieuModel->getLichChieuByPhimId($ma_phim, $ma_rap);

        // Xử lý nhóm lịch chiếu (giữ nguyên)
        $lichChieuTheoNgay = [];
        $daysOfWeek = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];
        foreach ($suatChieuCuaPhim as $suat) {
            $ngayChieu = $suat['ngay_chieu'];
            $dateObj = new DateTime($ngayChieu);
            $ngayFormatted = $dateObj->format('d/m');
            $thuFormatted = $daysOfWeek[$dateObj->format('w')];
            $keyNgay = $ngayFormatted . ' - ' . $thuFormatted;
            $dinhDang = "2D PHỤ ĐỀ"; 
            if (!isset($lichChieuTheoNgay[$keyNgay])) {
                $lichChieuTheoNgay[$keyNgay] = ['dinh_dang' => $dinhDang, 'gio' => []];
            }
            $lichChieuTheoNgay[$keyNgay]['gio'][] = [
                'id' => $suat['ma_suat_chieu'],
                'thoi_gian' => date('H:i', strtotime($suat['gio_bat_dau']))
            ];
        }

        // Lấy dữ liệu cho header
        $all_raps = $this->rapModel->getAllRap();
        $rap = $this->rapModel->getRapById($ma_rap); // Lấy rạp đang chọn
        $header_rap_link_template = 'index.php?controller=phim&action=detail&ma_rap=__MA_RAP__';

        // Tải View
        require_once __DIR__ . '/../views/chi_tiet_phim_view.php';
    }
}
?>