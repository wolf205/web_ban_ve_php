<?php
// app/controllers/ChonGheController.php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/GheModel.php';
require_once __DIR__ . '/../models/GheSuatChieuModel.php';
require_once __DIR__ . '/../models/RapModel.php';

class ChonGheController
{
    private $db;
    private $gheModel;
    private $gheSuatChieuModel;
    private $rapModel;

    public function __construct()
    {
        // Kết nối DB giống PhimController
        $database = new Database();
        $this->db = $database->getConnection();

        // Khởi tạo model
        $this->gheModel         = new GheModel($this->db);
        $this->gheSuatChieuModel= new GheSuatChieuModel($this->db);
        $this->rapModel         = new RapModel($this->db);
    }

    /**
     * Action: Hiển thị trang Chọn Ghế cho 1 suất chiếu
     */
    public function index()
    {
        if (!isset($_GET['ma_suat_chieu'])) {
            die("Lỗi: Thiếu mã suất chiếu.");
        }

        $ma_suat_chieu = (int)$_GET['ma_suat_chieu'];
            // 1. Lấy full thông tin suất chiếu + phim + rạp + phòng
        $thongTinSuat = $this->gheSuatChieuModel->getThongTinSuatChieu($ma_suat_chieu);
        if (!$thongTinSuat) {
            die("Không tìm thấy suất chiếu.");
        }
    // ⭐⭐ NẾU LÀ REQUEST POST (bấm TIẾP TỤC) → xử lý ghế, lưu SESSION, redirect ⭐⭐
    if ($_SERVER['REQUEST_METHOD'] === 'POST'
        && isset($_POST['seat_ids'], $_POST['seat_names'])) {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $dsGheId   = json_decode($_POST['seat_ids'], true) ?? [];
        $seatNames = $_POST['seat_names'];

        // Lấy thông tin ghế để biết loại ghế (Thường / VIP / Đôi)
        $gheList = $this->gheModel->getGheByIds($dsGheId);

        $giaBase     = (float)$thongTinSuat['gia_ve_co_ban'];
        $tongTienVe  = 0;

        foreach ($gheList as $ghe) {
            $loai = strtolower(trim($ghe['loai_ghe'] ?? ''));

            if ($loai === 'vip') {
                $tongTienVe += $giaBase * 1.2;
            } elseif ($loai === 'đôi' || $loai === 'doi' || $loai === 'ghe doi') {
                $tongTienVe += $giaBase * 2;
            } else {
                $tongTienVe += $giaBase;
            }
        }

        // Lưu vào SESSION cho bước combo
        $_SESSION['ticket_subtotal']      = $tongTienVe;
        $_SESSION['selected_seat_ids']    = $dsGheId;
        $_SESSION['selected_seat_names']  = $seatNames;
        $_SESSION['thong_tin_suat']       = $thongTinSuat;

        // Chuyển sang ComboThanhToanController
        header("Location: index.php?controller=combothanhtoan&ma_suat_chieu=".$ma_suat_chieu);
        exit;
    }

        // 2. Lấy ghế + trạng thái cho suất chiếu
        $dsGheDayDu = $this->gheSuatChieuModel->getChiTietGheBySuatChieu($ma_suat_chieu);

        // 3. Gom ghế theo hàng + sort để view dùng
        $rows = $this->gheSuatChieuModel->groupSeatsByRow($dsGheDayDu);

        // 4. Chuẩn hoá các biến hiển thị cho view
        $ten_phim    = $thongTinSuat['ten_phim'];
        $the_loai    = $thongTinSuat['the_loai'];
        $thoi_luong  = (int)$thongTinSuat['thoi_luong'];
        $phan_loai   = 'T' . (int)$thongTinSuat['gioi_han_do_tuoi'];

        $ten_rap     = $thongTinSuat['ten_rap'];
        $ngay_chieu  = date('d/m/Y', strtotime($thongTinSuat['ngay_chieu']));
        $gio_chieu   = date('H:i', strtotime($thongTinSuat['gio_bat_dau']));
        $phong_chieu = $thongTinSuat['ten_phong'];

        $poster      = $thongTinSuat['anh_trailer'];
        $giaVeCoBan  = (float)$thongTinSuat['gia_ve_co_ban'];

        // 5. Dữ liệu cho phần filter rạp giống PhimController (nếu header dùng)
        $ma_rap   = (int)$thongTinSuat['ma_rap'];
        $all_raps = $this->rapModel->getAllRap();
        $rap      = $this->rapModel->getRapById($ma_rap);

        // 6. Gọi view
        require_once __DIR__ . '/../views/chon_ghe.php';
    }
}
?>
