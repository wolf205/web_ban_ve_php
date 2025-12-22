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
     * Hiển thị danh sách phòng với bộ lọc và phân trang
     */
    public function index() {
        // 1. Nhận tham số lọc
        $ma_rap = $_GET['ma_rap'] ?? null;
        $search = $_GET['search'] ?? null;
        $loai_man_hinh = $_GET['loai_man_hinh'] ?? null;
        
        // 2. Cấu hình phân trang
        $limit = 3; // Số phòng mỗi trang
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;
        
        // 3. Lấy dữ liệu với phân trang
        if ($ma_rap || $search || $loai_man_hinh) {
            $danhSachPhong = $this->phongModel->filterPhongPhanTrang($limit, $offset, $ma_rap, $search, $loai_man_hinh);
            $totalRecords = $this->phongModel->countFilterPhong($ma_rap, $search, $loai_man_hinh);
        } else {
            $danhSachPhong = $this->phongModel->getAllPhongWithRapPhanTrang($limit, $offset);
            $totalRecords = $this->phongModel->countAllPhong();
        }
        
        // 4. Tính toán phân trang
        $totalPages = ceil($totalRecords / $limit);
        
        // 5. Tính số lượng ghế cho từng phòng
        foreach ($danhSachPhong as $key => $phong) {
            $danhSachPhong[$key]['so_luong_ghe'] = $this->gheModel->countGheByPhong($phong['ma_phong']);
        }
        
        // 6. Lấy danh sách rạp và loại màn hình
        $danhSachRap = $this->rapModel->getAllRap();
        $loai_man_hinh_list = $this->phongModel->getDistinctScreenTypes();
        
        // 7. Lưu tham số lọc
        $filter_params = [
            'ma_rap' => $ma_rap,
            'search' => $search,
            'loai_man_hinh' => $loai_man_hinh
        ];

        // 8. Tải view
        require_once __DIR__ . '/../views/admin/phong_view.php';
    }

    /**
     * Hiển thị form THÊM MỚI phòng (inline)
     */
    public function create() {
        // 1. Lấy tham số phân trang
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 3;
        $offset = ($page - 1) * $limit;
        
        // 2. Lấy dữ liệu với phân trang
        $danhSachPhong = $this->phongModel->getAllPhongWithRapPhanTrang($limit, $offset);
        
        // 3. Tính toán phân trang
        $totalRecords = $this->phongModel->countAllPhong();
        $totalPages = ceil($totalRecords / $limit);
        
        // Tính số lượng ghế
        foreach ($danhSachPhong as $key => $phong) {
            $danhSachPhong[$key]['so_luong_ghe'] = $this->gheModel->countGheByPhong($phong['ma_phong']);
        }

        // 4. Lấy danh sách rạp
        $danhSachRap = $this->rapModel->getAllRap();

        // 5. Lấy danh sách loại màn hình
        $loai_man_hinh_list = $this->phongModel->getDistinctScreenTypes();
        
        // 6. Đặt cờ và tham số
        $action = 'create';
        $filter_params = [
            'ma_rap' => null,
            'search' => null,
            'loai_man_hinh' => null
        ];

        // 7. Tải view
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
                
                // Quay lại trang hiện tại
                $page = $_POST['page'] ?? 1;
                header("Location: index.php?controller=adminPhong&action=index&page=" . $page . "&status=add_success");
            } catch (Exception $e) {
                $page = $_POST['page'] ?? 1;
                header("Location: index.php?controller=adminPhong&action=index&page=" . $page . "&status=add_error");
            }
        } else {
            $page = $_GET['page'] ?? 1;
            header('Location: index.php?controller=adminPhong&action=create&page=' . $page);
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
        
        // 1. Lấy tham số phân trang
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 3;
        $offset = ($page - 1) * $limit;
        
        // 2. Lấy dữ liệu với phân trang
        $danhSachPhong = $this->phongModel->getAllPhongWithRapPhanTrang($limit, $offset);
        
        // 3. Tính toán phân trang
        $totalRecords = $this->phongModel->countAllPhong();
        $totalPages = ceil($totalRecords / $limit);
        
        // Tính số lượng ghế
        foreach ($danhSachPhong as $key => $phong) {
            $danhSachPhong[$key]['so_luong_ghe'] = $this->gheModel->countGheByPhong($phong['ma_phong']);
        }
        
        // 4. Lấy thông tin phòng cần sửa
        $phong_to_edit = $this->phongModel->getPhongByMa($edit_id);
        if (!$phong_to_edit) {
            header('Location: index.php?controller=adminPhong&action=index&status=not_found');
            exit;
        }

        // 5. Lấy danh sách rạp và loại màn hình
        $danhSachRap = $this->rapModel->getAllRap();
        $loai_man_hinh_list = $this->phongModel->getDistinctScreenTypes();
        
        // 6. Tải view
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
                
                // Quay lại trang hiện tại
                $page = $_POST['page'] ?? 1;
                header("Location: index.php?controller=adminPhong&action=index&page=" . $page . "&status=update_success");
            } catch (Exception $e) {
                $page = $_POST['page'] ?? 1;
                header("Location: index.php?controller=adminPhong&action=index&page=" . $page . "&status=update_error");
            }
        }
    }

    /**
     * Xử lý XÓA một phòng
     */
    public function destroy() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            // Lấy trang hiện tại
            $page = $_GET['page'] ?? 1;
            
            if ($this->phongModel->deletePhong($id)) {
                header("Location: index.php?controller=adminPhong&action=index&page=" . $page . "&status=delete_success");
            } else {
                header("Location: index.php?controller=adminPhong&action=index&page=" . $page . "&status=delete_error_fk");
            }
        } else {
            $page = $_GET['page'] ?? 1;
            header('Location: index.php?controller=adminPhong&action=index&page=' . $page);
        }
    }

    /**
     * Hiển thị quản lý ghế của một phòng với phân trang
     */
    public function manageSeats() {
        $ma_phong = $_GET['ma_phong'] ?? null;
        if (!$ma_phong) {
            header('Location: index.php?controller=adminPhong&action=index');
            exit;
        }

        // 1. Cấu hình phân trang
        $limit = 5; // Số ghế mỗi trang
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        // 2. Lấy thông tin phòng
        $selected_phong_info = $this->phongModel->getPhongByMa($ma_phong);
        if (!$selected_phong_info) {
            header('Location: index.php?controller=adminPhong&action=index&status=not_found');
            exit;
        }

        // 3. Lấy danh sách ghế với phân trang
        $danhSachGhe = $this->gheModel->getAllGheByPhongPhanTrang($ma_phong, $limit, $offset);
        $totalRecords = $this->gheModel->countGheByPhongTotal($ma_phong);
        $totalPages = ceil($totalRecords / $limit);

        // 4. Tải view
        require_once __DIR__ . '/../views/admin/ghe_view.php';
    }

    /**
     * Hiển thị form THÊM ghế mới
     */
    public function createGhe() {
        $ma_phong = $_GET['ma_phong'] ?? null;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        
        if (!$ma_phong) {
            header('Location: index.php?controller=adminPhong&action=index');
            exit;
        }

        // 1. Cấu hình phân trang
        $limit = 5;
        $offset = ($page - 1) * $limit;

        // 2. Lấy thông tin phòng
        $selected_phong_info = $this->phongModel->getPhongByMa($ma_phong);
        
        // 3. Lấy danh sách ghế với phân trang
        $danhSachGhe = $this->gheModel->getAllGheByPhongPhanTrang($ma_phong, $limit, $offset);
        $totalRecords = $this->gheModel->countGheByPhongTotal($ma_phong);
        $totalPages = ceil($totalRecords / $limit);

        // 4. Đặt cờ để hiển thị form thêm ghế
        $action = 'create_ghe';

        // 5. Tải view
        require_once __DIR__ . '/../views/admin/ghe_view.php';
    }

    /**
     * Xử lý thêm ghế mới
     */
    public function storeGhe() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $ma_phong = $_POST['ma_phong'] ?? null;
                $page = $_POST['page'] ?? 1;
                
                if (!$ma_phong) throw new Exception("Thiếu ID phòng.");

                $this->gheModel->addGhe(
                    $ma_phong,
                    $_POST['vi_tri'],
                    $_POST['loai_ghe'],
                    $_POST['trang_thai']
                );
                
                header("Location: index.php?controller=adminPhong&action=manageSeats&ma_phong=" . $ma_phong . "&page=" . $page . "&status=add_ghe_success");
            } catch (Exception $e) {
                $page = $_POST['page'] ?? 1;
                header("Location: index.php?controller=adminPhong&action=manageSeats&ma_phong=" . $_POST['ma_phong'] . "&page=" . $page . "&status=add_ghe_error");
            }
        }
    }

    /**
     * Hiển thị form SỬA ghế
     */
    public function editGhe() {
        $ma_ghe = $_GET['ma_ghe'] ?? null;
        $ma_phong = $_GET['ma_phong'] ?? null;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        
        if (!$ma_ghe || !$ma_phong) {
            header('Location: index.php?controller=adminPhong&action=index');
            exit;
        }

        // 1. Cấu hình phân trang
        $limit = 5;
        $offset = ($page - 1) * $limit;

        // 2. Lấy thông tin phòng
        $selected_phong_info = $this->phongModel->getPhongByMa($ma_phong);
        
        // 3. Lấy danh sách ghế với phân trang
        $danhSachGhe = $this->gheModel->getAllGheByPhongPhanTrang($ma_phong, $limit, $offset);
        $totalRecords = $this->gheModel->countGheByPhongTotal($ma_phong);
        $totalPages = ceil($totalRecords / $limit);
        
        // 4. Lấy thông tin ghế cần sửa
        $ghe_to_edit = null;
        foreach ($danhSachGhe as $ghe) {
            if ($ghe['ma_ghe'] == $ma_ghe) {
                $ghe_to_edit = $ghe;
                break;
            }
        }
        
        if (!$ghe_to_edit) {
            // Nếu không tìm thấy trong trang hiện tại, lấy thông tin trực tiếp
            $ghe_to_edit = $this->gheModel->getGheById($ma_ghe);
            
            if (!$ghe_to_edit) {
                header('Location: index.php?controller=adminPhong&action=manageSeats&ma_phong=' . $ma_phong . '&page=' . $page . '&status=not_found');
                exit;
            }
        }

        // 5. Tải view
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
                $page = $_POST['page'] ?? 1;
                
                if (!$ma_ghe || !$ma_phong) throw new Exception("Thiếu thông tin.");

                $this->gheModel->updateGhe(
                    $ma_ghe,
                    $_POST['vi_tri'],
                    $_POST['loai_ghe'],
                    $_POST['trang_thai']
                );
                
                header("Location: index.php?controller=adminPhong&action=manageSeats&ma_phong=" . $ma_phong . "&page=" . $page . "&status=update_ghe_success");
            } catch (Exception $e) {
                $page = $_POST['page'] ?? 1;
                header("Location: index.php?controller=adminPhong&action=manageSeats&ma_phong=" . $_POST['ma_phong'] . "&page=" . $page . "&status=update_ghe_error");
            }
        }
    }

    /**
     * Xử lý XÓA ghế
     */
    public function destroyGhe() {
        $ma_ghe = $_GET['ma_ghe'] ?? null;
        $ma_phong = $_GET['ma_phong'] ?? null;
        $page = $_GET['page'] ?? 1;
        
        if ($ma_ghe && $ma_phong) {
            if ($this->gheModel->deleteGhe($ma_ghe)) {
                header("Location: index.php?controller=adminPhong&action=manageSeats&ma_phong=" . $ma_phong . "&page=" . $page . "&status=delete_ghe_success");
            } else {
                header("Location: index.php?controller=adminPhong&action=manageSeats&ma_phong=" . $ma_phong . "&page=" . $page . "&status=delete_ghe_error");
            }
        } else {
            header('Location: index.php?controller=adminPhong&action=index');
        }
    }
}
?>