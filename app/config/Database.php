<?php
// app/config/Database.php

class Database {
    private $host = "localhost";
    private $db_name = "web_ban_ve"; // tên database trong phpMyAdmin
    private $username = "root";              // tài khoản mặc định của XAMPP
    private $password = "";                  // XAMPP thường để trống mật khẩu
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            // Chuỗi DSN cho MySQL
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->db_name;charset=utf8", 
                                   $this->username, 
                                   $this->password);

            // Thiết lập chế độ báo lỗi
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Lỗi kết nối: " . $e->getMessage();
        }

        return $this->conn;
    }
}
?>
