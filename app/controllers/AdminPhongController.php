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
     * Hiển thị danh sách phòng với bộ lọc
     */
    public function index() {
        // Nhận tham số lọc từ URL
        $ma_rap = $_GET['ma_rap'] ?? null;
        $search = $_GET['search'] ?? null;
        $loai_man_hinh = $_GET['loai_man_hinh'] ?? null;
        
        // Lấy danh sách phòng đã lọc
        if ($ma_rap || $search || $loai_man_hinh) {
            $danhSachPhong = $this->phongModel->filterPhong($ma_rap, $search, $loai_man_hinh);
        } else {
            $danhSachPhong = $this->phongModel->getAllPhongWithRap();
        }
        
        // Tính số lượng ghế cho từng phòng
        foreach ($danhSachPhong as $key => $phong) {
            $danhSachPhong[$key]['so_luong_ghe'] = $this->gheModel->countGheByPhong($phong['ma_phong']);
        }
        
        // Lấy danh sách rạp cho dropdown
        $danhSachRap = $this->rapModel->getAllRap();
        
        // Lấy danh sách loại màn hình unique
        $loai_man_hinh_list = $this->phongModel->getDistinctScreenTypes();
        
        // Lưu các tham số lọc
        $filter_params = [
            'ma_rap' => $ma_rap,
            'search' => $search,
            'loai_man_hinh' => $loai_man_hinh
        ];

        // Tải view
        require_once __DIR__ . '/../views/admin/phong_view.php';
    }

    /**
     * Hiển thị form THÊM MỚI phòng (inline)
     */
    public function create() {
        // Lấy danh sách phòng để hiển thị bên dưới form
        $danhSachPhong = $this->phongModel->getAllPhongWithRap();
        foreach ($danhSachPhong as $key => $phong) {
            $danhSachPhong[$key]['so_luong_ghe'] = $this->gheModel->countGheByPhong($phong['ma_phong']);
        }

        // Lấy danh sách rạp cho dropdown
        $danhSachRap = $this->rapModel->getAllRap();

        // Đặt cờ để view biết phải hiển thị form 'create'
        $action = 'create';
        
        // Khởi tạo filter_params rỗng
        $filter_params = [
            'ma_rap' => null,
            'search' => null,
            'loai_man_hinh' => null
        ];

        // Tải view
        require_once __DIR__ . '/../views/admin/phong_view.php';
    }

    /**
     * Xử lý lưu trữ phòng MỚI
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        } else {
            header('Location: index.php?controller=adminPhong&action=create');
        }
    }

    /**
     * Hiển thị form SỬA phòng (inline)
     */
    public function edit() {
        $edit_id = $_GET['id'] ?? null;
        if (!$edit_id) {
            header('Location: index.php?controller=adminPhong&action=index');
            exit;
        }

        // Lấy danh sách phòng để hiển thị
        $danhSachPhong = $this->phongModel->getAllPhongWithRap();
        foreach ($danhSachPhong as $key => $phong) {
            $danhSachPhong[$key]['so_luong_ghe'] = $this->gheModel->countGheByPhong($phong['ma_phong']);
        }
        
        // Lấy thông tin chi tiết của phòng cần sửa
        $phong_to_edit = $this->phongModel->getPhongByMa($edit_id);
        if (!$phong_to_edit) {
            header('Location: index.php?controller=adminPhong&action=index&status=not_found');
            exit;
        }

        // Lấy danh sách rạp cho dropdown
        $danhSachRap = $this->rapModel->getAllRap();
        
        // Lấy danh sách loại màn hình
        $loai_man_hinh_list = $this->phongModel->getDistinctScreenTypes();
        
        // Khởi tạo filter_params rỗng
        $filter_params = [
            'ma_rap' => null,
            'search' => null,
            'loai_man_hinh' => null
        ];

        // Tải view
        require_once __DIR__ . '/../views/admin/phong_view.php';
    }

    /**
     * Xử lý CẬP NHẬT phòng
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $ma_phong = $_POST['ma_phong'] ?? null;
                if (!$ma_phong) throw new Exception("Thiếu ID phòng.");
                
                $this->phongModel->updatePhong(
                    $ma_phong,
                    $_POST['ten_phong'],
                    $_POST['ma_rap'],
                    $_POST['loai_man_hinh']
                );
                header("Location: index.php?controller=adminPhong&action=index&status=update_success");
            } catch (Exception $e) {
                header("Location: index.php?controller=adminPhong&action=index&status=update_error");
            }
        }
    }

    /**
     * Xử lý XÓA một phòng
     */
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

    /**
     * Hiển thị quản lý ghế của một phòng
     */
    public function manageSeats() {
        $ma_phong = $_GET['ma_phong'] ?? null;
        if (!$ma_phong) {
            header('Location: index.php?controller=adminPhong&action=index');
            exit;
        }

        // Lấy thông tin phòng
        $selected_phong_info = $this->phongModel->getPhongByMa($ma_phong);
        if (!$selected_phong_info) {
            header('Location: index.php?controller=adminPhong&action=index&status=not_found');
            exit;
        }

        // Lấy danh sách ghế
        $danhSachGhe = $this->gheModel->getAllGheByPhong($ma_phong);

        // Tải view quản lý ghế
        require_once __DIR__ . '/../views/admin/ghe_view.php';
    }

    /**
     * Hiển thị form THÊM ghế mới
     */
    public function createGhe() {
        $ma_phong = $_GET['ma_phong'] ?? null;
        if (!$ma_phong) {
            header('Location: index.php?controller=adminPhong&action=index');
            exit;
        }

        // Lấy thông tin phòng
        $selected_phong_info = $this->phongModel->getPhongByMa($ma_phong);
        
        // Lấy danh sách ghế
        $danhSachGhe = $this->gheModel->getAllGheByPhong($ma_phong);

        // Đặt cờ để hiển thị form thêm ghế
        $action = 'create_ghe';

        // Tải view
        require_once __DIR__ . '/../views/admin/ghe_view.php';
    }

    /**
     * Xử lý thêm ghế mới
     */
    public function storeGhe() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $ma_phong = $_POST['ma_phong'] ?? null;
                if (!$ma_phong) throw new Exception("Thiếu ID phòng.");

                $this->gheModel->addGhe(
                    $ma_phong,
                    $_POST['vi_tri'],
                    $_POST['loai_ghe'],
                    $_POST['trang_thai']
                );
                header("Location: index.php?controller=adminPhong&action=manageSeats&ma_phong=" . $ma_phong . "&status=add_ghe_success");
            } catch (Exception $e) {
                header("Location: index.php?controller=adminPhong&action=manageSeats&ma_phong=" . $_POST['ma_phong'] . "&status=add_ghe_error");
            }
        }
    }

    /**
     * Hiển thị form SỬA ghế
     */
    public function editGhe() {
        $ma_ghe = $_GET['ma_ghe'] ?? null;
        $ma_phong = $_GET['ma_phong'] ?? null;
        
        if (!$ma_ghe || !$ma_phong) {
            header('Location: index.php?controller=adminPhong&action=index');
            exit;
        }

        // Lấy thông tin phòng
        $selected_phong_info = $this->phongModel->getPhongByMa($ma_phong);
        
        // Lấy danh sách ghế
        $danhSachGhe = $this->gheModel->getAllGheByPhong($ma_phong);
        
        // Lấy thông tin ghế cần sửa
        $ghe_to_edit = null;
        foreach ($danhSachGhe as $ghe) {
            if ($ghe['ma_ghe'] == $ma_ghe) {
                $ghe_to_edit = $ghe;
                break;
            }
        }
        
        if (!$ghe_to_edit) {
            header('Location: index.php?controller=adminPhong&action=manageSeats&ma_phong=' . $ma_phong . '&status=not_found');
            exit;
        }

        // Tải view
        require_once __DIR__ . '/../views/admin/ghe_view.php';
    }

    /**
     * Xử lý CẬP NHẬT ghế
     */
    public function updateGhe() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
                header("Location: index.php?controller=adminPhong&action=manageSeats&ma_phong=" . $ma_phong . "&status=update_ghe_success");
            } catch (Exception $e) {
                header("Location: index.php?controller=adminPhong&action=manageSeats&ma_phong=" . $_POST['ma_phong'] . "&status=update_ghe_error");
            }
        }
    }

    /**
     * Xử lý XÓA ghế
     */
    public function destroyGhe() {
        $ma_ghe = $_GET['ma_ghe'] ?? null;
        $ma_phong = $_GET['ma_phong'] ?? null;
        
        if ($ma_ghe && $ma_phong) {
            if ($this->gheModel->deleteGhe($ma_ghe)) {
                header("Location: index.php?controller=adminPhong&action=manageSeats&ma_phong=" . $ma_phong . "&status=delete_ghe_success");
            } else {
                header("Location: index.php?controller=adminPhong&action=manageSeats&ma_phong=" . $ma_phong . "&status=delete_ghe_error");
            }
        } else {
            header('Location: index.php?controller=adminPhong&action=index');
        }
    }
}
?>