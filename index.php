<?php
// Hiển thị lỗi trong môi trường phát triển
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Lấy tên controller và action từ URL (hoặc đặt mặc định)
$controllerName = ucfirst(strtolower($_REQUEST['controller'] ?? 'Lichchieu')) . 'Controller';
$actionName     = $_REQUEST['action'] ?? 'index';

// Đường dẫn đến file controller
$controllerPath = __DIR__ . "/app/controllers/{$controllerName}.php";

// Kiểm tra controller có tồn tại không
if (!file_exists($controllerPath)) {
    http_response_code(404);
    echo "<h1>404 - Không tìm thấy controller</h1>";
    echo "<p>File: {$controllerPath}</p>";
    exit;
}

// Gọi file controller
require_once $controllerPath;

// Kiểm tra class có tồn tại không
if (!class_exists($controllerName)) {
    echo "<h1>Lỗi: Controller '$controllerName' không tồn tại trong file.</h1>";
    exit;
}

// Tạo đối tượng controller
$controllerObject = new $controllerName();

// Gọi action tương ứng
if (method_exists($controllerObject, $actionName)) {
    $controllerObject->$actionName();
} else {
    echo "<h1>404 - Action không tồn tại</h1>";
    echo "<p>Controller '$controllerName' không có phương thức '$actionName'.</p>";
}
