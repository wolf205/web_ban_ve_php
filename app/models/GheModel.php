<?php
// app/models/GheModel.php

class GheModel {
    private $conn;

    // ==============================
    // HÀM KHỞI TẠO
    // ==============================
    public function __construct($db) {
        $this->conn = $db;
    }

    // ==============================
    // 1. LẤY TẤT CẢ GHẾ THEO PHÒNG
    // ==============================
    public function getAllGheByPhong($ma_phong) {
        $sql = "SELECT ma_ghe, ma_phong, loai_ghe, ma_phong_ghe, vi_tri
                FROM ghe
                WHERE ma_phong = :ma_phong";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':ma_phong', $ma_phong);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ==============================
    // 2. LẤY GHẾ THEO MÃ
    // ==============================
    public function getGheById($ma_ghe) {
        $sql = "SELECT ma_ghe, ma_phong, loai_ghe, ma_phong_ghe, vi_tri
                FROM ghe
                WHERE ma_ghe = :ma_ghe";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':ma_ghe', $ma_ghe);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
