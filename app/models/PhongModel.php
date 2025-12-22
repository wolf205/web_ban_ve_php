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
    // (Tùy chọn) HÀM: LẤY TẤT CẢ PHÒNG
    // ==============================
    public function getAllPhong() {
        $sql = "SELECT * FROM phong ORDER BY ma_phong ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ==============================
    // HÀM: LẤY THÔNG TIN PHÒNG THEO MÃ
    // ==============================
    public function getPhongByMa($ma_phong) {
        $sql = "SELECT p.*, r.ten_rap 
                FROM phong p
                LEFT JOIN rap r ON p.ma_rap = r.ma_rap
                WHERE p.ma_phong = :ma_phong";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':ma_phong', $ma_phong);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ==============================
    // HÀM: LẤY TẤT CẢ PHÒNG VỚI THÔNG TIN RẠP
    // ==============================
    public function getAllPhongWithRap() {
        $sql = "SELECT p.*, r.ten_rap 
                FROM phong p
                LEFT JOIN rap r ON p.ma_rap = r.ma_rap
                ORDER BY p.ma_phong ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ==============================
    // HÀM: THÊM PHÒNG MỚI
    // ==============================
    public function addPhong($ten_phong, $ma_rap, $loai_man_hinh) {
        $sql = "INSERT INTO phong (ten_phong, ma_rap, loai_man_hinh) 
                VALUES (:ten_phong, :ma_rap, :loai_man_hinh)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':ten_phong', $ten_phong);
        $stmt->bindParam(':ma_rap', $ma_rap);
        $stmt->bindParam(':loai_man_hinh', $loai_man_hinh);

        return $stmt->execute();
    }

    // ==============================
    // HÀM: CẬP NHẬT PHÒNG
    // ==============================
    public function updatePhong($ma_phong, $ten_phong, $ma_rap, $loai_man_hinh) {
        $sql = "UPDATE phong SET 
                ten_phong = :ten_phong,
                ma_rap = :ma_rap,
                loai_man_hinh = :loai_man_hinh
                WHERE ma_phong = :ma_phong";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':ma_phong', $ma_phong);
        $stmt->bindParam(':ten_phong', $ten_phong);
        $stmt->bindParam(':ma_rap', $ma_rap);
        $stmt->bindParam(':loai_man_hinh', $loai_man_hinh);

        return $stmt->execute();
    }

    // ==============================
    // HÀM: XÓA PHÒNG
    // ==============================
    public function deletePhong($ma_phong) {
        try {
            $sql = "DELETE FROM phong WHERE ma_phong = :ma_phong";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':ma_phong', $ma_phong);
            return $stmt->execute();
        } catch (PDOException $e) {
            if ($e->getCode() == '23000') {
                return false;
            }
            throw $e;
        }
    }

    public function countPhongByRapId($ma_rap) {
        $sql = "SELECT COUNT(*) as so_luong 
                FROM phong
                WHERE ma_rap = :ma_rap";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':ma_rap', $ma_rap);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['so_luong'];
    }
    // ==============================
    // HÀM MỚI: LẤY DANH SÁCH RẠP
    // ==============================
    public function getAllRap() {
        $sql = "SELECT ma_rap, ten_rap FROM rap ORDER BY ten_rap ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thêm các hàm này vào cuối class PhongModel, trước dấu đóng ngoặc }

// ==============================
// HÀM: LỌC VÀ TÌM KIẾM PHÒNG
// ==============================
public function filterPhong($ma_rap = null, $search = null, $loai_man_hinh = null) {
    $sql = "SELECT p.*, r.ten_rap 
            FROM phong p
            LEFT JOIN rap r ON p.ma_rap = r.ma_rap
            WHERE 1=1";
    $params = [];
    
    // Lọc theo rạp
    if (!empty($ma_rap) && $ma_rap != 'all') {
        $sql .= " AND p.ma_rap = :ma_rap";
        $params[':ma_rap'] = $ma_rap;
    }
    
    // Lọc theo loại màn hình
    if (!empty($loai_man_hinh) && $loai_man_hinh != 'all') {
        $sql .= " AND p.loai_man_hinh = :loai_man_hinh";
        $params[':loai_man_hinh'] = $loai_man_hinh;
    }
    
    // Tìm kiếm theo tên phòng
    if (!empty($search)) {
        $sql .= " AND (p.ten_phong LIKE :search OR r.ten_rap LIKE :search)";
        $params[':search'] = '%' . $search . '%';
    }
    
    $sql .= " ORDER BY p.ma_phong ASC";
    
    $stmt = $this->conn->prepare($sql);
    
    // Bind các tham số
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ==============================
// HÀM: LẤY DANH SÁCH LOẠI MÀN HÌNH DUY NHẤT
// ==============================
public function getDistinctScreenTypes() {
    $sql = "SELECT DISTINCT loai_man_hinh FROM phong ORDER BY loai_man_hinh ASC";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    
    $types = $stmt->fetchAll(PDO::FETCH_COLUMN);
    return $types ?: [];
}

    // ==============================
    // HÀM: LẤY TẤT CẢ PHÒNG VỚI THÔNG TIN RẠP (CÓ PHÂN TRANG)
    // ==============================
    public function getAllPhongWithRapPhanTrang($limit, $offset) {
        $sql = "SELECT p.*, r.ten_rap 
                FROM phong p
                LEFT JOIN rap r ON p.ma_rap = r.ma_rap
                ORDER BY p.ma_phong ASC
                LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ==============================
    // HÀM: ĐẾM TẤT CẢ PHÒNG
    // ==============================
    public function countAllPhong() {
        $sql = "SELECT COUNT(*) FROM phong";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    // ==============================
    // HÀM: LỌC PHÒNG VỚI PHÂN TRANG
    // ==============================
    public function filterPhongPhanTrang($limit, $offset, $ma_rap = null, $search = null, $loai_man_hinh = null) {
        $sql = "SELECT p.*, r.ten_rap 
                FROM phong p
                LEFT JOIN rap r ON p.ma_rap = r.ma_rap
                WHERE 1=1";
        $params = [];
        
        if (!empty($ma_rap) && $ma_rap != 'all') {
            $sql .= " AND p.ma_rap = :ma_rap";
            $params[':ma_rap'] = $ma_rap;
        }
        
        if (!empty($loai_man_hinh) && $loai_man_hinh != 'all') {
            $sql .= " AND p.loai_man_hinh = :loai_man_hinh";
            $params[':loai_man_hinh'] = $loai_man_hinh;
        }
        
        if (!empty($search)) {
            $sql .= " AND (p.ten_phong LIKE :search OR r.ten_rap LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }
        
        $sql .= " ORDER BY p.ma_phong ASC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($sql);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ==============================
    // HÀM: ĐẾM SỐ LƯỢNG PHÒNG KHI LỌC
    // ==============================
    public function countFilterPhong($ma_rap = null, $search = null, $loai_man_hinh = null) {
        $sql = "SELECT COUNT(*) 
                FROM phong p
                LEFT JOIN rap r ON p.ma_rap = r.ma_rap
                WHERE 1=1";
        $params = [];
        
        if (!empty($ma_rap) && $ma_rap != 'all') {
            $sql .= " AND p.ma_rap = :ma_rap";
            $params[':ma_rap'] = $ma_rap;
        }
        
        if (!empty($loai_man_hinh) && $loai_man_hinh != 'all') {
            $sql .= " AND p.loai_man_hinh = :loai_man_hinh";
            $params[':loai_man_hinh'] = $loai_man_hinh;
        }
        
        if (!empty($search)) {
            $sql .= " AND (p.ten_phong LIKE :search OR r.ten_rap LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }
        
        $stmt = $this->conn->prepare($sql);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        return $stmt->fetchColumn();
    }

}
?>
