<?php
session_start();
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/LichChieuModel.php';
require_once __DIR__ . '/../models/PhimModel.php';
require_once __DIR__ . '/../models/GheSuatChieuModel.php';
require_once __DIR__ . '/../models/RapModel.php';

class LichChieuController {
    private $db;
    private $lichChieuModel;
    private $phimModel;
    private $gheSuatChieuModel;
    private $rapModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        if ($this->db === null) throw new \Exception("Không thể kết nối đến CSDL.");

        $this->lichChieuModel = new LichChieuModel($this->db);
        $this->phimModel = new PhimModel($this->db);
        $this->gheSuatChieuModel = new GheSuatChieuModel($this->db);
        $this->rapModel = new RapModel($this->db);
    }

    public function index() {
        // Lấy ngày được chọn từ GET, mặc định là ngày hôm nay
        $selected_date = $_GET['ngay'] ?? date('Y-m-d');
        $selected_rap_id = $_GET['ma_rap'] ?? '1';

        // Tạo danh sách 6 ngày kể từ hôm nay
        $dateListRaw = [];
        
        // Tên các thứ trong tiếng Việt
        $weekdays = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];
        
        // Ngày hiện tại
        $currentDate = new DateTime();
        
        // Tạo 6 ngày kể từ hôm nay
        for ($i = 0; $i < 6; $i++) {
            $date = clone $currentDate;
            $date->modify("+$i days");
            
            $sqlDate = $date->format('Y-m-d');
            $displayDate = $date->format('d/m');
            $weekdayIndex = (int)$date->format('w'); // 0=CN, 1=T2, ..., 6=T7
            
            $dateListRaw[] = [
                'sql' => $sqlDate,
                'display' => $displayDate,
                'weekday' => $weekdays[$weekdayIndex]
            ];
        }

        $fixedDateList = [];
        foreach ($dateListRaw as $date_item) {
            $date_item['link'] = 'index.php?controller=lichchieu&action=index'
                               . '&ngay=' . $date_item['sql']
                               . '&ma_rap=' . urlencode($selected_rap_id);
            $fixedDateList[] = $date_item;
        }

        // Lấy lịch chiếu cho ngày được chọn
        $allShowtimes = $this->lichChieuModel->getLichChieu($selected_date, $selected_rap_id);
        $moviesData = [];

        foreach ($allShowtimes as $showtime) {
            $ma_phim = $showtime['ma_phim'];
            if (!isset($moviesData[$ma_phim])) {
                $phimInfo = $this->phimModel->getPhimById($ma_phim);
                if (!$phimInfo) continue;
                $moviesData[$ma_phim] = $phimInfo;
                $moviesData[$ma_phim]['showtimes'] = [];
            }

            $ma_suat_chieu = $showtime['ma_suat_chieu'];
            $so_ghe_trong = $this->gheSuatChieuModel->countGheTrong($ma_suat_chieu);
            $showtime['so_ghe_trong'] = $so_ghe_trong;
            $moviesData[$ma_phim]['showtimes'][] = $showtime;
        }

        $all_raps = $this->rapModel->getAllRap();
        $rap = $this->rapModel->getRapById($selected_rap_id);
        $header_rap_link_template = 'index.php?controller=lichchieu&action=index&ngay=' 
                                  . urlencode($selected_date) 
                                  . '&ma_rap=__MA_RAP__';

        require_once __DIR__ . '/../views/khach_hang/LichChieu_view.php';
    }
}
?>