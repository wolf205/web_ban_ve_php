<?php
// app/controllers/AdminPhongController.php
session_start();

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/PhongModel.php';
require_once __DIR__ . '/../models/GheModel.php';
require_once __DIR__ . '/../models/RapModel.php';

class AdminPhongController {

    private $db;
    private $phongModel;
    private $gheModel;
    private $rapModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        if ($this->db === null) throw new \Exception("Không thể kết nối đến CSDL.");

        $this->phongModel = new PhongModel($this->db);
        $this->gheModel = new GheModel($this->db);
        $this->rapModel = new RapModel($this->db);
    }

    /**
 * Hiển thị danh sách phòng và quản lý ghế
 */
public function index() {
    // Nhận tham số lọc từ URL
    $ma_rap = $_GET['ma_rap'] ?? null;
    $search = $_GET['search'] ?? null;
    $loai_man_hinh = $_GET['loai_man_hinh'] ?? null;
    
    // Lấy danh sách rạp cho dropdown
    $danhSachRap = $this->rapModel->getAllRap();
    
    // Lấy danh sách loại màn hình duy nhất
    $loai_man_hinh_list = $this->phongModel->getDistinctScreenTypes();
    
    // Lấy danh sách phòng đã lọc
    if ($ma_rap || $search || $loai_man_hinh) {
        $danhSachPhong = $this->phongModel->filterPhong($ma_rap, $search, $loai_man_hinh);
    } else {
        $danhSachPhong = $this->phongModel->getAllPhongWithRap();
    }
    
    // Tính số lượng ghế cho từng phòng và thêm vào mảng
    foreach ($danhSachPhong as &$phong) {
        $phong['so_luong_ghe'] = $this->gheModel->countGheByPhong($phong['ma_phong']);
    }
    unset($phong); // Hủy tham chiếu
    
    // Xử lý selected phòng (nếu có)
    $selected_phong_id = $_GET['selected_phong'] ?? null;
    $selected_phong_info = null;
    $danhSachGhe = [];

    if ($selected_phong_id) {
        $selected_phong_info = $this->phongModel->getPhongByMa($selected_phong_id);
        $danhSachGhe = $this->gheModel->getAllGheByPhong($selected_phong_id);
    }
    
    // Lưu các tham số lọc
    $filter_params = [
        'ma_rap' => $ma_rap,
        'search' => $search,
        'loai_man_hinh' => $loai_man_hinh
    ];

    // Tải view
    require_once __DIR__ . '/../views/admin/phong_view.php';
}
    // ... (các hàm khác giữ nguyên)
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_phong'])) {
            try {
                $this->phongModel->addPhong(
                    $_POST['ten_phong'],
                    $_POST['ma_rap'],
                    $_POST['loai_man_hinh']
                );
                header("Location: index.php?controller=adminPhong&action=index&status=add_success");
            } catch (Exception $e) {
                header("Location: index.php?controller=adminPhong&action=index&status=add_error");
            }
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_phong'])) {
            try {
                $ma_phong = $_POST['ma_phong'] ?? null;
                if (!$ma_phong) throw new Exception("Thiếu ID phòng.");

                $this->phongModel->updatePhong(
                    $ma_phong,
                    $_POST['ten_phong'],
                    $_POST['ma_rap'],
                    $_POST['loai_man_hinh']
                );
                header("Location: index.php?controller=adminPhong&action=index&selected_phong=" . $ma_phong . "&status=update_success");
            } catch (Exception $e) {
                header("Location: index.php?controller=adminPhong&action=index&status=update_error");
            }
        }
    }

    public function destroy() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            if ($this->phongModel->deletePhong($id)) {
                header("Location: index.php?controller=adminPhong&action=index&status=delete_success");
            } else {
                header("Location: index.php?controller=adminPhong&action=index&status=delete_error_fk");
            }
        } else {
            header('Location: index.php?controller=adminPhong&action=index');
        }
    }

    public function addGhe() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_ghe'])) {
            try {
                $ma_phong = $_POST['ma_phong'] ?? null;
                if (!$ma_phong) throw new Exception("Thiếu ID phòng.");

                $this->gheModel->addGhe(
                    $ma_phong,
                    $_POST['vi_tri'],
                    $_POST['loai_ghe'],
                    $_POST['trang_thai']
                );
                header("Location: index.php?controller=adminPhong&action=index&selected_phong=" . $ma_phong . "&status=add_ghe_success");
            } catch (Exception $e) {
                header("Location: index.php?controller=adminPhong&action=index&selected_phong=" . $_POST['ma_phong'] . "&status=add_ghe_error");
            }
        }
    }

    public function updateGhe() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_ghe'])) {
            try {
                $ma_ghe = $_POST['ma_ghe'] ?? null;
                $ma_phong = $_POST['ma_phong'] ?? null;
                if (!$ma_ghe || !$ma_phong) throw new Exception("Thiếu thông tin.");

                $this->gheModel->updateGhe(
                    $ma_ghe,
                    $_POST['vi_tri'],
                    $_POST['loai_ghe'],
                    $_POST['trang_thai']
                );
                header("Location: index.php?controller=adminPhong&action=index&selected_phong=" . $ma_phong . "&status=update_ghe_success");
            } catch (Exception $e) {
                header("Location: index.php?controller=adminPhong&action=index&selected_phong=" . $_POST['ma_phong'] . "&status=update_ghe_error");
            }
        }
    }

    public function destroyGhe() {
        $ma_ghe = $_GET['ma_ghe'] ?? null;
        $ma_phong = $_GET['ma_phong'] ?? null;
        
        if ($ma_ghe && $ma_phong) {
            if ($this->gheModel->deleteGhe($ma_ghe)) {
                header("Location: index.php?controller=adminPhong&action=index&selected_phong=" . $ma_phong . "&status=delete_ghe_success");
            } else {
                header("Location: index.php?controller=adminPhong&action=index&selected_phong=" . $ma_phong . "&status=delete_ghe_error");
            }
        } else {
            header('Location: index.php?controller=adminPhong&action=index');
        }
    }
}
?>