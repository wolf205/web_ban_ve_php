<?php
// app/models/ComboModel.php

class ComboModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // ==============================
    // 1. LẤY DANH SÁCH (CÓ LỌC)
    // ==============================
    public function getAllCombo($filters = []) {
        $sql = "SELECT * FROM combo WHERE 1=1";
        $params = [];

        // Lọc theo tên
        if (!empty($filters['search'])) {
            $sql .= " AND ten_combo LIKE :search";
            $params[':search'] = "%" . $filters['search'] . "%";
        }

        // Lọc theo giá (Từ - Đến)
        if (!empty($filters['min_price'])) {
            $sql .= " AND gia_tien >= :min_price";
            $params[':min_price'] = $filters['min_price'];
        }
        if (!empty($filters['max_price'])) {
            $sql .= " AND gia_tien <= :max_price";
            $params[':max_price'] = $filters['max_price'];
        }

        $sql .= " ORDER BY ma_combo DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ==============================
    // 2. LẤY 1 COMBO THEO ID
    // ==============================
    public function getComboById($ma_combo) {
        $sql = "SELECT * FROM combo WHERE ma_combo = :ma_combo";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':ma_combo', $ma_combo);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ==============================
    // 3. THÊM COMBO MỚI
    // ==============================
    public function addCombo($data) {
        $sql = "INSERT INTO combo (ten_combo, mo_ta, anh_minh_hoa, gia_tien) 
                VALUES (:ten, :mota, :anh, :gia)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':ten' => $data['ten_combo'],
            ':mota' => $data['mo_ta'],
            ':anh' => $data['anh_minh_hoa'],
            ':gia' => $data['gia_tien']
        ]);
    }

    // ==============================
    // 4. CẬP NHẬT COMBO
    // ==============================
    public function updateCombo($data) {
        $sql = "UPDATE combo SET 
                    ten_combo = :ten, 
                    mo_ta = :mota, 
                    gia_tien = :gia";
        
        // Nếu có ảnh mới thì mới update trường ảnh
        if (!empty($data['anh_minh_hoa'])) {
            $sql .= ", anh_minh_hoa = :anh";
        }
        
        $sql .= " WHERE ma_combo = :id";

        $params = [
            ':ten' => $data['ten_combo'],
            ':mota' => $data['mo_ta'],
            ':gia' => $data['gia_tien'],
            ':id' => $data['ma_combo']
        ];

        if (!empty($data['anh_minh_hoa'])) {
            $params[':anh'] = $data['anh_minh_hoa'];
        }

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }

    // ==============================
    // 5. XÓA COMBO
    // ==============================
    public function deleteCombo($id) {
        $sql = "DELETE FROM combo WHERE ma_combo = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>