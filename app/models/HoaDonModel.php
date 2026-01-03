<?php
// app/models/HoaDonModel.php

class HoaDonModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // ==============================
    // TẠO HÓA ĐƠN MỚI
    // ==============================
    public function createHoaDon($data) {
        $sql = "INSERT INTO hoa_don (ma_kh, tong_tien, phuong_thuc_thanh_toan, trang_thai, ngay_tao) 
                VALUES (:ma_kh, :tong_tien, :pttt, :trang_thai, NOW())";
        $stmt = $this->conn->prepare($sql);
        $result = $stmt->execute([
            ':ma_kh' => $data['ma_kh'],
            ':tong_tien' => $data['tong_tien'],
            ':pttt' => $data['pttt'],
            ':trang_thai' => $data['trang_thai']
        ]);
        
        if ($result) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // ==============================
    // TẠO VÉ
    // ==============================
    public function createVe($data) {
        $sql = "INSERT INTO ve (ma_suat_chieu, ma_ghe, ma_hoa_don, gia) 
                VALUES (:ma_suat_chieu, :ma_ghe, :ma_hoa_don, :gia)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':ma_suat_chieu' => $data['ma_suat_chieu'],
            ':ma_ghe' => $data['ma_ghe'],
            ':ma_hoa_don' => $data['ma_hoa_don'],
            ':gia' => $data['gia']
        ]);
    }

    // ==============================
    // THÊM COMBO VÀO HÓA ĐƠN
    // ==============================
    public function addComboToHoaDon($data) {
        $sql = "INSERT INTO hoa_don_combo (ma_hoa_don, ma_combo, so_luong) 
                VALUES (:ma_hoa_don, :ma_combo, :so_luong)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':ma_hoa_don' => $data['ma_hoa_don'],
            ':ma_combo' => $data['ma_combo'],
            ':so_luong' => $data['so_luong']
        ]);
    }

    // ==============================
    // CẬP NHẬT TRẠNG THÁI HÓA ĐƠN
    // ==============================
    public function updateTrangThaiHoaDon($ma_hoa_don, $trang_thai) {
        $sql = "UPDATE hoa_don 
                SET trang_thai = :trang_thai 
                WHERE ma_hoa_don = :ma_hoa_don";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':trang_thai' => $trang_thai,
            ':ma_hoa_don' => $ma_hoa_don
        ]);
    }

    // ==============================
    // LẤY DANH SÁCH HÓA ĐƠN (CÓ LỌC)
    // SẮP XẾP TỪ NHỎ ĐẾN LỚN (ASC)
    // ==============================
    public function getAllHoaDon($filters = []) {
        $sql = "SELECT 
                    hd.ma_hoa_don,
                    kh.ho_ten,
                    hd.ngay_tao,
                    hd.tong_tien,
                    hd.phuong_thuc_thanh_toan,
                    hd.trang_thai
                FROM hoa_don hd
                LEFT JOIN khach_hang kh ON hd.ma_kh = kh.ma_kh
                WHERE 1=1";
        
        $params = [];

        if (!empty($filters['search'])) {
            $sql .= " AND (kh.ho_ten LIKE :search OR hd.ma_hoa_don LIKE :search)";
            $params[':search'] = "%" . $filters['search'] . "%";
        }

        if (!empty($filters['trang_thai']) && $filters['trang_thai'] !== 'all') {
            $sql .= " AND hd.trang_thai = :trang_thai";
            $params[':trang_thai'] = $filters['trang_thai'];
        }

        if (!empty($filters['pttt']) && $filters['pttt'] !== 'all') {
            $sql .= " AND hd.phuong_thuc_thanh_toan = :pttt";
            $params[':pttt'] = $filters['pttt'];
        }

        if (!empty($filters['tu_ngay'])) {
            $sql .= " AND DATE(hd.ngay_tao) >= :tu_ngay";
            $params[':tu_ngay'] = $filters['tu_ngay'];
        }
        if (!empty($filters['den_ngay'])) {
            $sql .= " AND DATE(hd.ngay_tao) <= :den_ngay";
            $params[':den_ngay'] = $filters['den_ngay'];
        }

        // Sắp xếp từ nhỏ đến lớn (ASC)
        $sql .= " ORDER BY hd.ma_hoa_don ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ==============================
    // LẤY 1 HÓA ĐƠN
    // ==============================
    public function getHoaDonById($id) {
        $sql = "SELECT hd.*, kh.ho_ten, kh.email, kh.SDT 
                FROM hoa_don hd 
                LEFT JOIN khach_hang kh ON hd.ma_kh = kh.ma_kh 
                WHERE hd.ma_hoa_don = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ==============================
    // THÊM HÓA ĐƠN (CHO ADMIN)
    // ==============================
    public function addHoaDon($data) {
        $sql = "INSERT INTO hoa_don (ma_kh, tong_tien, phuong_thuc_thanh_toan, trang_thai, ngay_tao) 
                VALUES (:ma_kh, :tong_tien, :pttt, :trang_thai, NOW())";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':ma_kh' => $data['ma_kh'],
            ':tong_tien' => $data['tong_tien'],
            ':pttt' => $data['pttt'],
            ':trang_thai' => $data['trang_thai']
        ]);
    }

    // ==============================
    // CẬP NHẬT HÓA ĐƠN
    // ==============================
    public function updateHoaDon($data) {
        $sql = "UPDATE hoa_don SET 
                    ma_kh = :ma_kh,
                    tong_tien = :tong_tien,
                    phuong_thuc_thanh_toan = :pttt,
                    trang_thai = :trang_thai
                WHERE ma_hoa_don = :id";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':ma_kh' => $data['ma_kh'],
            ':tong_tien' => $data['tong_tien'],
            ':pttt' => $data['pttt'],
            ':trang_thai' => $data['trang_thai'],
            ':id' => $data['ma_hoa_don']
        ]);
    }

    // ==============================
    // XÓA HÓA ĐƠN (CÓ XỬ LÝ CASCADE)
    // ==============================
    public function deleteHoaDon($id) {
        try {
            // Bắt đầu transaction
            $this->conn->beginTransaction();

            // 1. Xóa combo liên quan
            $sql1 = "DELETE FROM hoa_don_combo WHERE ma_hoa_don = :id";
            $stmt1 = $this->conn->prepare($sql1);
            $stmt1->execute([':id' => $id]);

            // 2. Xóa vé liên quan
            $sql2 = "DELETE FROM ve WHERE ma_hoa_don = :id";
            $stmt2 = $this->conn->prepare($sql2);
            $stmt2->execute([':id' => $id]);

            // 3. Xóa hóa đơn
            $sql3 = "DELETE FROM hoa_don WHERE ma_hoa_don = :id";
            $stmt3 = $this->conn->prepare($sql3);
            $result = $stmt3->execute([':id' => $id]);

            // Commit transaction
            $this->conn->commit();
            
            return $result;
            
        } catch (PDOException $e) {
            // Rollback nếu có lỗi
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            
            // Log lỗi
            error_log("Lỗi xóa hóa đơn: " . $e->getMessage());
            return false;
        }
    }

// ==============================
    // LẤY VÉ THEO HÓA ĐƠN (ĐÃ SỬA - BỔ SUNG ma_suat_chieu và ma_ghe)
    // ==============================
    public function getVeByHoaDon($ma_hoa_don) {
        $sql = "SELECT 
                    v.ma_ve,
                    v.ma_suat_chieu,
                    v.ma_ghe,
                    v.gia AS gia_ve,
                    g.vi_tri,
                    g.loai_ghe,
                    p.ten_phim,
                    sc.gio_bat_dau,
                    ph.ten_phong
                FROM ve v
                JOIN ghe g ON v.ma_ghe = g.ma_ghe
                JOIN suat_chieu sc ON v.ma_suat_chieu = sc.ma_suat_chieu
                JOIN phim p ON sc.ma_phim = p.ma_phim
                JOIN phong ph ON sc.ma_phong = ph.ma_phong
                WHERE v.ma_hoa_don = :ma_hoa_don";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':ma_hoa_don' => $ma_hoa_don]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ==============================
    // LẤY COMBO THEO HÓA ĐƠN
    // ==============================
    public function getComboByHoaDon($ma_hoa_don) {
        $sql = "SELECT 
                    c.ten_combo,
                    hdc.so_luong,
                    c.gia_tien AS gia_combo,
                    (hdc.so_luong * c.gia_tien) AS thanh_tien
                FROM hoa_don_combo hdc
                JOIN combo c ON hdc.ma_combo = c.ma_combo
                WHERE hdc.ma_hoa_don = :ma_hoa_don";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':ma_hoa_don' => $ma_hoa_don]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>