<?php
// app/models/PhimModel.php

class PhimModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * 1. LẤY TẤT CẢ CÁC PHIM
     * (Bao gồm gioi_han_do_tuoi)
     */
    public function getAllPhim() {
        $sql = "SELECT ma_phim, ten_phim, the_loai, thoi_luong, dao_dien, dien_vien, mo_ta, ngay_khoi_chieu, anh_trailer, hot, gioi_han_do_tuoi 
                FROM phim";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 2. LẤY PHIM THEO MÃ
     * (Bao gồm gioi_han_do_tuoi)
     */
    public function getPhimById($ma_phim) {
        $sql = "SELECT ma_phim, ten_phim, the_loai, thoi_luong, dao_dien, dien_vien, mo_ta, ngay_khoi_chieu, anh_trailer, hot, gioi_han_do_tuoi 
                FROM phim 
                WHERE ma_phim = :ma_phim";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':ma_phim', $ma_phim);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * 3. LẤY CÁC PHIM HOT
     * (Bao gồm gioi_han_do_tuoi)
     */
    public function getPhimHot() {
        $sql = "SELECT ma_phim, ten_phim, the_loai, thoi_luong, dao_dien, dien_vien, mo_ta, ngay_khoi_chieu, anh_trailer, hot, gioi_han_do_tuoi 
                FROM phim 
                WHERE hot = 1"; // Lưu ý: Bảng của bạn là 'hot' kiểu BIT
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 4. LẤY CÁC PHIM ĐANG CHIẾU (THEO NGÀY CỐ ĐỊNH)
     * (Bao gồm gioi_han_do_tuoi)
     */
    public function getPhimDangChieu() {
        $sql = "SELECT ma_phim, ten_phim, the_loai, thoi_luong, dao_dien, dien_vien, mo_ta, ngay_khoi_chieu, anh_trailer, hot, gioi_han_do_tuoi 
                FROM phim 
                WHERE ngay_khoi_chieu = '2025-10-25'"; // <-- Đã trả về ngày cố định
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 5. LẤY CÁC PHIM SẮP CHIẾU (THEO NGÀY CỐ ĐỊNH)
     * (Bao gồm gioi_han_do_tuoi)
     */
    public function getPhimSapChieu() {
        $sql = "SELECT ma_phim, ten_phim, the_loai, thoi_luong, dao_dien, dien_vien, mo_ta, ngay_khoi_chieu, anh_trailer, hot, gioi_han_do_tuoi 
                FROM phim 
                WHERE ngay_khoi_chieu >= '2025-10-26'"; // <-- Đã trả về ngày cố định
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>