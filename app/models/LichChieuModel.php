<?php
// app/models/LichChieuModel.php

class LichChieuModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // =================================================================
    // 1. KHU VỰC DÀNH CHO TRANG KHÁCH HÀNG (PUBLIC)
    // =================================================================

    /**
     * LẤY DANH SÁCH SUẤT CHIẾU ĐỂ HIỂN THỊ TRANG BÁN VÉ
     * - Dùng cho trang khách hàng chọn suất chiếu
     * - Có thể lọc theo ngày chiếu và rạp
     * - Trả về thông tin cơ bản: mã suất chiếu, thông tin phim, phòng, thời gian
     */
    public function getLichChieu($ngay_chieu = null, $ma_rap = null) {
        $sql = "SELECT sc.ma_suat_chieu, sc.ma_phim, p.ten_phim, sc.ma_phong, 
                       sc.ngay_chieu, sc.gio_bat_dau, sc.gio_ket_thuc,
                       ph.ma_rap
                FROM suat_chieu sc
                JOIN phim p ON sc.ma_phim = p.ma_phim
                JOIN phong ph ON sc.ma_phong = ph.ma_phong
                WHERE 1=1"; // Mệnh đề WHERE 1=1 để dễ dàng thêm điều kiện

        // THÊM ĐIỀU KIỆN LỌC THEO NGÀY CHIẾU (NẾU CÓ)
        if (!empty($ngay_chieu)) {
            $sql .= " AND sc.ngay_chieu = :ngay_chieu";
        }
        
        // THÊM ĐIỀU KIỆN LỌC THEO RẠP (NẾU CÓ)
        if (!empty($ma_rap)) {
            $sql .= " AND ph.ma_rap = :ma_rap";
        }

        // SẮP XẾP THEO NGÀY VÀ GIỜ CHIẾU
        $sql .= " ORDER BY sc.ngay_chieu ASC, sc.gio_bat_dau ASC";

        $stmt = $this->conn->prepare($sql);

        // BIND PARAM CHO CÁC ĐIỀU KIỆN LỌC
        if (!empty($ngay_chieu)) {
            $stmt->bindParam(':ngay_chieu', $ngay_chieu);
        }
        
        if (!empty($ma_rap)) {
            $stmt->bindParam(':ma_rap', $ma_rap);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * LẤY SUẤT CHIẾU THEO MÃ PHIM VÀ MÃ RẠP
     * - Dùng cho trang chi tiết phim, hiển thị lịch chiếu của phim tại một rạp cụ thể
     * - Kết hợp cả 2 điều kiện: phim và rạp
     */
    public function getLichChieuByPhimId($ma_phim, $ma_rap) {
        
        $sql = "SELECT sc.ma_suat_chieu, sc.ma_phim, p.ten_phim, sc.ma_phong, 
                       sc.ngay_chieu, sc.gio_bat_dau, sc.gio_ket_thuc,
                       ph.ma_rap
                FROM suat_chieu sc
                JOIN phim p ON sc.ma_phim = p.ma_phim
                JOIN phong ph ON sc.ma_phong = ph.ma_phong
                WHERE sc.ma_phim = :ma_phim
                  AND ph.ma_rap = :ma_rap"; // LỌC THEO CẢ PHIM VÀ RẠP

        $sql .= " ORDER BY sc.ngay_chieu ASC, sc.gio_bat_dau ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':ma_phim', $ma_phim);
        $stmt->bindParam(':ma_rap', $ma_rap); // BIND PARAM CHO MÃ RẠP
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =================================================================
    // 2. KHU VỰC DÀNH CHO TRANG QUẢN TRỊ (ADMIN)
    // =================================================================

    /**
     * LẤY TẤT CẢ SUẤT CHIẾU VỚI THÔNG TIN ĐẦY ĐỦ
     * - Dùng cho admin xem toàn bộ suất chiếu
     * - JOIN nhiều bảng để lấy thông tin đầy đủ: phim, phòng, rạp
     * - Sắp xếp theo ngày mới nhất và giờ sớm nhất
     */
    public function getAllSuatChieu() {
        $sql = "SELECT 
                    sc.ma_suat_chieu,
                    sc.ma_phong,
                    p.ten_phim,
                    r.ten_rap,
                    ph.ten_phong,
                    sc.ngay_chieu,
                    sc.gio_bat_dau,
                    sc.gio_ket_thuc,
                    sc.gia_ve_co_ban as gia_ve
                FROM suat_chieu sc
                JOIN phim p ON sc.ma_phim = p.ma_phim
                JOIN phong ph ON sc.ma_phong = ph.ma_phong
                JOIN rap r ON ph.ma_rap = r.ma_rap
                ORDER BY sc.ngay_chieu DESC, sc.gio_bat_dau ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * LẤY THÔNG TIN SUẤT CHIẾU THEO ID
     * - Dùng cho admin chỉnh sửa suất chiếu
     * - Chỉ lấy thông tin từ bảng suat_chieu
     */
    public function getSuatChieuById($ma_suat_chieu) {
        $sql = "SELECT * FROM suat_chieu WHERE ma_suat_chieu = :ma_suat_chieu";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':ma_suat_chieu', $ma_suat_chieu);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * THÊM SUẤT CHIẾU MỚI
     * - Dùng cho admin tạo suất chiếu mới
     * - Validate dữ liệu đầu vào
     * - Trả về boolean để controller biết thành công/thất bại
     */
    public function addSuatChieu($ma_phim, $ma_phong, $ngay_chieu, $gio_bat_dau, $gio_ket_thuc, $gia_ve_co_ban) {
        $sql = "INSERT INTO suat_chieu (ma_phim, ma_phong, ngay_chieu, gio_bat_dau, gio_ket_thuc, gia_ve_co_ban) 
                VALUES (:ma_phim, :ma_phong, :ngay_chieu, :gio_bat_dau, :gio_ket_thuc, :gia_ve_co_ban)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':ma_phim', $ma_phim);
        $stmt->bindParam(':ma_phong', $ma_phong);
        $stmt->bindParam(':ngay_chieu', $ngay_chieu);
        $stmt->bindParam(':gio_bat_dau', $gio_bat_dau);
        $stmt->bindParam(':gio_ket_thuc', $gio_ket_thuc);
        $stmt->bindParam(':gia_ve_co_ban', $gia_ve_co_ban);

        return $stmt->execute(); // TRẢ VỀ TRUE/FALSE
    }

    /**
     * CẬP NHẬT SUẤT CHIẾU
     * - Dùng cho admin chỉnh sửa suất chiếu
     * - Cập nhật tất cả các trường
     */
    public function updateSuatChieu($ma_suat_chieu, $ma_phim, $ma_phong, $ngay_chieu, $gio_bat_dau, $gio_ket_thuc, $gia_ve_co_ban) {
        $sql = "UPDATE suat_chieu 
                SET ma_phim = :ma_phim, 
                    ma_phong = :ma_phong, 
                    ngay_chieu = :ngay_chieu, 
                    gio_bat_dau = :gio_bat_dau, 
                    gio_ket_thuc = :gio_ket_thuc, 
                    gia_ve_co_ban = :gia_ve_co_ban
                WHERE ma_suat_chieu = :ma_suat_chieu";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':ma_suat_chieu', $ma_suat_chieu);
        $stmt->bindParam(':ma_phim', $ma_phim);
        $stmt->bindParam(':ma_phong', $ma_phong);
        $stmt->bindParam(':ngay_chieu', $ngay_chieu);
        $stmt->bindParam(':gio_bat_dau', $gio_bat_dau);
        $stmt->bindParam(':gio_ket_thuc', $gio_ket_thuc);
        $stmt->bindParam(':gia_ve_co_ban', $gia_ve_co_ban);

        return $stmt->execute(); // TRẢ VỀ TRUE/FALSE
    }

    /**
     * XÓA SUẤT CHIẾU
     * - Dùng cho admin xóa suất chiếu
     * - Kiểm tra ràng buộc khóa ngoại trước khi xóa (nếu cần)
     */
    public function deleteSuatChieu($ma_suat_chieu) {
        $sql = "DELETE FROM suat_chieu WHERE ma_suat_chieu = :ma_suat_chieu";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':ma_suat_chieu', $ma_suat_chieu);
        return $stmt->execute(); // TRẢ VỀ TRUE/FALSE
    }

    /**
     * LẤY TẤT CẢ PHÒNG
     * - Dùng để populate dropdown chọn phòng trong form
     * - Sắp xếp theo mã phòng
     */
    public function getAllPhong() {
        $sql = "SELECT * FROM phong ORDER BY ma_phong ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * LẤY TẤT CẢ PHIM
     * - Dùng để populate dropdown chọn phim trong form
     * - Chỉ lấy các trường cần thiết
     */
    public function getAllPhim() {
        $sql = "SELECT ma_phim, ten_phim, the_loai, thoi_luong, dao_dien, dien_vien, mo_ta, ngay_khoi_chieu, anh_trailer, hot, gioi_han_do_tuoi 
                FROM phim";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * LẤY SUẤT CHIẾU VỚI BỘ LỌC (KHÔNG PHÂN TRANG)
     * - Dùng cho admin xem suất chiếu với bộ lọc
     * - Hỗ trợ lọc theo ngày, rạp, phim
     * - Sử dụng LIKE cho tìm kiếm text
     */
    public function getSuatChieuWithFilter($filters = []) {
        $sql = "SELECT 
                    sc.ma_suat_chieu,
                    sc.ma_phong,
                    p.ten_phim,
                    r.ten_rap,
                    ph.ten_phong,
                    sc.ngay_chieu,
                    sc.gio_bat_dau,
                    sc.gio_ket_thuc,
                    sc.gia_ve_co_ban as gia_ve
                FROM suat_chieu sc
                JOIN phim p ON sc.ma_phim = p.ma_phim
                JOIN phong ph ON sc.ma_phong = ph.ma_phong
                JOIN rap r ON ph.ma_rap = r.ma_rap
                WHERE 1=1";

        $params = [];

        // XỬ LÝ CÁC ĐIỀU KIỆN LỌC
        if (!empty($filters['ngay_chieu'])) {
            $sql .= " AND sc.ngay_chieu = :ngay_chieu";
            $params[':ngay_chieu'] = $filters['ngay_chieu'];
        }

        if (!empty($filters['ten_rap'])) {
            $sql .= " AND r.ten_rap LIKE :ten_rap";
            $params[':ten_rap'] = '%' . $filters['ten_rap'] . '%'; // TÌM KIẾM GẦN ĐÚNG
        }

        if (!empty($filters['ten_phim'])) {
            $sql .= " AND p.ten_phim LIKE :ten_phim";
            $params[':ten_phim'] = '%' . $filters['ten_phim'] . '%'; // TÌM KIẾM GẦN ĐÚNG
        }

        $sql .= " ORDER BY sc.ngay_chieu DESC, sc.gio_bat_dau ASC";

        $stmt = $this->conn->prepare($sql);
        
        // BIND CÁC THAM SỐ
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * LẤY DANH SÁCH RẠP DUY NHẤT
     * - Dùng để populate dropdown lọc rạp
     * - DISTINCT để chỉ lấy tên rạp không trùng
     */
    public function getAllRap() {
        $sql = "SELECT DISTINCT ten_rap FROM rap ORDER BY ten_rap ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =================================================================
    // 3. HÀM PHÂN TRANG (CHO ADMIN)
    // =================================================================

    /**
     * LẤY SUẤT CHIẾU VỚI PHÂN TRANG VÀ BỘ LỌC
     * - Dùng cho admin với phân trang
     * - Giới hạn số lượng kết quả trả về (LIMIT)
     * - Bỏ qua một số kết quả (OFFSET)
     */
    public function getSuatChieuPhanTrang($filters = [], $limit = 3, $offset = 0) {
        $sql = "SELECT 
                    sc.ma_suat_chieu,
                    sc.ma_phong,
                    p.ten_phim,
                    r.ten_rap,
                    ph.ten_phong,
                    sc.ngay_chieu,
                    sc.gio_bat_dau,
                    sc.gio_ket_thuc,
                    sc.gia_ve_co_ban as gia_ve
                FROM suat_chieu sc
                JOIN phim p ON sc.ma_phim = p.ma_phim
                JOIN phong ph ON sc.ma_phong = ph.ma_phong
                JOIN rap r ON ph.ma_rap = r.ma_rap
                WHERE 1=1";

        $params = [];

        // XỬ LÝ CÁC ĐIỀU KIỆN LỌC
        if (!empty($filters['ngay_chieu'])) {
            $sql .= " AND sc.ngay_chieu = :ngay_chieu";
            $params[':ngay_chieu'] = $filters['ngay_chieu'];
        }

        if (!empty($filters['ten_rap'])) {
            $sql .= " AND r.ten_rap LIKE :ten_rap";
            $params[':ten_rap'] = '%' . $filters['ten_rap'] . '%';
        }

        if (!empty($filters['ten_phim'])) {
            $sql .= " AND p.ten_phim LIKE :ten_phim";
            $params[':ten_phim'] = '%' . $filters['ten_phim'] . '%';
        }

        $sql .= " ORDER BY sc.ngay_chieu DESC, sc.gio_bat_dau ASC";
        $sql .= " LIMIT :limit OFFSET :offset"; // THÊM PHÂN TRANG

        $stmt = $this->conn->prepare($sql);
        
        // BIND CÁC THAM SỐ LỌC
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        // BIND THAM SỐ PHÂN TRANG (ÉP KIỂU INT)
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * ĐẾM TỔNG SỐ SUẤT CHIẾU (CHO PHÂN TRANG)
     * - Đếm tổng số bản ghi phù hợp với bộ lọc
     * - Dùng để tính toán số lượng trang
     */
    public function countSuatChieu($filters = []) {
        $sql = "SELECT COUNT(*) 
                FROM suat_chieu sc
                JOIN phim p ON sc.ma_phim = p.ma_phim
                JOIN phong ph ON sc.ma_phong = ph.ma_phong
                JOIN rap r ON ph.ma_rap = r.ma_rap
                WHERE 1=1";

        $params = [];

        // XỬ LÝ CÁC ĐIỀU KIỆN LỌC (GIỐNG NHƯ TRÊN)
        if (!empty($filters['ngay_chieu'])) {
            $sql .= " AND sc.ngay_chieu = :ngay_chieu";
            $params[':ngay_chieu'] = $filters['ngay_chieu'];
        }

        if (!empty($filters['ten_rap'])) {
            $sql .= " AND r.ten_rap LIKE :ten_rap";
            $params[':ten_rap'] = '%' . $filters['ten_rap'] . '%';
        }

        if (!empty($filters['ten_phim'])) {
            $sql .= " AND p.ten_phim LIKE :ten_phim";
            $params[':ten_phim'] = '%' . $filters['ten_phim'] . '%';
        }

        $stmt = $this->conn->prepare($sql);
        
        // BIND CÁC THAM SỐ
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        return $stmt->fetchColumn(); // LẤY GIÁ TRỊ ĐẾM ĐẦU TIÊN
    }
}
?>