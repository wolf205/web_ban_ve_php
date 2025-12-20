<?php
// app/models/KhachHangModel.php

class KhachHangModel {
    private $conn;

    // ==============================
    // HÀM KHỞI TẠO
    // ==============================
    public function __construct($db) {
        $this->conn = $db;
    }

    // ==============================
    // 1. LẤY KHÁCH HÀNG THEO MÃ
    // ==============================
    public function getKhachHangById($ma_kh) {
        $sql = "SELECT ma_kh, avatar, ho_ten, email, SDT, tai_khoan , vai_tro
                FROM khach_hang 
                WHERE ma_kh = :ma_kh";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':ma_kh', $ma_kh);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ==============================
    // 2. KIỂM TRA EMAIL ĐÃ TỒN TẠI HAY CHƯA
    // ==============================
    public function checkEmailExists($email) {
        $sql = "SELECT COUNT(*) AS count FROM khach_hang WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    // ==============================
    // 3. KIỂM TRA TÀI KHOẢN ĐÃ TỒN TẠI HAY CHƯA
    // ==============================
    public function checkTaiKhoanExists($tai_khoan) {
        $sql = "SELECT COUNT(*) AS count FROM khach_hang WHERE tai_khoan = :tai_khoan";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':tai_khoan', $tai_khoan);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    // ==============================
    // 4. HÀM LOGIN
    // ==============================
    public function login($tai_khoan, $mat_khau) {
        $sql = "SELECT ma_kh, ho_ten, email, SDT, vai_tro, tai_khoan, avatar 
                FROM khach_hang 
                WHERE tai_khoan = :tai_khoan AND mat_khau = :mat_khau";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':tai_khoan', $tai_khoan);
        $stmt->bindParam(':mat_khau', $mat_khau);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ==============================
    // 5. CẬP NHẬT THÔNG TIN KHÁCH HÀNG
    // ==============================
    public function updateKhachHang($ma_kh, $ho_ten, $email, $SDT, $avatar) {
        
            $sql = "UPDATE khach_hang 
                    SET ho_ten = :ho_ten, email = :email, SDT = :SDT , avatar = :avatar
                    WHERE ma_kh = :ma_kh";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':avatar', $avatar);
        $stmt->bindParam(':ho_ten', $ho_ten);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':SDT', $SDT);
        $stmt->bindParam(':ma_kh', $ma_kh);

        return $stmt->execute();
    }
    

    // ==============================
    // 6. HÀM REGISTER (ĐĂNG KÝ KHÁCH HÀNG MỚI)
    // ==============================
    public function register( $ho_ten, $email, $SDT, $tai_khoan, $mat_khau) {
        // Kiểm tra trùng email hoặc tài khoản trước khi thêm
        if ($this->checkEmailExists($email) || $this->checkTaiKhoanExists($tai_khoan)) {
            return false; // Không thêm nếu trùng
        }

        $sql = "INSERT INTO khach_hang ( ho_ten, email, SDT, tai_khoan, mat_khau)
                VALUES ( :ho_ten, :email, :SDT, :tai_khoan, :mat_khau)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':ho_ten', $ho_ten);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':SDT', $SDT);
        $stmt->bindParam(':tai_khoan', $tai_khoan);
        $stmt->bindParam(':mat_khau', $mat_khau);
        return $stmt->execute();
    }

    public function getBookingHistory($ma_kh) {
    $sql = "
        SELECT 
            hd.ma_hoa_don AS ma_hd,
            p.ten_phim,
            r.ten_rap,
            sc.ngay_chieu,
            sc.gio_bat_dau,
            sc.gio_ket_thuc,

            -- Ghế đã đặt (từ bảng ve)
            GROUP_CONCAT(DISTINCT g.vi_tri ORDER BY g.vi_tri SEPARATOR ', ') AS ghe_da_dat,

            -- Combo đã mua
            GROUP_CONCAT(DISTINCT CONCAT(cb.ten_combo, ' x', hdc.so_luong) SEPARATOR ', ') AS combo_da_mua,

            hd.ngay_tao

        FROM hoa_don hd

        -- Lấy ghế qua bảng VE
        LEFT JOIN ve v ON v.ma_hoa_don = hd.ma_hoa_don
        LEFT JOIN ghe g ON g.ma_ghe = v.ma_ghe

        -- Combo
        LEFT JOIN hoa_don_combo hdc ON hdc.ma_hoa_don = hd.ma_hoa_don
        LEFT JOIN combo cb ON cb.ma_combo = hdc.ma_combo

        -- Suất chiếu
        LEFT JOIN suat_chieu sc ON sc.ma_suat_chieu = v.ma_suat_chieu
        LEFT JOIN phim p ON p.ma_phim = sc.ma_phim
        LEFT JOIN phong ph ON ph.ma_phong = sc.ma_phong
        LEFT JOIN rap r ON r.ma_rap = ph.ma_rap

        WHERE hd.ma_kh = :ma_kh

        GROUP BY hd.ma_hoa_don

        ORDER BY hd.ngay_tao DESC
    ";

    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':ma_kh', $ma_kh);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
// ==============================
    // HÀM MỚI CHO QUẢN LÝ KHÁCH HÀNG
    // ==============================

    /**
     * Lấy tất cả khách hàng
     */
    public function getAllKhachHang() {
        $sql = "SELECT ma_kh, ho_ten, email, SDT, tai_khoan, vai_tro, avatar 
                FROM khach_hang 
                ORDER BY ma_kh DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lọc khách hàng
     */
    public function filterKhachHang($vai_tro = null, $search = null) {
        $sql = "SELECT ma_kh, ho_ten, email, SDT, tai_khoan, vai_tro, avatar 
                FROM khach_hang 
                WHERE 1=1";
        
        $params = [];
        
        if ($vai_tro && $vai_tro != 'all') {
            $sql .= " AND vai_tro = :vai_tro";
            $params[':vai_tro'] = $vai_tro;
        }
        
        if ($search) {
            $sql .= " AND (ho_ten LIKE :search OR email LIKE :search OR tai_khoan LIKE :search OR SDT LIKE :search)";
            $params[':search'] = "%$search%";
        }
        
        $sql .= " ORDER BY ma_kh DESC";
        
        $stmt = $this->conn->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy danh sách vai trò duy nhất
     */
    public function getDistinctRoles() {
        $sql = "SELECT DISTINCT vai_tro FROM khach_hang WHERE vai_tro IS NOT NULL ORDER BY vai_tro";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    public function addQuanLy($ho_ten, $email, $SDT, $tai_khoan, $mat_khau, $avatar = null) {
        // Kiểm tra trùng email hoặc tài khoản trước khi thêm
        if ($this->checkEmailExists($email) || $this->checkTaiKhoanExists($tai_khoan)) {
            return false; // Không thêm nếu trùng
        }
        
        $sql = "INSERT INTO khach_hang (ho_ten, email, SDT, tai_khoan, mat_khau, vai_tro, avatar)
                VALUES (:ho_ten, :email, :SDT, :tai_khoan, :mat_khau, :vai_tro, :avatar)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':ho_ten', $ho_ten);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':SDT', $SDT);
        $stmt->bindParam(':tai_khoan', $tai_khoan);
        $stmt->bindParam(':mat_khau', $mat_khau);
        $stmt->bindValue(':vai_tro', 'quản lý'); // Vai trò mặc định
        $stmt->bindParam(':avatar', $avatar);
        
        return $stmt->execute();
    }

    /**
     * Cập nhật khách hàng (dành cho admin)
     */
    public function updateKhachHangAdmin($ma_kh, $ho_ten, $email, $SDT, $tai_khoan, $mat_khau = null, $vai_tro = null, $avatar = null) {
        // Kiểm tra email trùng (ngoại trừ chính khách hàng này)
        $checkEmail = $this->conn->prepare("SELECT ma_kh FROM khach_hang WHERE email = :email AND ma_kh != :ma_kh");
        $checkEmail->execute([':email' => $email, ':ma_kh' => $ma_kh]);
        if ($checkEmail->fetch()) {
            return false; // Email đã tồn tại
        }
        
        // Kiểm tra tài khoản trùng
        $checkTaiKhoan = $this->conn->prepare("SELECT ma_kh FROM khach_hang WHERE tai_khoan = :tai_khoan AND ma_kh != :ma_kh");
        $checkTaiKhoan->execute([':tai_khoan' => $tai_khoan, ':ma_kh' => $ma_kh]);
        if ($checkTaiKhoan->fetch()) {
            return false; // Tài khoản đã tồn tại
        }
        
        // Xây dựng câu lệnh SQL động
        $sql = "UPDATE khach_hang SET 
                ho_ten = :ho_ten, 
                email = :email, 
                SDT = :SDT, 
                tai_khoan = :tai_khoan, 
                vai_tro = :vai_tro";
        
        // Thêm mật khẩu nếu có
        if ($mat_khau) {
            $sql .= ", mat_khau = :mat_khau";
        }
        
        // Thêm avatar nếu có
        if ($avatar) {
            $sql .= ", avatar = :avatar";
        }
        
        $sql .= " WHERE ma_kh = :ma_kh";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':ho_ten', $ho_ten);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':SDT', $SDT);
        $stmt->bindParam(':tai_khoan', $tai_khoan);
        $stmt->bindParam(':vai_tro', $vai_tro);
        $stmt->bindParam(':ma_kh', $ma_kh);
        
        if ($mat_khau) {
            $stmt->bindParam(':mat_khau', $mat_khau);
        }
        
        if ($avatar) {
            $stmt->bindParam(':avatar', $avatar);
        }
        
        return $stmt->execute();
    }

    /**
     * Xóa khách hàng
     */
    public function deleteKhachHang($ma_kh) {
        // Kiểm tra xem khách hàng có hóa đơn không
        $check = $this->conn->prepare("SELECT COUNT(*) as count FROM hoa_don WHERE ma_kh = :ma_kh");
        $check->execute([':ma_kh' => $ma_kh]);
        $result = $check->fetch(PDO::FETCH_ASSOC);
        
        if ($result['count'] > 0) {
            return false; // Không thể xóa vì có hóa đơn liên quan
        }
        
        $sql = "DELETE FROM khach_hang WHERE ma_kh = :ma_kh";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':ma_kh', $ma_kh);
        return $stmt->execute();
    }
}

?>
