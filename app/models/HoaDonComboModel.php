<?php
// app/models/HoaDonComboModel.php

class HoaDonComboModel {
    private $conn;

    // ==============================
    // HÀM KHỞI TẠO
    // ==============================
    public function __construct($db) {
        $this->conn = $db;
    }

    // ==============================
    // HÀM: LẤY SỐ LƯỢNG COMBO THEO MÃ HÓA ĐƠN VÀ MÃ COMBO
    // ==============================
    public function getSoLuong($ma_hoa_don, $ma_combo) {
        $sql = "SELECT so_luong
                FROM hoa_don_combo
                WHERE ma_hoa_don = :ma_hoa_don
                  AND ma_combo = :ma_combo";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':ma_hoa_don', $ma_hoa_don);
        $stmt->bindParam(':ma_combo', $ma_combo);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['so_luong'] ?? 0; // Nếu không có dữ liệu thì trả về 0
    }

    // ==============================
    // (Tùy chọn) HÀM: LẤY TẤT CẢ COMBO CỦA MỘT HÓA ĐƠN
    // ==============================
    public function getComboByHoaDon($ma_hoa_don) {
        $sql = "SELECT ma_combo, so_luong
                FROM hoa_don_combo
                WHERE ma_hoa_don = :ma_hoa_don";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':ma_hoa_don', $ma_hoa_don);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
