<?php
// app/models/DanhGiaModel.php

class DanhGiaModel {
    private $conn;

    // ==============================
    // HÀM KHỞI TẠO
    // ==============================
    public function __construct($db) {
        $this->conn = $db;
    }

    // ==============================
    // HÀM 1: LẤY CÁC ĐÁNH GIÁ THEO MÃ RẠP
    // ==============================
    public function getDanhGiaByRap($ma_rap) {
        // Thêm kh.avatar vào câu SELECT
        $sql = "SELECT dg.*, kh.ho_ten, kh.avatar 
                FROM danh_gia_rap dg
                JOIN khach_hang kh ON dg.ma_kh = kh.ma_kh
                WHERE dg.ma_rap = :ma_rap
                ORDER BY dg.ngay_danh_gia DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':ma_rap', $ma_rap);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ==============================
    // HÀM 2: THÊM ĐÁNH GIÁ MỚI
    // ==============================
    public function insertDanhGia($ma_rap, $ma_kh, $noi_dung) {
        $sql = "INSERT INTO danh_gia_rap (ma_rap, ma_kh, noi_dung, ngay_danh_gia)
                VALUES (:ma_rap, :ma_kh, :noi_dung, NOW())";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':ma_rap', $ma_rap);
        $stmt->bindParam(':ma_kh', $ma_kh);
        $stmt->bindParam(':noi_dung', $noi_dung);

        return $stmt->execute();
    }
}
?>
