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
        $sql = "SELECT ma_ghe, ma_phong, loai_ghe, tinh_trang, vi_tri 
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
        $sql = "SELECT ma_ghe, ma_phong, loai_ghe, tinh_trang, vi_tri
                FROM ghe
                WHERE ma_ghe = :ma_ghe";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':ma_ghe', $ma_ghe);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ==============================
    // 3. THÊM GHẾ MỚI
    // ==============================
    public function addGhe($ma_phong, $vi_tri, $loai_ghe, $trang_thai = 'Hoạt động') {
        $sql = "INSERT INTO ghe (ma_phong, vi_tri, loai_ghe, tinh_trang) 
                VALUES (:ma_phong, :vi_tri, :loai_ghe, :trang_thai)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':ma_phong', $ma_phong);
        $stmt->bindParam(':vi_tri', $vi_tri);
        $stmt->bindParam(':loai_ghe', $loai_ghe);
        $stmt->bindParam(':trang_thai', $trang_thai);

        return $stmt->execute();
    }

    // ==============================
    // 4. CẬP NHẬT GHẾ
    // ==============================
    public function updateGhe($ma_ghe, $vi_tri, $loai_ghe, $trang_thai) {
        $sql = "UPDATE ghe SET 
                vi_tri = :vi_tri,
                loai_ghe = :loai_ghe,
                tinh_trang = :trang_thai
                WHERE ma_ghe = :ma_ghe";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':ma_ghe', $ma_ghe);
        $stmt->bindParam(':vi_tri', $vi_tri);
        $stmt->bindParam(':loai_ghe', $loai_ghe);
        $stmt->bindParam(':trang_thai', $trang_thai);

        return $stmt->execute();
    }

    // ==============================
    // 5. XÓA GHẾ
    // ==============================
    public function deleteGhe($ma_ghe) {
        try {
            $sql = "DELETE FROM ghe WHERE ma_ghe = :ma_ghe";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':ma_ghe', $ma_ghe);
            return $stmt->execute();
        } catch (PDOException $e) {
            if ($e->getCode() == '23000') {
                return false;
            }
            throw $e;
        }
    }

    // ==============================
    // 6. ĐẾM SỐ GHẾ THEO PHÒNG
    // ==============================
    public function countGheByPhong($ma_phong) {
        $sql = "SELECT COUNT(*) as so_luong 
                FROM ghe 
                WHERE ma_phong = :ma_phong";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':ma_phong', $ma_phong);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['so_luong'];
    }
}
?>