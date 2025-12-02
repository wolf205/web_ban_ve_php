<?php
// app/controllers/ComboThanhToanController.php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/ComboModel.php';
require_once __DIR__ . '/../models/GheSuatChieuModel.php';
require_once __DIR__ . '/../models/RapModel.php';
class ComboThanhToanController
{
    private $db;
    private $comboModel;
    private $gheSuatChieuModel;
    private $rapModel;

    public function __construct()
    {
        $database   = new Database();
        $this->db   = $database->getConnection();
        $this->comboModel = new ComboModel($this->db);
        $this->gheSuatChieuModel= new GheSuatChieuModel($this->db);
        $this->rapModel         = new RapModel($this->db);
    }

    /**
     * Hiển thị màn hình chọn combo & thanh toán
     * Gợi ý router: index.php?controller=combothanhtoan
     */
    public function index()
    {
        if (!isset($_GET['ma_suat_chieu'])) {
            die("Lỗi: Thiếu mã suất chiếu.");
        } 
        $ma_suat_chieu = (int)$_GET['ma_suat_chieu'];  
        if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $ticketSubtotal     = $_SESSION['ticket_subtotal']     ?? 0;
    $selectedSeatsLabel = $_SESSION['selected_seat_names'] ?? '—';
    $thongTinSuat       = $_SESSION['thong_tin_suat']      ?? null;

        // 1. Lấy danh sách combo từ DB
        $dsCombo = $this->comboModel->getAllCombo();
        // 1. Lấy full thông tin suất chiếu + phim + rạp + phòng
        $thongTinSuat = $this->gheSuatChieuModel->getThongTinSuatChieu($ma_suat_chieu);
        if (!$thongTinSuat) {
            die("Không tìm thấy suất chiếu.");
        }

        // 2. Chuẩn hoá đường dẫn ảnh (nếu trong DB chỉ lưu 'publics/images/...') 
        foreach ($dsCombo as &$combo) {
            $path = $combo['anh_minh_hoa'] ?? '';
            if ($path !== '' &&
                strpos($path, 'http') !== 0 &&  // không phải URL tuyệt đối
                strpos($path, '../') !== 0)     // chưa có ../ ở đầu
            {
                // tuỳ cấu trúc project của bạn, chỉnh lại prefix này cho đúng
                $combo['anh_minh_hoa'] = ltrim($path, '/');
            }
        }
        unset($combo);
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
        // 3. Truyền dữ liệu sang view combo_thanh_toan.php
        // - $dsCombo: danh sách combo từ DB
        // - $ticketSubtotal: tổng tiền vé từ bước trước
        require_once __DIR__ . '/../views/combo_thanh_toan.php';
    }
}
?>
