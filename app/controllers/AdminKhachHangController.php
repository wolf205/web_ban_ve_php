<?php
// app/controllers/AdminKhachHangController.php
session_start();

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/KhachHangModel.php';

class AdminKhachHangController {

    private $db;
    private $khachHangModel;

    public function __construct() {
        // Kiểm tra admin session
        // if (!isset($_SESSION['admin_user'])) {
        //     header('Location: login.php');
        //     exit;
        // }

        $database = new Database();
        $this->db = $database->getConnection();
        if ($this->db === null) throw new \Exception("Không thể kết nối đến CSDL.");

        $this->khachHangModel = new KhachHangModel($this->db);
    }

    /**
     * Xử lý upload file avatar
     */
    private function handleFileUpload($fileInputName, $ma_kh_prefix = '') {
        if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] == 0) {
            // Đảm bảo đường dẫn này đúng từ file index.php gốc
            $uploadDir = 'publics/img/avatar/'; 
            
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileName = basename($_FILES[$fileInputName]['name']);
            // Thêm prefix để tránh trùng tên file
            $safeFileName = $ma_kh_prefix . time() . '_' . $fileName;
            $targetFilePath = $uploadDir . $safeFileName;
            
            if (move_uploaded_file($_FILES[$fileInputName]['tmp_name'], $targetFilePath)) {
                return $targetFilePath; // Trả về đường dẫn file đã lưu
            }
        }
        return null; // Trả về null nếu không upload
    }

    /**
     * Hiển thị danh sách khách hàng với bộ lọc
     */
    public function index() {
        // Nhận tham số lọc
        $vai_tro = $_GET['vai_tro'] ?? $_POST['vai_tro'] ?? null;
        $search = $_GET['search'] ?? $_POST['search'] ?? null;
        
        // Lấy danh sách khách hàng đã lọc
        if ($vai_tro || $search) {
            $danhSachKhachHang = $this->khachHangModel->filterKhachHang($vai_tro, $search);
        } else {
            $danhSachKhachHang = $this->khachHangModel->getAllKhachHang();
        }
        
        // Lấy danh sách vai trò để hiển thị trong dropdown
        $roles = $this->khachHangModel->getDistinctRoles();
        
        // Lưu các tham số lọc
        $filter_params = [
            'vai_tro' => $vai_tro,
            'search' => $search
        ];
        
        // Tải view
        require_once __DIR__ . '/../views/admin/khachhang_view.php';
    }

    /**
     * Hiển thị form THÊM MỚI
     */
    public function create() {
        // Lấy danh sách khách hàng để hiển thị bên dưới form
        $danhSachKhachHang = $this->khachHangModel->getAllKhachHang();
        $roles = $this->khachHangModel->getDistinctRoles();

        // Đặt cờ để view biết phải hiển thị form 'create'
        $action = 'create';

        // Tải view
        require_once __DIR__ . '/../views/admin/khachhang_view.php';
    }

    /**
     * Xử lý lưu trữ khách hàng MỚI
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Upload avatar nếu có
                $avatar_path = $this->handleFileUpload('avatar');
                
                // Tạo mật khẩu mặc định nếu không nhập
                $mat_khau = !empty($_POST['mat_khau']) ? $_POST['mat_khau'] : '123456'; // Mật khẩu mặc định
                
                // CHỈ THÊM TÀI KHOẢN VỚI VAI TRÒ "QUẢN LÝ"
                $result = $this->khachHangModel->addQuanLy(
                    $_POST['ho_ten'],
                    $_POST['email'],
                    $_POST['SDT'],
                    $_POST['tai_khoan'],
                    $mat_khau,
                    $avatar_path // Truyền đường dẫn avatar đã upload
                );
                
                if ($result) {
                    header("Location: index.php?controller=adminKhachHang&action=index&status=add_success");
                } else {
                    header("Location: index.php?controller=adminKhachHang&action=index&status=add_error");
                }
                exit;
            } catch (Exception $e) {
                header("Location: index.php?controller=adminKhachHang&action=index&status=add_error");
                exit;
            }
        } else {
            header('Location: index.php?controller=adminKhachHang&action=create');
            exit;
        }
    }

    /**
     * Hiển thị form SỬA
     */
    public function edit() {
        $edit_id = $_GET['id'] ?? null;
        if (!$edit_id) {
            header('Location: index.php?controller=adminKhachHang&action=index');
            exit;
        }

        // Lấy danh sách khách hàng để hiển thị
        $danhSachKhachHang = $this->khachHangModel->getAllKhachHang();
        $roles = $this->khachHangModel->getDistinctRoles();
        
        // Lấy thông tin chi tiết của khách hàng cần sửa
        $khachhang_to_edit = $this->khachHangModel->getKhachHangById($edit_id);
        if (!$khachhang_to_edit) {
             header('Location: index.php?controller=adminKhachHang&action=index&status=not_found');
             exit;
        }

        // Tải view
        require_once __DIR__ . '/../views/admin/khachhang_view.php';
    }

    /**
     * Xử lý CẬP NHẬT khách hàng
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $ma_kh = $_POST['ma_kh'] ?? null;
                if (!$ma_kh) throw new Exception("Thiếu ID khách hàng.");

                $mat_khau = !empty($_POST['mat_khau']) ? $_POST['mat_khau'] : null;
                
                $result = $this->khachHangModel->updateKhachHangAdmin(
                    $ma_kh,
                    $_POST['ho_ten'],
                    $_POST['email'],
                    $_POST['SDT'],
                    $_POST['tai_khoan'],
                    $mat_khau,
                    $_POST['vai_tro']
                );
                
                if ($result) {
                    header("Location: index.php?controller=adminKhachHang&action=index&status=update_success");
                } else {
                    header("Location: index.php?controller=adminKhachHang&action=index&status=update_error");
                }
                exit;
            } catch (Exception $e) {
                header("Location: index.php?controller=adminKhachHang&action=index&status=update_error");
                exit;
            }
        }
    }

    /**
     * Xử lý XÓA một khách hàng
     */
    public function destroy() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            // CHỈ CHO PHÉP XÓA TÀI KHOẢN CÓ VAI TRÒ "QUẢN LÝ"
            $khachhang = $this->khachHangModel->getKhachHangById($id);
            if ($khachhang && $khachhang['vai_tro'] === 'quản lý') {
                if ($this->khachHangModel->deleteKhachHang($id)) {
                    header("Location: index.php?controller=adminKhachHang&action=index&status=delete_success");
                } else {
                    // Lỗi do ràng buộc khóa ngoại (có hóa đơn)
                    header("Location: index.php?controller=adminKhachHang&action=index&status=delete_error_fk");
                }
            } else {
                // Không cho phép xóa tài khoản không phải "quản lý"
                header("Location: index.php?controller=adminKhachHang&action=index&status=delete_not_allowed");
            }
        } else {
            header('Location: index.php?controller=adminKhachHang&action=index');
        }
        exit;
    }
}
?>