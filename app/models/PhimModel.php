<?php
// app/models/PhimModel.php

class PhimModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * LẤY TẤT CẢ CÁC PHIM
     */
    public function getAllPhim() {
        $sql = "SELECT ma_phim, ten_phim, the_loai, thoi_luong, dao_dien, dien_vien, mo_ta, ngay_khoi_chieu, anh_trailer, hot, gioi_han_do_tuoi 
                FROM phim";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * LẤY PHIM THEO MÃ (Đã fix PARAM_INT)
     */
    public function getPhimById($ma_phim) {
        $sql = "SELECT ma_phim, ten_phim, the_loai, thoi_luong, dao_dien, dien_vien, mo_ta, ngay_khoi_chieu, anh_trailer, hot, gioi_han_do_tuoi 
                FROM phim 
                WHERE ma_phim = :ma_phim";
        $stmt = $this->conn->prepare($sql);
        // SỬA LỖI: Chỉ định rõ đây là SỐ (INT)
        $stmt->bindParam(':ma_phim', $ma_phim, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * HÀM ĐÃ SỬA: LẤY CÁC PHIM HOT (theo rạp)
     */
    public function getPhimHot($ma_rap = null) {
        $sql = "SELECT DISTINCT p.* FROM phim p
                JOIN suat_chieu sc ON p.ma_phim = sc.ma_phim
                JOIN phong ph ON sc.ma_phong = ph.ma_phong
                WHERE p.hot = 1";
        
        if ($ma_rap !== null) {
            $sql .= " AND ph.ma_rap = :ma_rap";
        }
        
        $stmt = $this->conn->prepare($sql);
        if ($ma_rap !== null) {
            // SỬA LỖI: Chỉ định rõ đây là SỐ (INT)
            $stmt->bindParam(':ma_rap', $ma_rap, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * HÀM ĐÃ SỬA: LẤY CÁC PHIM ĐANG CHIẾU (theo rạp)
     */
    public function getPhimDangChieu($ma_rap) {
        $sql = "SELECT DISTINCT p.* FROM phim p
                JOIN suat_chieu sc ON p.ma_phim = sc.ma_phim
                JOIN phong ph ON sc.ma_phong = ph.ma_phong
                WHERE p.ngay_khoi_chieu = '2025-10-25'
                  AND ph.ma_rap = :ma_rap";
                  
        $stmt = $this->conn->prepare($sql);
        // SỬA LỖI: Chỉ định rõ đây là SỐ (INT)
        $stmt->bindParam(':ma_rap', $ma_rap, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * HÀM ĐÃ SỬA: LẤY CÁC PHIM SẮP CHIẾU (theo rạp)
     */
    public function getPhimSapChieu($ma_rap) {
        $sql = "SELECT DISTINCT p.* FROM phim p
                JOIN suat_chieu sc ON p.ma_phim = sc.ma_phim
                JOIN phong ph ON sc.ma_phong = ph.ma_phong
                WHERE p.ngay_khoi_chieu >= '2025-10-26'
                  AND ph.ma_rap = :ma_rap";
                  
        $stmt = $this->conn->prepare($sql);
        // SỬA LỖI: Chỉ định rõ đây là SỐ (INT)
        $stmt->bindParam(':ma_rap', $ma_rap, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =================================================================
    // CÁC HÀM DÀNH CHO ADMIN (CRUD PHIM) - THÊM MỚI
    // =================================================================

    /**
     * 1. THÊM PHIM MỚI
     */
    public function createPhim($ten_phim, $the_loai, $thoi_luong, $dao_dien, $dien_vien, $mo_ta, $ngay_khoi_chieu, $gioi_han_do_tuoi, $anh_trailer, $hot) {
        $sql = "INSERT INTO phim (ten_phim, the_loai, thoi_luong, dao_dien, dien_vien, mo_ta, ngay_khoi_chieu, gioi_han_do_tuoi, anh_trailer, hot) 
                VALUES (:ten_phim, :the_loai, :thoi_luong, :dao_dien, :dien_vien, :mo_ta, :ngay_khoi_chieu, :gioi_han_do_tuoi, :anh_trailer, :hot)";
        
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindParam(':ten_phim', $ten_phim);
        $stmt->bindParam(':the_loai', $the_loai);
        $stmt->bindParam(':thoi_luong', $thoi_luong, PDO::PARAM_INT);
        $stmt->bindParam(':dao_dien', $dao_dien);
        $stmt->bindParam(':dien_vien', $dien_vien);
        $stmt->bindParam(':mo_ta', $mo_ta);
        $stmt->bindParam(':ngay_khoi_chieu', $ngay_khoi_chieu);
        $stmt->bindParam(':gioi_han_do_tuoi', $gioi_han_do_tuoi, PDO::PARAM_INT);
        $stmt->bindParam(':anh_trailer', $anh_trailer);
        $stmt->bindParam(':hot', $hot, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    /**
     * 2. CẬP NHẬT THÔNG TIN PHIM
     */
    public function updatePhim($id, $ten_phim, $the_loai, $thoi_luong, $dao_dien, $dien_vien, $mo_ta, $ngay_khoi_chieu, $gioi_han_do_tuoi, $anh_trailer, $hot) {
        $sql = "UPDATE phim 
                SET ten_phim = :ten_phim, 
                    the_loai = :the_loai, 
                    thoi_luong = :thoi_luong, 
                    dao_dien = :dao_dien, 
                    dien_vien = :dien_vien, 
                    mo_ta = :mo_ta, 
                    ngay_khoi_chieu = :ngay_khoi_chieu, 
                    gioi_han_do_tuoi = :gioi_han_do_tuoi, 
                    anh_trailer = :anh_trailer, 
                    hot = :hot 
                WHERE ma_phim = :id";
        
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':ten_phim', $ten_phim);
        $stmt->bindParam(':the_loai', $the_loai);
        $stmt->bindParam(':thoi_luong', $thoi_luong, PDO::PARAM_INT);
        $stmt->bindParam(':dao_dien', $dao_dien);
        $stmt->bindParam(':dien_vien', $dien_vien);
        $stmt->bindParam(':mo_ta', $mo_ta);
        $stmt->bindParam(':ngay_khoi_chieu', $ngay_khoi_chieu);
        $stmt->bindParam(':gioi_han_do_tuoi', $gioi_han_do_tuoi, PDO::PARAM_INT);
        $stmt->bindParam(':anh_trailer', $anh_trailer);
        $stmt->bindParam(':hot', $hot, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    /**
     * 3. XÓA PHIM
     */
    public function deletePhim($id) {
        $sql = "DELETE FROM phim WHERE ma_phim = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * 4. LỌC PHIM THEO TÊN VÀ THỂ LOẠI (Cho chức năng Filter)
     */
    /**
     * ADMIN: Bộ lọc nâng cao (Thể loại + 4 tiêu chí mới)
     */
    public function getPhimWithFilter($filters) {
        $sql = "SELECT * FROM phim WHERE 1=1";
        
        // 1. Lọc Thể loại (LIKE)
        if (!empty($filters['the_loai'])) {
            $sql .= " AND the_loai LIKE :the_loai";
        }

        // 2. Logic Trạng thái
        if (!empty($filters['trang_thai'])) {
            if ($filters['trang_thai'] == 'sap_chieu') {
                $sql .= " AND ngay_khoi_chieu > CURDATE()";
            } elseif ($filters['trang_thai'] == 'dang_chieu') {
                $sql .= " AND ngay_khoi_chieu <= CURDATE()";
            }
        }

        // 3. Logic Độ tuổi
        if (isset($filters['gioi_han_do_tuoi'])) {
            $sql .= " AND gioi_han_do_tuoi = :gioi_han_do_tuoi";
        }

        // 4. Logic Hot
        if (isset($filters['hot'])) {
            $sql .= " AND hot = :hot";
        }

        // 5. Logic Khoảng thời gian
        if (!empty($filters['tu_ngay'])) {
            $sql .= " AND ngay_khoi_chieu >= :tu_ngay";
        }
        if (!empty($filters['den_ngay'])) {
            $sql .= " AND ngay_khoi_chieu <= :den_ngay";
        }
        
        $sql .= " ORDER BY ma_phim DESC";

        $stmt = $this->conn->prepare($sql);

        // Bind tham số
        if (!empty($filters['the_loai'])) {
            $theloai = "%" . $filters['the_loai'] . "%";
            $stmt->bindParam(':the_loai', $theloai);
        }
        if (isset($filters['gioi_han_do_tuoi'])) {
            $stmt->bindParam(':gioi_han_do_tuoi', $filters['gioi_han_do_tuoi'], PDO::PARAM_INT);
        }
        if (isset($filters['hot'])) {
            $stmt->bindParam(':hot', $filters['hot'], PDO::PARAM_INT);
        }
        if (!empty($filters['tu_ngay'])) {
            $stmt->bindParam(':tu_ngay', $filters['tu_ngay']);
        }
        if (!empty($filters['den_ngay'])) {
            $stmt->bindParam(':den_ngay', $filters['den_ngay']);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>