<?php
// app/controllers/AdminRapController.php
session_start();

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/RapModel.php';
require_once __DIR__ . '/../models/PhongModel.php';

class AdminRapController {

    private $db;
    private $rapModel;
    private $phongModel;

    public function __construct() {
        // (Bảo mật: Kiểm tra admin session nếu cần)
        // if (!isset($_SESSION['admin_user'])) {
        //     header('Location: login.php');
        //     exit;
        // }

        $database = new Database();
        $this->db = $database->getConnection();
        if ($this->db === null) throw new \Exception("Không thể kết nối đến CSDL.");

        $this->rapModel = new RapModel($this->db);
        $this->phongModel = new PhongModel($this->db);
    }

    /**
     * Hàm tiện ích xử lý upload file
     */
    private function handleFileUpload($fileInputName, $ma_rap_prefix = '') {
        if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] == 0) {
            // Đảm bảo đường dẫn này đúng từ file index.php gốc
            $uploadDir = 'publics/img/rap/'; 
            
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileName = basename($_FILES[$fileInputName]['name']);
            // Thêm prefix để tránh trùng tên file
            $safeFileName = $ma_rap_prefix . time() . '_' . $fileName;
            $targetFilePath = $uploadDir . $safeFileName;
            
            if (move_uploaded_file($_FILES[$fileInputName]['tmp_name'], $targetFilePath)) {
                return $targetFilePath; // Trả về đường dẫn file đã lưu
            }
        }
        return null; // Trả về null nếu không upload
    }

    /**
 * Hiển thị danh sách rạp với bộ lọc
 */
public function index() {
    // Nhận tham số lọc từ URL hoặc POST
    $thanh_pho = $_GET['thanh_pho'] ?? $_POST['thanh_pho'] ?? null;
    $search = $_GET['search'] ?? $_POST['search'] ?? null;
    
    // Lấy danh sách rạp đã lọc
    if ($thanh_pho || $search) {
        $danhSachRap = $this->rapModel->filterRap($thanh_pho, $search);
    } else {
        $danhSachRap = $this->rapModel->getAllRap();
    }
    
    // Lấy danh sách thành phố để hiển thị trong dropdown
    $cities = $this->rapModel->getDistinctCities();
    
    // Lấy số lượng phòng cho mỗi rạp
    foreach ($danhSachRap as $key => $rap) {
        $danhSachRap[$key]['so_phong'] = $this->phongModel->countPhongByRapId($rap['ma_rap']);
    }
    
    // Lưu các tham số lọc để hiển thị lại trong form
    $filter_params = [
        'thanh_pho' => $thanh_pho,
        'search' => $search
    ];
    
    // Tải view
    require_once __DIR__ . '/../views/rap_view.php';
}

    /**
     * Hiển thị form THÊM MỚI (inline)
     */
    public function create() {
        // Lấy danh sách rạp để hiển thị bên dưới form
        $danhSachRap = $this->rapModel->getAllRap();
        foreach ($danhSachRap as $key => $rap) {
            $danhSachRap[$key]['so_phong'] = $this->phongModel->countPhongByRapId($rap['ma_rap']);
        }

        // Đặt cờ (flag) để view biết phải hiển thị form 'create'
        $action = 'create';

        // Tải view
        require_once __DIR__ . '/../views/rap_view.php';
    }

    /**
     * Xử lý lưu trữ rạp MỚI
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $anh_rap_path = $this->handleFileUpload('anh_rap');
                
                $this->rapModel->addRap(
                    $_POST['ten_rap'],
                    $_POST['dia_chi'],
                    $_POST['thanh_pho'],
                    $_POST['SDT'],
                    $anh_rap_path,
                    $_POST['mo_ta_rap']
                );
                header("Location: index.php?controller=adminRap&action=index&status=add_success");
            } catch (Exception $e) {
                header("Location: index.php?controller=adminRap&action=index&status=add_error");
            }
        } else {
            header('Location: index.php?controller=adminRap&action=create');
        }
    }

    /**
     * Hiển thị form SỬA (inline)
     */
    public function edit() {
        $edit_id = $_GET['id'] ?? null;
        if (!$edit_id) {
            header('Location: index.php?controller=adminRap&action=index');
            exit;
        }

        // Lấy danh sách rạp để hiển thị (bao gồm cả dòng đang sửa)
        $danhSachRap = $this->rapModel->getAllRap();
        foreach ($danhSachRap as $key => $rap) {
            $danhSachRap[$key]['so_phong'] = $this->phongModel->countPhongByRapId($rap['ma_rap']);
        }
        
        // Lấy thông tin chi tiết của rạp cần sửa để fill vào form
        $rap_to_edit = $this->rapModel->getRapById($edit_id);
        if (!$rap_to_edit) {
             header('Location: index.php?controller=adminRap&action=index&status=not_found');
             exit;
        }

        // Tải view
        require_once __DIR__ . '/../views/rap_view.php';
    }

    /**
     * Xử lý CẬP NHẬT rạp
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $ma_rap = $_POST['ma_rap'] ?? null;
                if (!$ma_rap) throw new Exception("Thiếu ID rạp.");

                // Xử lý upload ảnh mới (nếu có)
                $anh_rap_path = $this->handleFileUpload('anh_rap', $ma_rap . '_');
                
                $this->rapModel->updateRap(
                    $ma_rap,
                    $_POST['ten_rap'],
                    $_POST['dia_chi'],
                    $_POST['thanh_pho'],
                    $_POST['SDT'],
                    $anh_rap_path, // Sẽ là null nếu không có file mới
                    $_POST['mo_ta_rap']
                );
                header("Location: index.php?controller=adminRap&action=index&status=update_success");
            } catch (Exception $e) {
                header("Location: index.php?controller=adminRap&action=index&status=update_error");
            }
        }
    }

    /**
     * Xử lý XÓA một rạp
     */
    public function destroy() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            if ($this->rapModel->deleteRap($id)) {
                header("Location: index.php?controller=adminRap&action=index&status=delete_success");
            } else {
                // Lỗi do ràng buộc khóa ngoại
                header("Location: index.php?controller=adminRap&action=index&status=delete_error_fk");
            }
        } else {
            header('Location: index.php?controller=adminRap&action=index');
        }
    }
}
?>