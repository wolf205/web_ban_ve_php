<?php
// app/models/VeModel.php

class VeModel {
    private $conn;

    // ==============================
    // HÀM KHỞI TẠO
    // ==============================
    public function __construct($db) {
        $this->conn = $db;
    }

    // ==============================
    // HÀM: LẤY DANH SÁCH VÉ THEO MÃ HÓA ĐƠN
    // ==============================
    public function getVeByHoaDon($ma_hoa_don) {
        $sql = "SELECT * 
                FROM ve
                WHERE ma_hoa_don = :ma_hoa_don
                ORDER BY ma_ve ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':ma_hoa_don', $ma_hoa_don);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
