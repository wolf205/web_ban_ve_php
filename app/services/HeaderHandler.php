<?php
// app/services/HeaderHandler.php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/RapModel.php';

class HeaderHandler {
    private $db;
    private $rapModel;
    private $allRaps;
    private $selectedTheaterId;
    private $selectedTheater;
    
    public function __construct() {
        // Khởi tạo database connection
        $database = new Database();
        $this->db = $database->getConnection();
        
        // Khởi tạo RapModel
        $this->rapModel = new RapModel($this->db);
        
        // Lấy dữ liệu
        $this->initData();
    }
    
    private function initData() {
        // Lấy tất cả rạp
        $this->allRaps = $this->rapModel->getAllRap();
        
        // Xác định rạp đang chọn
        $this->selectedTheaterId = $_GET['ma_rap'] ?? '1';
        $this->selectedTheater = $this->rapModel->getRapById($this->selectedTheaterId);
    }
    
    public function getAllRaps() {
        return $this->allRaps;
    }
    
    public function getSelectedTheater() {
        return $this->selectedTheater;
    }
    
    public function getSelectedTheaterId() {
        return $this->selectedTheaterId;
    }
    
    public function getSelectedTheaterName() {
        if ($this->selectedTheater && isset($this->selectedTheater['ten_rap'])) {
            return $this->selectedTheater['ten_rap'];
        } elseif (!empty($this->allRaps)) {
            // Nếu không có rạp đã chọn, lấy rạp đầu tiên
            return $this->allRaps[0]['ten_rap'];
        }
        return 'Chọn rạp';
    }
    
    public function getCurrentController() {
        return $_GET['controller'] ?? 'phim';
    }
    
    public function getCurrentAction() {
        return $_GET['action'] ?? 'index';
    }
    
    public function generateRapLinkTemplate() {
        $controller = $this->getCurrentController();
        $action = $this->getCurrentAction();
        
        // Lấy tất cả tham số GET hiện tại (trừ ma_rap)
        $allParams = $_GET;
        unset($allParams['ma_rap']);
        
        // Tạo base URL
        $baseUrl = "index.php?";
        
        // Thêm controller và action
        $params = ['controller' => $controller];
        if ($action !== 'index') {
            $params['action'] = $action;
        }
        
        // Thêm các tham số khác (trừ ma_rap)
        foreach ($allParams as $key => $value) {
            if ($key !== 'controller' && $key !== 'action') {
                $params[$key] = $value;
            }
        }
        
        // Tạo chuỗi query
        $queryString = http_build_query($params);
        $baseUrl .= $queryString;
        
        // Thêm placeholder cho mã rạp
        if (!empty($queryString)) {
            $baseUrl .= "&";
        }
        $baseUrl .= "ma_rap=__MA_RAP__";
        
        return $baseUrl;
    }
    
    /**
     * Lấy tất cả dữ liệu header dưới dạng mảng
     */
    public function getHeaderData() {
        return [
            'all_raps' => $this->getAllRaps(),
            'rap' => $this->getSelectedTheater(),
            'header_rap_link_template' => $this->generateRapLinkTemplate(),
            'current_controller' => $this->getCurrentController(),
            'selected_theater_id' => $this->getSelectedTheaterId(),
            'selected_theater_name' => $this->getSelectedTheaterName()
        ];
    }
}