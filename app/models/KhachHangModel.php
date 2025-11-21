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
        $sql = "SELECT ma_kh, ho_ten, email, SDT, vai_tro, tai_khoan 
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
}
?>
