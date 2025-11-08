<?php
// app/models/GheSuatChieuModel.php

class GheSuatChieuModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Đếm số ghế trống (trang_thai = 0)
    public function countGheTrong($ma_suat_chieu) {
        $sql = "SELECT COUNT(*) AS so_ghe_trong
                FROM ghe_suat_chieu
                WHERE ma_suat_chieu = :ma_suat_chieu
                AND (trang_thai = 0 OR trang_thai IS NULL)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':ma_suat_chieu', $ma_suat_chieu);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['so_ghe_trong'] ?? 0;
    }

    // Lấy danh sách ghế của suất chiếu
    public function getGheBySuatChieu($ma_suat_chieu) {
        $sql = "SELECT ma_ghe, ma_suat_chieu, trang_thai
                FROM ghe_suat_chieu
                WHERE ma_suat_chieu = :ma_suat_chieu
                ORDER BY ma_ghe ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':ma_suat_chieu', $ma_suat_chieu);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
