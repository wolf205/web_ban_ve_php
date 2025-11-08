<?php
// app/models/LichChieuModel.php

class LichChieuModel {
    private $conn;

    // ==============================
    // HÀM KHỞI TẠO
    // ==============================
    public function __construct($db) {
        $this->conn = $db;
    }

    // ==============================
    // HÀM LẤY SUẤT CHIẾU THEO NGÀY VÀ RẠP
    // ==============================
    /**
     * Lấy danh sách suất chiếu
     * @param string $ngay_chieu - Lọc theo ngày (ví dụ: '2025-10-25')
     * @param string $ma_rap - Lọc theo mã rạp (ví dụ: '1')
     */
    public function getLichChieu($ngay_chieu = null, $ma_rap = null) {
        
        // Cập nhật câu SQL:
        // 1. Thêm JOIN với bảng `phong` để lấy `ma_rap`
        // 2. Sửa điều kiện WHERE từ `ma_phim` thành `ma_rap`
        
        $sql = "SELECT sc.ma_suat_chieu, sc.ma_phim, p.ten_phim, sc.ma_phong, 
                       sc.ngay_chieu, sc.gio_bat_dau, sc.gio_ket_thuc,
                       ph.ma_rap -- Thêm cột này để dễ debug (tùy chọn)
                FROM suat_chieu sc
                JOIN phim p ON sc.ma_phim = p.ma_phim
                JOIN phong ph ON sc.ma_phong = ph.ma_phong -- THÊM JOIN NÀY
                WHERE 1=1";

        // Điều kiện lọc động
        if (!empty($ngay_chieu)) {
            $sql .= " AND sc.ngay_chieu = :ngay_chieu";
        }
        
        // SỬA LẠI ĐIỀU KIỆN LỌC
        if (!empty($ma_rap)) {
            $sql .= " AND ph.ma_rap = :ma_rap"; // Lọc theo mã rạp từ bảng phòng
        }

        $sql .= " ORDER BY sc.ngay_chieu ASC, sc.gio_bat_dau ASC";

        $stmt = $this->conn->prepare($sql);

        // Gán tham số động
        if (!empty($ngay_chieu)) {
            $stmt->bindParam(':ngay_chieu', $ngay_chieu);
        }
        
        // SỬA LẠI BIND PARAM
        if (!empty($ma_rap)) {
            $stmt->bindParam(':ma_rap', $ma_rap);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>