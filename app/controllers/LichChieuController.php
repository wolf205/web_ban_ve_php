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

    /**
     * KHỞI TẠO CONTROLLER
     * - Thiết lập kết nối database
     * - Khởi tạo các model cần thiết
     * - Kiểm tra kết nối thành công
     */
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        if ($this->db === null) throw new \Exception("Không thể kết nối đến CSDL.");

        $this->lichChieuModel = new LichChieuModel($this->db);
        $this->phimModel = new PhimModel($this->db);
        $this->gheSuatChieuModel = new GheSuatChieuModel($this->db);
        $this->rapModel = new RapModel($this->db);
    }

    /**
     * ACTION INDEX - HIỂN THỊ TRANG LỊCH CHIẾU
     * - Xử lý tham số ngày và rạp từ URL
     * - Tạo danh sách ngày cho date selector
     * - Lấy và tổ chức dữ liệu lịch chiếu
     * - Truyền dữ liệu đến view
     */
    public function index() {
        // =================================================================
        // 1. XỬ LÝ THAM SỐ ĐẦU VÀO
        // =================================================================
        
        // LẤY NGÀY ĐƯỢC CHỌN TỪ THAM SỐ GET, MẶC ĐỊNH LÀ NGÀY HÔM NAY
        $selected_date = $_GET['ngay'] ?? date('Y-m-d');
        
        // LẤY MÃ RẠP ĐƯỢC CHỌN, MẶC ĐỊNH LÀ '1'
        $selected_rap_id = $_GET['ma_rap'] ?? '1';

        // =================================================================
        // 2. TẠO DANH SÁCH NGÀY CHO DATE SELECTOR
        // =================================================================
        
        $dateListRaw = [];
        
        // TÊN CÁC THỨ TRONG TIẾNG VIỆT (0=CN, 1=T2, ..., 6=T7)
        $weekdays = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];
        
        // NGÀY HIỆN TẠI ĐỂ LÀM ĐIỂM BẮT ĐẦU
        $currentDate = new DateTime();
        
        // TẠO 6 NGÀY KỂ TỪ HÔM NAY (0 ĐẾN 5)
        for ($i = 0; $i < 6; $i++) {
            $date = clone $currentDate; // CLONE ĐỂ KHÔNG ẢNH HƯỞNG ĐẾN $currentDate
            $date->modify("+$i days"); // THÊM i NGÀY VÀO NGÀY HIỆN TẠI
            
            // ĐỊNH DẠNG CHO CÁC MỤC ĐÍCH KHÁC NHAU
            $sqlDate = $date->format('Y-m-d');     // CHO SQL QUERY (YYYY-MM-DD)
            $displayDate = $date->format('d/m');   // CHO HIỂN THỊ (DD/MM)
            $weekdayIndex = (int)$date->format('w'); // 0=CN, 1=T2, ..., 6=T7
            
            // THÊM VÀO MẢNG DANH SÁCH NGÀY
            $dateListRaw[] = [
                'sql' => $sqlDate,          // CHO DATABASE
                'display' => $displayDate,  // CHO HIỂN THỊ
                'weekday' => $weekdays[$weekdayIndex] // TÊN THỨ
            ];
        }

        // =================================================================
        // 3. CHUẨN BỊ DỮ LIỆU CHO DATE SELECTOR VIEW
        // =================================================================
        
        $fixedDateList = [];
        foreach ($dateListRaw as $date_item) {
            // TẠO LINK CHO MỖI NGÀY, GIỮ LẠI MÃ RẠP HIỆN TẠI
            $date_item['link'] = 'index.php?controller=lichchieu&action=index'
                     . '&ngay=' . $date_item['sql']           // NGÀY
                     . '&ma_rap=' . urlencode($selected_rap_id); // RẠP

            // KIỂM TRA NGÀY NÀY CÓ ĐANG ĐƯỢC CHỌN KHÔNG
            $date_item['active'] = ($date_item['sql'] == $selected_date);
            
            // VĂN BẢN HIỂN THỊ: "DD/MM - TThứ"
            $date_item['text'] = $date_item['display'] . " - " . $date_item['weekday'];

            $fixedDateList[] = $date_item;
        }

        // =================================================================
        // 4. LẤY VÀ XỬ LÝ DỮ LIỆU LỊCH CHIẾU
        // =================================================================
        
        // LẤY TẤT CẢ SUẤT CHIẾU CHO NGÀY VÀ RẠP ĐƯỢC CHỌN
        $allShowtimes = $this->lichChieuModel->getLichChieu($selected_date, $selected_rap_id);
        
        // MẢNG ĐỂ NHÓM SUẤT CHIẾU THEO PHIM
        $moviesData = [];

        foreach ($allShowtimes as $showtime) {
            $ma_phim = $showtime['ma_phim'];
            
            // NẾU PHIM NÀY CHƯA CÓ TRONG MẢNG, THÊM THÔNG TIN PHIM
            if (!isset($moviesData[$ma_phim])) {
                $phimInfo = $this->phimModel->getPhimById($ma_phim);
                if (!$phimInfo) continue; // BỎ QUA NẾU KHÔNG TÌM THẤY PHIM
                
                $moviesData[$ma_phim] = $phimInfo;
                $moviesData[$ma_phim]['showtimes'] = []; // KHỞI TẠO MẢNG SUẤT CHIẾU
            }

            $ma_suat_chieu = $showtime['ma_suat_chieu'];
            
            // ĐẾM SỐ GHẾ TRỐNG CHO SUẤT CHIẾU NÀY
            $so_ghe_trong = $this->gheSuatChieuModel->countGheTrong($ma_suat_chieu);
            
            // THÊM THÔNG TIN GHẾ TRỐNG VÀO SUẤT CHIẾU
            $showtime['so_ghe_trong'] = $so_ghe_trong;
            
            // THÊM SUẤT CHIẾU VÀO MẢNG SUẤT CHIẾU CỦA PHIM
            $moviesData[$ma_phim]['showtimes'][] = $showtime;
        }

        // =================================================================
        // 5. TRUYỀN DỮ LIỆU ĐẾN VIEW
        // =================================================================
        
        require_once __DIR__ . '/../views/khach_hang/LichChieu_view.php';
    }
}
?>