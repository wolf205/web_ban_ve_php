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
        $sql = "SELECT ma_rap, ten_rap, dia_chi, thanh_pho, SDT, anh_rap, mo_ta_rap FROM rap";
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

    // ==============================
    // 3. (MỚI) THÊM RẠP MỚI
    // ==============================
    public function addRap($ten, $dia_chi, $thanh_pho, $sdt, $anh_rap_path, $mo_ta) {
        $sql = "INSERT INTO rap (ten_rap, dia_chi, thanh_pho, SDT, anh_rap, mo_ta_rap) 
                VALUES (:ten, :dia_chi, :thanh_pho, :sdt, :anh_rap, :mo_ta)";
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindParam(':ten', $ten);
        $stmt->bindParam(':dia_chi', $dia_chi);
        $stmt->bindParam(':thanh_pho', $thanh_pho);
        $stmt->bindParam(':sdt', $sdt);
        $stmt->bindParam(':anh_rap', $anh_rap_path);
        $stmt->bindParam(':mo_ta', $mo_ta);

        return $stmt->execute();
    }

    // ==============================
    // 4. (MỚI) CẬP NHẬT RẠP
    // ==============================
    public function updateRap($ma_rap, $ten, $dia_chi, $thanh_pho, $sdt, $anh_rap_path, $mo_ta) {
        // Nếu không có ảnh mới, chỉ cập nhật thông tin
        if (empty($anh_rap_path)) {
            $sql = "UPDATE rap SET 
                        ten_rap = :ten, 
                        dia_chi = :dia_chi, 
                        thanh_pho = :thanh_pho, 
                        SDT = :sdt, 
                        mo_ta_rap = :mo_ta
                    WHERE ma_rap = :ma_rap";
        } else {
            // Nếu có ảnh mới, cập nhật cả đường dẫn ảnh
            $sql = "UPDATE rap SET 
                        ten_rap = :ten, 
                        dia_chi = :dia_chi, 
                        thanh_pho = :thanh_pho, 
                        SDT = :sdt, 
                        anh_rap = :anh_rap,
                        mo_ta_rap = :mo_ta
                    WHERE ma_rap = :ma_rap";
        }
        
        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':ma_rap', $ma_rap);
        $stmt->bindParam(':ten', $ten);
        $stmt->bindParam(':dia_chi', $dia_chi);
        $stmt->bindParam(':thanh_pho', $thanh_pho);
        $stmt->bindParam(':sdt', $sdt);
        $stmt->bindParam(':mo_ta', $mo_ta);
        
        // Chỉ bind anh_rap nếu có ảnh mới
        if (!empty($anh_rap_path)) {
            $stmt->bindParam(':anh_rap', $anh_rap_path);
        }
        
        return $stmt->execute();
    }

    // ==============================
    // 5. (MỚI) XÓA RẠP
    // ==============================
    public function deleteRap($ma_rap) {
        try {
            $sql = "DELETE FROM rap WHERE ma_rap = :ma_rap";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':ma_rap', $ma_rap);
            return $stmt->execute();
        } catch (PDOException $e) {
            // Bắt lỗi khóa ngoại
            if ($e->getCode() == '23000') { 
                return false; // Hoặc ném ra thông báo lỗi cụ thể
            }
            throw $e;
        }
    }

    // ==============================
// 6. LỌC VÀ TÌM KIẾM RẠP
// ==============================
public function filterRap($thanh_pho = null, $search = null) {
    $sql = "SELECT ma_rap, ten_rap, dia_chi, thanh_pho, SDT, anh_rap, mo_ta_rap FROM rap WHERE 1=1";
    $params = [];
    
    // Lọc theo thành phố
    if (!empty($thanh_pho) && $thanh_pho != 'all') {
        $sql .= " AND thanh_pho = :thanh_pho";
        $params[':thanh_pho'] = $thanh_pho;
    }
    
    // Tìm kiếm theo tên
    if (!empty($search)) {
        $sql .= " AND (ten_rap LIKE :search OR dia_chi LIKE :search)";
        $params[':search'] = '%' . $search . '%';
    }
    
    $sql .= " ORDER BY ma_rap ASC";
    
    $stmt = $this->conn->prepare($sql);
    
    // Bind các tham số
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ==============================
// 7. LẤY DANH SÁCH THÀNH PHỐ DUY NHẤT
// ==============================
public function getDistinctCities() {
    $sql = "SELECT DISTINCT thanh_pho FROM rap ORDER BY thanh_pho ASC";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    
    $cities = $stmt->fetchAll(PDO::FETCH_COLUMN);
    return $cities ?: [];
}
}
?>
