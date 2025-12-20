<?php
// app/models/HoaDonModel.php

class HoaDonModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // ==============================
    // 1. LẤY DANH SÁCH (CÓ LỌC)
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

        // Tìm kiếm (Tên KH hoặc Mã HĐ)
        if (!empty($filters['search'])) {
            $sql .= " AND (kh.ho_ten LIKE :search OR hd.ma_hoa_don LIKE :search)";
            $params[':search'] = "%" . $filters['search'] . "%";
        }

        // Trạng thái
        if (!empty($filters['trang_thai']) && $filters['trang_thai'] !== 'all') {
            $sql .= " AND hd.trang_thai = :trang_thai";
            $params[':trang_thai'] = $filters['trang_thai'];
        }

        // PTTT
        if (!empty($filters['pttt']) && $filters['pttt'] !== 'all') {
            $sql .= " AND hd.phuong_thuc_thanh_toan = :pttt";
            $params[':pttt'] = $filters['pttt'];
        }

        // Ngày (Từ - Đến)
        if (!empty($filters['tu_ngay'])) {
            $sql .= " AND DATE(hd.ngay_tao) >= :tu_ngay";
            $params[':tu_ngay'] = $filters['tu_ngay'];
        }
        if (!empty($filters['den_ngay'])) {
            $sql .= " AND DATE(hd.ngay_tao) <= :den_ngay";
            $params[':den_ngay'] = $filters['den_ngay'];
        }

        $sql .= " ORDER BY hd.ma_hoa_don DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ==============================
    // 2. LẤY 1 HOÁ ĐƠN
    // ==============================
    public function getHoaDonById($id) {
            // JOIN bảng khach_hang để lấy ho_ten, email, SDT
            $sql = "SELECT hd.*, kh.ho_ten, kh.email, kh.SDT 
                    FROM hoa_don hd 
                    LEFT JOIN khach_hang kh ON hd.ma_kh = kh.ma_kh 
                    WHERE hd.ma_hoa_don = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    // ==============================
    // 3. THÊM HOÁ ĐƠN
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
    // 4. CẬP NHẬT HOÁ ĐƠN
    // ==============================
    public function updateHoaDon($data) {
        // Lưu ý: Thường hoá đơn ít khi sửa tiền/khách hàng, chủ yếu sửa trạng thái.
        // Nhưng ở đây mình làm full update.
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
    // 5. XÓA HOÁ ĐƠN
    // ==============================
    public function deleteHoaDon($id) {
        $sql = "DELETE FROM hoa_don WHERE ma_hoa_don = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    public function getVeByHoaDon($ma_hoa_don) {
        $sql = "SELECT 
                    v.ma_ve,
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

    // --- MỚI: Lấy danh sách COMBO của hóa đơn ---
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