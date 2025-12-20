<?php
// app/controllers/AdminComboController.php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/ComboModel.php';

class AdminComboController
{
    private $db;
    private $comboModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->comboModel = new ComboModel($this->db);
    }

    // --- HIỂN THỊ DANH SÁCH ---
    public function index()
    {
        // Lấy tham số lọc từ URL
        $filters = [
            'search'    => $_GET['search'] ?? '',
            'min_price' => $_GET['min_price'] ?? '',
            'max_price' => $_GET['max_price'] ?? ''
        ];

        $listCombo = $this->comboModel->getAllCombo($filters);
        require_once __DIR__ . '/../views/admin/combo_view.php';
    }

    // --- HIỂN THỊ FORM THÊM ---
    public function create() {
        $listCombo = $this->comboModel->getAllCombo(); // Để hiển thị list bên dưới form
        require_once __DIR__ . '/../views/admin/combo_view.php';
    }

    // --- XỬ LÝ LƯU (STORE) ---
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ten_combo = $_POST['ten_combo'];
            $gia_tien = $_POST['gia_tien'];
            $mo_ta = $_POST['mo_ta'];
            
            // Xử lý upload ảnh
            $anh_minh_hoa = "";
            if (isset($_FILES['anh_minh_hoa']) && $_FILES['anh_minh_hoa']['error'] == 0) {
                $target_dir = "publics/img/combo/";
                // Tạo thư mục nếu chưa tồn tại
                if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
                
                $file_name = time() . '_' . basename($_FILES["anh_minh_hoa"]["name"]);
                $target_file = $target_dir . $file_name;
                
                if (move_uploaded_file($_FILES["anh_minh_hoa"]["tmp_name"], $target_file)) {
                    $anh_minh_hoa = $target_file;
                }
            }

            $data = [
                'ten_combo' => $ten_combo,
                'gia_tien' => $gia_tien,
                'mo_ta' => $mo_ta,
                'anh_minh_hoa' => $anh_minh_hoa
            ];

            if ($this->comboModel->addCombo($data)) {
                header("Location: index.php?controller=adminCombo&action=index&status=success");
            } else {
                header("Location: index.php?controller=adminCombo&action=index&status=error");
            }
        }
    }

    // --- HIỂN THỊ FORM SỬA ---
    public function edit() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $combo_to_edit = $this->comboModel->getComboById($id);
            $listCombo = $this->comboModel->getAllCombo();
            require_once __DIR__ . '/../views/admin/combo_view.php';
        } else {
            header("Location: index.php?controller=adminCombo&action=index");
        }
    }

    // --- XỬ LÝ CẬP NHẬT ---
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ma_combo = $_POST['ma_combo'];
            $ten_combo = $_POST['ten_combo'];
            $gia_tien = $_POST['gia_tien'];
            $mo_ta = $_POST['mo_ta'];
            
            // Xử lý upload ảnh
            $anh_minh_hoa = "";
            if (isset($_FILES['anh_minh_hoa']) && $_FILES['anh_minh_hoa']['error'] == 0) {
                $target_dir = "publics/img/combo/";
                // Tạo thư mục nếu chưa tồn tại
                if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
                
                $file_name = time() . '_' . basename($_FILES["anh_minh_hoa"]["name"]);
                $target_file = $target_dir . $file_name;
                
                if (move_uploaded_file($_FILES["anh_minh_hoa"]["tmp_name"], $target_file)) {
                    $anh_minh_hoa = $target_file;
                }
            }

            $data = [
                'ma_combo' => $ma_combo,
                'ten_combo' => $ten_combo,
                'gia_tien' => $gia_tien,
                'mo_ta' => $mo_ta,
                'anh_minh_hoa' => $anh_minh_hoa 
            ];

            if ($this->comboModel->updateCombo($data)) {
                header("Location: index.php?controller=adminCombo&action=index&status=success");
            } else {
                header("Location: index.php?controller=adminCombo&action=index&status=error");
            }
        }
    }

    // --- XÓA COMBO ---
    public function delete() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            if ($this->comboModel->deleteCombo($id)) {
                header("Location: index.php?controller=adminCombo&action=index&status=success");
            } else {
                header("Location: index.php?controller=adminCombo&action=index&status=error");
            }
        }
    }
}
?>