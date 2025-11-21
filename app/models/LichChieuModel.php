<?php
// app/models/LichChieuModel.php

class LichChieuModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // =================================================================
    // 1. KHU VỰC DÀNH CHO TRANG KHÁCH HÀNG (PUBLIC)
    // =================================================================

    /**
     * Lấy danh sách suất chiếu để hiển thị trang bán vé (Giữ nguyên)
     */
    public function getLichChieu($ngay_chieu = null, $ma_rap = null) {
        $sql = "SELECT sc.ma_suat_chieu, sc.ma_phim, p.ten_phim, sc.ma_phong, 
                       sc.ngay_chieu, sc.gio_bat_dau, sc.gio_ket_thuc,
                       ph.ma_rap
                FROM suat_chieu sc
                JOIN phim p ON sc.ma_phim = p.ma_phim
                JOIN phong ph ON sc.ma_phong = ph.ma_phong
                WHERE 1=1";

        if (!empty($ngay_chieu)) {
            $sql .= " AND sc.ngay_chieu = :ngay_chieu";
        }
        
        if (!empty($ma_rap)) {
            $sql .= " AND ph.ma_rap = :ma_rap";
        }

        $sql .= " ORDER BY sc.ngay_chieu ASC, sc.gio_bat_dau ASC";

        $stmt = $this->conn->prepare($sql);

        if (!empty($ngay_chieu)) {
            $stmt->bindParam(':ngay_chieu', $ngay_chieu);
        }
        
        if (!empty($ma_rap)) {
            $stmt->bindParam(':ma_rap', $ma_rap);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * HÀM ĐÃ SỬA: LẤY SUẤT CHIẾU THEO MÃ PHIM VÀ MÃ RẠP
     */
    public function getLichChieuByPhimId($ma_phim, $ma_rap) {
        
        $sql = "SELECT sc.ma_suat_chieu, sc.ma_phim, p.ten_phim, sc.ma_phong, 
                       sc.ngay_chieu, sc.gio_bat_dau, sc.gio_ket_thuc,
                       ph.ma_rap
                FROM suat_chieu sc
                JOIN phim p ON sc.ma_phim = p.ma_phim
                JOIN phong ph ON sc.ma_phong = ph.ma_phong
                WHERE sc.ma_phim = :ma_phim
                  AND ph.ma_rap = :ma_rap"; // <-- THÊM LỌC RẠP

        $sql .= " ORDER BY sc.ngay_chieu ASC, sc.gio_bat_dau ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':ma_phim', $ma_phim);
        $stmt->bindParam(':ma_rap', $ma_rap); // <-- THÊM BIND PARAM
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =================================================================
    // 2. KHU VỰC DÀNH CHO TRANG QUẢN TRỊ (ADMIN)
    // =================================================================

    /**
     * Lấy tất cả suất chiếu với thông tin đầy đủ
     */
    public function getAllSuatChieu() {
        $sql = "SELECT 
                    sc.ma_suat_chieu,
                    sc.ma_phong,
                    p.ten_phim,
                    r.ten_rap,
                    ph.ten_phong,
                    sc.ngay_chieu,
                    sc.gio_bat_dau,
                    sc.gio_ket_thuc,
                    sc.gia_ve_co_ban as gia_ve
                FROM suat_chieu sc
                JOIN phim p ON sc.ma_phim = p.ma_phim
                JOIN phong ph ON sc.ma_phong = ph.ma_phong
                JOIN rap r ON ph.ma_rap = r.ma_rap
                ORDER BY sc.ngay_chieu DESC, sc.gio_bat_dau ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy thông tin suất chiếu theo ID
     */
    public function getSuatChieuById($ma_suat_chieu) {
        $sql = "SELECT * FROM suat_chieu WHERE ma_suat_chieu = :ma_suat_chieu";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':ma_suat_chieu', $ma_suat_chieu);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Thêm suất chiếu mới
     */
    public function addSuatChieu($ma_phim, $ma_phong, $ngay_chieu, $gio_bat_dau, $gio_ket_thuc, $gia_ve_co_ban) {
        $sql = "INSERT INTO suat_chieu (ma_phim, ma_phong, ngay_chieu, gio_bat_dau, gio_ket_thuc, gia_ve_co_ban) 
                VALUES (:ma_phim, :ma_phong, :ngay_chieu, :gio_bat_dau, :gio_ket_thuc, :gia_ve_co_ban)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':ma_phim', $ma_phim);
        $stmt->bindParam(':ma_phong', $ma_phong);
        $stmt->bindParam(':ngay_chieu', $ngay_chieu);
        $stmt->bindParam(':gio_bat_dau', $gio_bat_dau);
        $stmt->bindParam(':gio_ket_thuc', $gio_ket_thuc);
        $stmt->bindParam(':gia_ve_co_ban', $gia_ve_co_ban);

        return $stmt->execute();
    }

    /**
     * Cập nhật suất chiếu
     */
    public function updateSuatChieu($ma_suat_chieu, $ma_phim, $ma_phong, $ngay_chieu, $gio_bat_dau, $gio_ket_thuc, $gia_ve_co_ban) {
        $sql = "UPDATE suat_chieu 
                SET ma_phim = :ma_phim, 
                    ma_phong = :ma_phong, 
                    ngay_chieu = :ngay_chieu, 
                    gio_bat_dau = :gio_bat_dau, 
                    gio_ket_thuc = :gio_ket_thuc, 
                    gia_ve_co_ban = :gia_ve_co_ban
                WHERE ma_suat_chieu = :ma_suat_chieu";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':ma_suat_chieu', $ma_suat_chieu);
        $stmt->bindParam(':ma_phim', $ma_phim);
        $stmt->bindParam(':ma_phong', $ma_phong);
        $stmt->bindParam(':ngay_chieu', $ngay_chieu);
        $stmt->bindParam(':gio_bat_dau', $gio_bat_dau);
        $stmt->bindParam(':gio_ket_thuc', $gio_ket_thuc);
        $stmt->bindParam(':gia_ve_co_ban', $gia_ve_co_ban);

        return $stmt->execute();
    }

    /**
     * Xóa suất chiếu
     */
    public function deleteSuatChieu($ma_suat_chieu) {
        $sql = "DELETE FROM suat_chieu WHERE ma_suat_chieu = :ma_suat_chieu";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':ma_suat_chieu', $ma_suat_chieu);
        return $stmt->execute();
    }

    /**
     * Lấy danh sách phim
     */
    public function getAllPhim() {
        $sql = "SELECT ma_phim, ten_phim FROM phim ORDER BY ten_phim ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy danh sách phòng
     */
    public function getAllPhong() {
        $sql = "SELECT ma_phong, ten_phong FROM phong ORDER BY ten_phong ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } 
}
?>