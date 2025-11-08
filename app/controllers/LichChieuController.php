<?php
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
        $selected_date = $_GET['ngay'] ?? '2025-10-25';
        $selected_rap_id = $_GET['ma_rap'] ?? '1';

        $dateListRaw = [
            ['sql' => '2025-10-25', 'display' => '25/10', 'weekday' => 'T7'],
            ['sql' => '2025-10-26', 'display' => '26/10', 'weekday' => 'CN'],
            ['sql' => '2025-10-27', 'display' => '27/10', 'weekday' => 'T2'],
            ['sql' => '2025-10-28', 'display' => '28/10', 'weekday' => 'T3'],
            ['sql' => '2025-10-29', 'display' => '29/10', 'weekday' => 'T4'],
            ['sql' => '2025-10-30', 'display' => '30/10', 'weekday' => 'T5'],
        ];

        $fixedDateList = [];
        foreach ($dateListRaw as $date_item) {
            $date_item['link'] = 'index.php?controller=lichchieu&action=index'
                               . '&ngay=' . $date_item['sql']
                               . '&ma_rap=' . urlencode($selected_rap_id);
            $fixedDateList[] = $date_item;
        }

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

        require_once __DIR__ . '/../views/LichChieu_view.php';
    }
}
?>
