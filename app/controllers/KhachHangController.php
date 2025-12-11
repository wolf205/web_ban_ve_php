<?php
// app/controllers/KhachHangController.php
session_start();

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/KhachHangModel.php';

class KhachHangController {
    private $db;
    private $khachHangModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        if ($this->db === null) throw new \Exception("Không thể kết nối đến CSDL.");

        $this->khachHangModel = new KhachHangModel($this->db);
    }

    /**
     * Hiển thị trang đăng nhập
     */
     public function index() {
    // Kiểm tra nếu đã đăng nhập, chuyển hướng theo vai trò
    if (isset($_SESSION['khach_hang'])) {
        $this->redirectByRole($_SESSION['khach_hang']['vai_tro']);
        exit;
    }

    // Xử lý khi form được submit
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $tai_khoan = $_POST['tai_khoan'] ?? '';
            $mat_khau = $_POST['mat_khau'] ?? '';

            // Kiểm tra thông tin đăng nhập
            $khach_hang = $this->khachHangModel->login($tai_khoan, $mat_khau);

            if ($khach_hang) {
                // Đăng nhập thành công - lưu thông tin vào session
                $_SESSION['khach_hang'] = [
                    'ma_kh' => $khach_hang['ma_kh'],
                    'ho_ten' => $khach_hang['ho_ten'],
                    'email' => $khach_hang['email'],
                    'SDT' => $khach_hang['SDT'],
                    'tai_khoan' => $khach_hang['tai_khoan'],
                    'vai_tro' => $khach_hang['vai_tro']
                ];

                // Chuyển hướng theo vai trò sau khi đăng nhập thành công
                $this->redirectByRole($khach_hang['vai_tro']);
                exit;
            } else {
                throw new Exception("Tài khoản hoặc mật khẩu không đúng!");
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
            require_once __DIR__ . '/../views/khach_hang/login_view.php';
        }
    } else {
        // Hiển thị form đăng nhập
        require_once __DIR__ . '/../views/khach_hang/login_view.php';
    }
}

/**
 * Chuyển hướng theo vai trò người dùng
 * @param string $vai_tro Vai trò của người dùng
 */
private function redirectByRole($vai_tro) {
    if ($vai_tro === 'quản lý') {
        header('Location: index.php?controller=adminrap');
    } else {
        header('Location: index.php?controller=trangchu');
    }
}
/**
     * Hiển thị trang đăng ký
     */
    public function register() {
        // Xử lý khi form được submit
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $ho_ten = $_POST['ho_ten'] ?? '';
                $email = $_POST['email'] ?? '';
                $sdt = $_POST['sdt'] ?? '';
                $tai_khoan = $_POST['tai_khoan'] ?? '';
                $mat_khau = $_POST['mat_khau'] ?? '';
                $xac_nhan_mat_khau = $_POST['xac_nhan_mat_khau'] ?? '';

                // Kiểm tra mật khẩu xác nhận
                if ($mat_khau !== $xac_nhan_mat_khau) {
                    throw new Exception("Mật khẩu xác nhận không khớp!");
                }

                // Kiểm tra email đã tồn tại
                if ($this->khachHangModel->checkEmailExists($email)) {
                    throw new Exception("Email đã được sử dụng!");
                }

                // Kiểm tra tài khoản đã tồn tại
                if ($this->khachHangModel->checkTaiKhoanExists($tai_khoan)) {
                    throw new Exception("Tài khoản đã tồn tại!");
                }

                

                // Đăng ký khách hàng mới
                $success = $this->khachHangModel->register( $ho_ten, $email, $sdt, $tai_khoan, $mat_khau);

                if ($success) {
                    // Đăng ký thành công, chuyển hướng đến trang đăng nhập
                    header('Location: index.php?controller=KhachHang&action=index&status=register_success');
                    exit;
                } else {
                    throw new Exception("Đăng ký thất bại. Vui lòng thử lại!");
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
                require_once __DIR__ . '/../views/khach_hang/register_view.php';
            }
        } else {
            // Hiển thị form đăng ký
            require_once __DIR__ . '/../views/khach_hang/register_view.php';
        }
    }

/**
     * Hiển thị thông tin tài khoản
     */
    public function profile() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['khach_hang'])) {
            header('Location: index.php?controller=KhachHang&action=index');
            exit;
        }

        // Lấy thông tin khách hàng từ database
        $ma_kh = $_SESSION['khach_hang']['ma_kh'];
        $khach_hang = $this->khachHangModel->getKhachHangById($ma_kh);
        
        require_once __DIR__ . '/../views/khach_hang/account_view.php';
    }

    /**
     * Cập nhật thông tin tài khoản
     */
    public function updateProfile() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['khach_hang'])) {
            header('Location: index.php?controller=KhachHang&action=index');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $ma_kh = $_SESSION['khach_hang']['ma_kh'];
                $ho_ten = $_POST['ho_ten'] ?? '';
                $email = $_POST['email'] ?? '';
                $sdt = $_POST['sdt'] ?? '';
                $avatar = $_POST['avatar'] ?? null;

                // Kiểm tra email có thuộc về người khác không
                $currentCustomer = $this->khachHangModel->getKhachHangById($ma_kh);
                if ($currentCustomer['email'] !== $email && $this->khachHangModel->checkEmailExists($email)) {
                    throw new Exception("Email đã được sử dụng bởi tài khoản khác!");
                }

                // Xử lý upload avatar nếu có
                $avatar_path = $this->handleAvatarUpload($ma_kh);

                // Nếu không có avatar mới, giữ avatar cũ
                if ($avatar_path === null && isset($currentCustomer['avatar'])) {
                    $avatar_path = $currentCustomer['avatar'];
                }

                // Cập nhật thông tin
                $success = $this->khachHangModel->updateKhachHang($ma_kh, $ho_ten, $email, $sdt, $avatar_path);

                if ($success) {
                    // Cập nhật session
                    $_SESSION['khach_hang']['ho_ten'] = $ho_ten;
                    $_SESSION['khach_hang']['email'] = $email;
                    $_SESSION['khach_hang']['SDT'] = $sdt;
                    if ($avatar_path) {
                        $_SESSION['khach_hang']['avatar'] = $avatar_path;
                    }

                    header('Location: index.php?controller=KhachHang&action=profile&status=update_success');
                    exit;
                } else {
                    throw new Exception("Cập nhật thông tin thất bại!");
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
                $ma_kh = $_SESSION['khach_hang']['ma_kh'];
                $khach_hang = $this->khachHangModel->getKhachHangById($ma_kh);
                require_once __DIR__ . '/../views/khach_hang/account_view.php';
            }
        } else {
            header('Location: index.php?controller=KhachHang&action=profile');
            exit;
        }
    }

    /**
     * Xử lý upload avatar
     */
    private function handleAvatarUpload($ma_kh) {
    if (isset($_FILES['avatar-upload']) && $_FILES['avatar-upload']['error'] == UPLOAD_ERR_OK) {

        // Đường dẫn thư mục upload giống cách làm của hàm đầu
        $uploadDir = 'publics/img/avatar/';

        // Tạo thư mục nếu chưa có
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Lấy thông tin file
        $fileName = basename($_FILES['avatar-upload']['name']);
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

        // Kiểm tra loại file
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array(strtolower($fileExtension), $allowedTypes)) {
            return null; // hoặc throw Exception nếu muốn
        }

        // Kiểm tra kích thước (tối đa 5MB)
        if ($_FILES['avatar-upload']['size'] > 5 * 1024 * 1024) {
            return null; // hoặc throw Exception
        }

        // Tạo tên file an toàn (giống prefix của hàm đầu)
        $safeFileName = 'avatar_' . $ma_kh . '_' . time() . '.' . $fileExtension;
        $targetFilePath = $uploadDir . $safeFileName;

        // Lưu file
        if (move_uploaded_file($_FILES['avatar-upload']['tmp_name'], $targetFilePath)) {
            return $targetFilePath; // Trả về đường dẫn file đã lưu
        }
    }

    return null; //Không upload thì trả về null
}
public function hanhTrinh() {
    // Kiểm tra đăng nhập
    if (!isset($_SESSION['khach_hang'])) {
        header('Location: index.php?controller=khachhang');
        exit;
    }

    $ma_kh = $_SESSION['khach_hang']['ma_kh'];

    // Lấy dữ liệu từ model
    $bookingHistory = $this->khachHangModel->getBookingHistory($ma_kh);

    // Gọi view
    require_once __DIR__ . '/../views/khach_hang/hanh_trinh_view.php';
}


    /**
     * Đăng xuất
     */
    public function logout() {
        // Xóa session đăng nhập
        unset($_SESSION['khach_hang']);
        session_destroy();

        // Chuyển hướng về trang chủ
        header('Location: index.php?controller=trangchu&status=logout_success');
        exit;
    }
}
?>
    



