<?php
// Lấy trang cần hiển thị
$page = $_GET['page'] ?? 'rap'; // mặc định vào trang Rạp

switch ($page) {
    case 'rap':
        require_once './app/controllers/RapController.php';
        $controller = new RapController();
        $controller->index();
        break;

    case 'lichchieu':
        require_once './app/controllers/LichChieuController.php';
        $controller = new LichChieuController();
        $controller->theoRap();
        break;

    default:
        echo "404 - Trang không tồn tại";
        break;
}
