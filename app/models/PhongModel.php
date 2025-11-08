<?php
// app/models/PhongModel.php

class PhongModel {
    private $conn;

    // ==============================
    // HÀM KHỞI TẠO
    // ==============================
    public function __construct($db) {
        $this->conn = $db;
    }

    // ==============================
    // HÀM: LẤY THÔNG TIN PHÒNG THEO MÃ
    // ==============================
    public function getPhongByMa($ma_phong) {
        $sql = "SELECT * 
                FROM phong
                WHERE ma_phong = :ma_phong";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':ma_phong', $ma_phong);
        $stmt->execute();

        // Trả về 1 dòng duy nhất (vì ma_phong là khóa chính)
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ==============================
    // (Tùy chọn) HÀM: LẤY TẤT CẢ PHÒNG
    // ==============================
    public function getAllPhong() {
        $sql = "SELECT * FROM phong ORDER BY ma_phong ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
