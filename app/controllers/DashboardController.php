<?php
// app/controllers/DashboardController.php
session_start();

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/DashboardModel.php';

class DashboardController {
    private $db;
    private $dashboardModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        if ($this->db === null) throw new \Exception("Không thể kết nối đến CSDL.");
        
        $this->dashboardModel = new DashboardModel($this->db);
    }

    /**
     * Hiển thị dashboard tổng quan
     */
    public function index() {
        // Nhận tham số filter từ URL
        $filter_time = $_GET['filter-time'] ?? 'today';
        $filter_cinema = $_GET['filter-cinema'] ?? 'all';
        $date_picker = $_GET['date-picker'] ?? date('Y-m-d');
        $movie_select = $_GET['movie-select'] ?? 'all';
        $room_select = $_GET['room-select'] ?? 'all';
        
        // Tính toán khoảng thời gian
        $time_range = $this->calculateTimeRange($filter_time);
        
        // Tính toán hiển thị khoảng thời gian
        $time_range_display = $this->getTimeRangeDisplay($time_range);
        
        // Lấy danh sách filter cho dropdown
        $cinemas_filter = $this->dashboardModel->getCinemasForFilter();
        $movies_filter = $this->dashboardModel->getMoviesForFilter();
        $rooms_filter = $this->dashboardModel->getRoomsForFilter();
        
        // Lấy thống kê chính
        $stats_raw = [
            'total_tickets' => $this->dashboardModel->getTotalTickets($time_range, $filter_cinema),
            'total_revenue' => $this->dashboardModel->getRevenueByTimeRange($time_range, $filter_cinema),
            'fill_rate' => $this->dashboardModel->getSeatFillRate($time_range, $filter_cinema),
            'movies_count' => $this->dashboardModel->getMoviesCount(),
            'today_revenue' => $this->dashboardModel->getRevenueByDay(date('Y-m-d'), $filter_cinema)
        ];
        
        // Xử lý dữ liệu thống kê để gửi sang view
        $stats = [
            'total_tickets' => $stats_raw['total_tickets']['total'] ?? 0,
            'total_revenue' => $stats_raw['total_revenue']['revenue'] ?? 0,
            'fill_rate' => $stats_raw['fill_rate']['fill_rate'] ?? 0,
            'movies_count' => $stats_raw['movies_count']['total'] ?? 0
        ];
        
        // Lấy dữ liệu biểu đồ
        $revenue_chart = $this->dashboardModel->getLast7DaysRevenue($filter_cinema);
        $genre_chart = $this->dashboardModel->getGenreDistribution($time_range, $filter_cinema);
        
        // Lấy top 5 phim
        $top_movies = $this->dashboardModel->getTopMovies($time_range, $filter_cinema);
        
        // Lấy lịch chiếu
        $showtimes = $this->dashboardModel->getShowtimesWithFilter(
            $date_picker, 
            $movie_select, 
            $room_select, 
            $filter_cinema
        );
        
        // Chuẩn bị dữ liệu cho view
        $data = [
            'filter_time' => $filter_time,
            'filter_cinema' => $filter_cinema,
            'date_picker' => $date_picker,
            'movie_select' => $movie_select,
            'room_select' => $room_select,
            'time_range' => $time_range,
            'stats' => $stats,
            'revenue_chart' => $revenue_chart,
            'genre_chart' => $genre_chart,
            'top_movies' => $top_movies,
            'showtimes' => $showtimes,
            'cinemas_filter' => $cinemas_filter,
            'movies_filter' => $movies_filter,
            'rooms_filter' => $rooms_filter,
            'time_range_display' => $time_range_display
        ];
        
        // Extract biến để sử dụng trong view
        extract($data);
        
        // Tải view
        require_once __DIR__ . '/../views/admin/dashboard_view.php';
    }
    
    /**
     * API endpoint cho AJAX requests
     */
    public function api() {
        header('Content-Type: application/json');
        
        $action = $_GET['action'] ?? '';
        $filter_cinema = $_GET['cinema_id'] ?? 'all';
        $filter_time = $_GET['time_range'] ?? 'today';
        
        $time_range = $this->calculateTimeRange($filter_time);
        
        $response = [];
        
        try {
            switch($action) {
                case 'stats':
                    $stats_raw = [
                        'total_tickets' => $this->dashboardModel->getTotalTickets($time_range, $filter_cinema),
                        'total_revenue' => $this->dashboardModel->getRevenueByTimeRange($time_range, $filter_cinema),
                        'fill_rate' => $this->dashboardModel->getSeatFillRate($time_range, $filter_cinema),
                        'movies_count' => $this->dashboardModel->getMoviesCount()
                    ];
                    
                    $response = [
                        'total_tickets' => $stats_raw['total_tickets']['total'] ?? 0,
                        'total_revenue' => $stats_raw['total_revenue']['revenue'] ?? 0,
                        'fill_rate' => $stats_raw['fill_rate']['fill_rate'] ?? 0,
                        'movies_count' => $stats_raw['movies_count']['total'] ?? 0
                    ];
                    break;
                    
                case 'revenue_chart':
                    $response = $this->dashboardModel->getLast7DaysRevenue($filter_cinema);
                    break;
                    
                case 'genre_chart':
                    $response = $this->dashboardModel->getGenreDistribution($time_range, $filter_cinema);
                    break;
                    
                case 'top_movies':
                    $response = $this->dashboardModel->getTopMovies($time_range, $filter_cinema);
                    break;
                    
                case 'showtimes':
                    $date = $_GET['date'] ?? date('Y-m-d');
                    $movie = $_GET['movie'] ?? 'all';
                    $room = $_GET['room'] ?? 'all';
                    $response = $this->dashboardModel->getShowtimesWithFilter($date, $movie, $room, $filter_cinema);
                    break;
                    
                default:
                    $response = ['error' => 'Action không hợp lệ'];
                    http_response_code(400);
            }
        } catch (Exception $e) {
            $response = ['error' => $e->getMessage()];
            http_response_code(500);
        }
        
        echo json_encode($response);
        exit;
    }
    
    /**
     * Tính toán khoảng thời gian từ filter
     */
    private function calculateTimeRange($filter_time) {
        $today = date('Y-m-d');
        
        switch($filter_time) {
            case 'today':
                return [
                    'start_date' => $today,
                    'end_date' => $today
                ];
                
            case 'week':
                return [
                    'start_date' => date('Y-m-d', strtotime('-6 days')),
                    'end_date' => $today
                ];
                
            case 'month':
                return [
                    'start_date' => date('Y-m-01'),
                    'end_date' => date('Y-m-t')
                ];
                
            case 'custom_date':
                $start = $_GET['custom_start_date'] ?? $today;
                $end = $_GET['custom_end_date'] ?? $today;
                return [
                    'start_date' => $start,
                    'end_date' => $end
                ];
                
            case 'custom_month':
                $month = $_GET['custom_month'] ?? date('Y-m');
                return [
                    'start_date' => $month . '-01',
                    'end_date' => date('Y-m-t', strtotime($month))
                ];
                
            default:
                return [
                    'start_date' => $today,
                    'end_date' => $today
                ];
        }
    }
    
    /**
     * Format hiển thị khoảng thời gian
     */
    private function getTimeRangeDisplay($time_range) {
        if (!isset($time_range['start_date']) || !isset($time_range['end_date'])) {
            return date('d/m/Y');
        }
        
        $start = date('d/m/Y', strtotime($time_range['start_date']));
        $end = date('d/m/Y', strtotime($time_range['end_date']));
        
        if ($start === $end) {
            return $start;
        }
        
        return $start . ' - ' . $end;
    }
}
?>