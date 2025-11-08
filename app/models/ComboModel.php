<?php
// app/models/ComboModel.php

class ComboModel {
    private $conn;

    // ==============================
    // HÀM KHỞI TẠO
    // ==============================
    public function __construct($db) {
        $this->conn = $db;
    }

    // ==============================
    // HÀM: LẤY TẤT CẢ CÁC COMBO
    // ==============================
    public function getAllCombo() {
        $sql = "SELECT * FROM combo ORDER BY gia_tien ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // (Tùy chọn) Nếu muốn lấy combo theo mã cụ thể:
    public function getComboById($ma_combo) {
        $sql = "SELECT * FROM combo WHERE ma_combo = :ma_combo";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':ma_combo', $ma_combo);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
