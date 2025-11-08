<?php
// app/models/RapModel.php

class RapModel {
    private $conn;

    // Hàm khởi tạo, nhận kết nối CSDL
    public function __construct($db) {
        $this->conn = $db;
    }

    // ==============================
    // 1. LẤY TẤT CẢ CÁC RẠP
    // ==============================
    public function getAllRap() {
        $sql = "SELECT ma_rap, ten_rap, dia_chi, thanh_pho, SDT FROM rap";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ==============================
    // 2. LẤY RẠP THEO MÃ
    // ==============================
    public function getRapById($ma_rap) {
        // Thêm mo_ta_rap và anh_rap vào câu SELECT
        $sql = "SELECT ma_rap, ten_rap, dia_chi, thanh_pho, SDT, anh_rap, mo_ta_rap 
                FROM rap 
                WHERE ma_rap = :ma_rap";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':ma_rap', $ma_rap);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
