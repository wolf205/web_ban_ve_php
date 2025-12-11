<?php
// app/controllers/AdminPhimController.php
session_start();

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/PhimModel.php';

class AdminPhimController {
    private $db;
    private $phimModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        if ($this->db === null) {
            die("Không thể kết nối đến CSDL.");
        }
        $this->phimModel = new PhimModel($this->db);
    }

    /**
     * 1. DANH SÁCH & BỘ LỌC (INDEX)
     */
    public function index() {
        $filters = [];
        
        // 1. Lọc Thể loại (GIỮ LẠI)
        if (!empty($_GET['the_loai'])) {
            $filters['the_loai'] = trim($_GET['the_loai']);
        }

        // 2. Lọc Trạng thái
        if (!empty($_GET['trang_thai'])) {
            $filters['trang_thai'] = $_GET['trang_thai'];
        }

        // 3. Lọc Độ tuổi
        if (isset($_GET['gioi_han_do_tuoi']) && $_GET['gioi_han_do_tuoi'] !== '') {
            $filters['gioi_han_do_tuoi'] = $_GET['gioi_han_do_tuoi'];
        }

        // 4. Lọc Hot
        if (isset($_GET['hot']) && $_GET['hot'] !== '') {
            $filters['hot'] = $_GET['hot'];
        }

        // 5. Lọc Thời gian
        if (!empty($_GET['tu_ngay'])) {
            $filters['tu_ngay'] = $_GET['tu_ngay'];
        }
        if (!empty($_GET['den_ngay'])) {
            $filters['den_ngay'] = $_GET['den_ngay'];
        }

        // Gọi Model
        if (!empty($filters)) {
             $danhSachPhim = $this->phimModel->getPhimWithFilter($filters);
        } else {
            $danhSachPhim = $this->phimModel->getAllPhim();
        }

        $action = 'index';
        require_once __DIR__ . '/../views/admin/list.php';
    }

    /**
     * 2. HIỂN THỊ FORM THÊM MỚI (CREATE)
     */
    public function create() {
        // Vẫn phải lấy danh sách phim để hiển thị bảng bên dưới form
        $danhSachPhim = $this->phimModel->getAllPhim();
        
        $action = 'create'; // Báo cho view hiện form Thêm
        require_once __DIR__ . '/../views/admin/list.php';
    }

    /**
     * 3. XỬ LÝ LƯU PHIM MỚI (STORE)
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Xử lý Upload Ảnh Trailer/Poster
                $anh_trailer = "";
                if (isset($_FILES['anh_trailer']) && $_FILES['anh_trailer']['error'] == 0) {
                    $target_dir = "publics/img/phim/";
                    
                    // Tạo thư mục nếu chưa có
                    if (!file_exists($target_dir)) {
                        mkdir($target_dir, 0777, true);
                    }

                    $file_name = time() . '_' . basename($_FILES["anh_trailer"]["name"]);
                    $target_file = $target_dir . $file_name;
                    
                    if (move_uploaded_file($_FILES["anh_trailer"]["tmp_name"], $target_file)) {
                        $anh_trailer = $target_file;
                    }
                }

                // Gọi Model để thêm
                $success = $this->phimModel->createPhim(
                    $_POST['ten_phim'],
                    $_POST['the_loai'],
                    $_POST['thoi_luong'],
                    $_POST['dao_dien'],
                    $_POST['dien_vien'],
                    $_POST['mo_ta'],
                    $_POST['ngay_khoi_chieu'],
                    $_POST['gioi_han_do_tuoi'],
                    $anh_trailer,
                    isset($_POST['hot']) ? 1 : 0
                );

                if ($success) {
                    header("Location: index.php?controller=adminPhim&action=index&status=add_success");
                } else {
                    throw new Exception("Lỗi khi thêm phim vào CSDL");
                }
            } catch (Exception $e) {
                // Có thể log lỗi ở đây
                header("Location: index.php?controller=adminPhim&action=index&status=add_error");
            }
        }
    }

    /**
     * 4. HIỂN THỊ FORM SỬA (EDIT)
     */
    public function edit() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?controller=adminPhim&action=index');
            exit;
        }

        // Lấy thông tin phim cần sửa
        $phim = $this->phimModel->getPhimById($id);
        if (!$phim) {
            header('Location: index.php?controller=adminPhim&action=index&status=not_found');
            exit;
        }

        // Vẫn lấy danh sách phim cho bảng bên dưới
        $danhSachPhim = $this->phimModel->getAllPhim();

        $action = 'edit'; // Báo cho view hiện form Sửa và đổ dữ liệu $phim
        require_once __DIR__ . '/../views/admin/list.php';
    }

    /**
     * 5. XỬ LÝ CẬP NHẬT PHIM (UPDATE)
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $id = $_POST['ma_phim'];
                
                // Giữ lại ảnh cũ mặc định
                $anh_trailer = $_POST['anh_cu']; 

                // Nếu có upload ảnh mới thì thay thế
                if (isset($_FILES['anh_trailer']) && $_FILES['anh_trailer']['error'] == 0) {
                    $target_dir = "publics/img/";
                    if (!file_exists($target_dir)) {
                        mkdir($target_dir, 0777, true);
                    }
                    
                    $file_name = time() . '_' . basename($_FILES["anh_trailer"]["name"]);
                    $target_file = $target_dir . $file_name;
                    
                    if (move_uploaded_file($_FILES["anh_trailer"]["tmp_name"], $target_file)) {
                        $anh_trailer = $target_file;
                    }
                }

                // Gọi Model update
                $success = $this->phimModel->updatePhim(
                    $id,
                    $_POST['ten_phim'],
                    $_POST['the_loai'],
                    $_POST['thoi_luong'],
                    $_POST['dao_dien'],
                    $_POST['dien_vien'],
                    $_POST['mo_ta'],
                    $_POST['ngay_khoi_chieu'],
                    $_POST['gioi_han_do_tuoi'],
                    $anh_trailer,
                    isset($_POST['hot']) ? 1 : 0
                );

                if ($success) {
                    header("Location: index.php?controller=adminPhim&action=index&status=update_success");
                } else {
                    throw new Exception("Lỗi cập nhật phim");
                }
            } catch (Exception $e) {
                header("Location: index.php?controller=adminPhim&action=index&status=update_error");
            }
        }
    }

    /**
     * 6. XÓA PHIM (DESTROY)
     */
    public function destroy() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            try {
                if ($this->phimModel->deletePhim($id)) {
                    header("Location: index.php?controller=adminPhim&action=index&status=delete_success");
                } else {
                    header("Location: index.php?controller=adminPhim&action=index&status=delete_error");
                }
            } catch (Exception $e) {
                // Thường lỗi do ràng buộc khóa ngoại (phim đã có suất chiếu)
                header("Location: index.php?controller=adminPhim&action=index&status=delete_constraint_error");
            }
        } else {
            header('Location: index.php?controller=adminPhim&action=index');
        }
    }
}
?>