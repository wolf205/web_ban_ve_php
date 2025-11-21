<?php
// app/models/PhimModel.php

class PhimModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * LẤY TẤT CẢ CÁC PHIM
     */
    public function getAllPhim() {
        $sql = "SELECT ma_phim, ten_phim, the_loai, thoi_luong, dao_dien, dien_vien, mo_ta, ngay_khoi_chieu, anh_trailer, hot, gioi_han_do_tuoi 
                FROM phim";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * LẤY PHIM THEO MÃ (Đã fix PARAM_INT)
     */
    public function getPhimById($ma_phim) {
        $sql = "SELECT ma_phim, ten_phim, the_loai, thoi_luong, dao_dien, dien_vien, mo_ta, ngay_khoi_chieu, anh_trailer, hot, gioi_han_do_tuoi 
                FROM phim 
                WHERE ma_phim = :ma_phim";
        $stmt = $this->conn->prepare($sql);
        // SỬA LỖI: Chỉ định rõ đây là SỐ (INT)
        $stmt->bindParam(':ma_phim', $ma_phim, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * HÀM ĐÃ SỬA: LẤY CÁC PHIM HOT (theo rạp)
     */
    public function getPhimHot($ma_rap = null) {
        $sql = "SELECT DISTINCT p.* FROM phim p
                JOIN suat_chieu sc ON p.ma_phim = sc.ma_phim
                JOIN phong ph ON sc.ma_phong = ph.ma_phong
                WHERE p.hot = 1";
        
        if ($ma_rap !== null) {
            $sql .= " AND ph.ma_rap = :ma_rap";
        }
        
        $stmt = $this->conn->prepare($sql);
        if ($ma_rap !== null) {
            // SỬA LỖI: Chỉ định rõ đây là SỐ (INT)
            $stmt->bindParam(':ma_rap', $ma_rap, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * HÀM ĐÃ SỬA: LẤY CÁC PHIM ĐANG CHIẾU (theo rạp)
     */
    public function getPhimDangChieu($ma_rap) {
        $sql = "SELECT DISTINCT p.* FROM phim p
                JOIN suat_chieu sc ON p.ma_phim = sc.ma_phim
                JOIN phong ph ON sc.ma_phong = ph.ma_phong
                WHERE p.ngay_khoi_chieu = '2025-10-25'
                  AND ph.ma_rap = :ma_rap";
                  
        $stmt = $this->conn->prepare($sql);
        // SỬA LỖI: Chỉ định rõ đây là SỐ (INT)
        $stmt->bindParam(':ma_rap', $ma_rap, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * HÀM ĐÃ SỬA: LẤY CÁC PHIM SẮP CHIẾU (theo rạp)
     */
    public function getPhimSapChieu($ma_rap) {
        $sql = "SELECT DISTINCT p.* FROM phim p
                JOIN suat_chieu sc ON p.ma_phim = sc.ma_phim
                JOIN phong ph ON sc.ma_phong = ph.ma_phong
                WHERE p.ngay_khoi_chieu >= '2025-10-26'
                  AND ph.ma_rap = :ma_rap";
                  
        $stmt = $this->conn->prepare($sql);
        // SỬA LỖI: Chỉ định rõ đây là SỐ (INT)
        $stmt->bindParam(':ma_rap', $ma_rap, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>