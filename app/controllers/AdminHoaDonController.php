<?php
// app/controllers/AdminHoaDonController.php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/HoaDonModel.php';

class AdminHoaDonController
{
    private $db;
    private $hoaDonModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->hoaDonModel = new HoaDonModel($this->db);
    }

    // --- HIỂN THỊ DANH SÁCH ---
    public function index()
    {
        $filters = [
            'search'     => $_GET['search'] ?? '',
            'trang_thai' => $_GET['trang_thai'] ?? '',
            'pttt'       => $_GET['pttt'] ?? '',
            'tu_ngay'    => $_GET['tu_ngay'] ?? '',
            'den_ngay'   => $_GET['den_ngay'] ?? ''
        ];

        $listHoaDon = $this->hoaDonModel->getAllHoaDon($filters);
        require_once __DIR__ . '/../views/admin/hoadon_view.php';
    }

    // --- FORM TẠO (Nếu vẫn muốn giữ tính năng thêm mới) ---
    public function create() {
        $listHoaDon = $this->hoaDonModel->getAllHoaDon();
        require_once __DIR__ . '/../views/admin/hoadon_view.php';
    }

    // --- LƯU MỚI ---
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'ma_kh' => $_POST['ma_khach_hang'],
                'tong_tien' => $_POST['tong_tien'],
                'pttt' => $_POST['phuong_thuc_thanh_toan'],
                'trang_thai' => $_POST['trang_thai']
            ];

            if ($this->hoaDonModel->addHoaDon($data)) {
                header("Location: index.php?controller=adminHoaDon&action=index&status=success");
            } else {
                header("Location: index.php?controller=adminHoaDon&action=index&status=error");
            }
        }
    }

    // --- XEM CHI TIẾT (Thay thế cho EDIT) ---
    // Action này vẫn tên là 'edit' để khớp với URL, nhưng chỉ phục vụ mục đích xem
    public function edit() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            // Lấy chi tiết hóa đơn
            $hd_detail = $this->hoaDonModel->getHoaDonById($id);
            
            // 2. Lấy danh sách Vé
            $listVe = $this->hoaDonModel->getVeByHoaDon($id);

            // 3. Lấy danh sách Combo
            $listCombo = $this->hoaDonModel->getComboByHoaDon($id);

            // Lấy danh sách để hiển thị bảng bên dưới (giữ nguyên layout)
            $listHoaDon = $this->hoaDonModel->getAllHoaDon();
            
            require_once __DIR__ . '/../views/admin/hoadon_view.php';
        } else {
            header("Location: index.php?controller=adminHoaDon&action=index");
        }
    }

    // --- XÓA ---
    public function delete() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            if ($this->hoaDonModel->deleteHoaDon($id)) {
                header("Location: index.php?controller=adminHoaDon&action=index&status=success");
            } else {
                header("Location: index.php?controller=adminHoaDon&action=index&status=error");
            }
        }
    }
}
?>