<?php
class DashboardModel {
    private $conn;
    private $table_movies = 'phim';
    private $table_showtimes = 'suat_chieu';
    private $table_tickets = 've';
    private $table_rooms = 'phong';
    private $table_cinemas = 'rap';
    private $table_customers = 'khach_hang';
    private $table_seats = 'ghe';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tổng số vé bán theo khoảng thời gian và rạp
    public function getTotalTickets($time_range, $cinema_id = 'all') {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_tickets . " v
                  JOIN " . $this->table_showtimes . " sc ON v.ma_suat_chieu = sc.ma_suat_chieu
                  JOIN " . $this->table_rooms . " p ON sc.ma_phong = p.ma_phong
                  WHERE sc.ngay_chieu BETWEEN :start_date AND :end_date";
        
        if ($cinema_id != 'all') {
            $query .= " AND p.ma_rap = :cinema_id";
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':start_date', $time_range['start_date']);
        $stmt->bindParam(':end_date', $time_range['end_date']);
        
        if ($cinema_id != 'all') {
            $stmt->bindParam(':cinema_id', $cinema_id);
        }
        
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy doanh thu theo ngày
    public function getRevenueByDay($date, $cinema_id = 'all') {
        $query = "SELECT COALESCE(SUM(v.gia), 0) as revenue FROM " . $this->table_tickets . " v
                  JOIN " . $this->table_showtimes . " sc ON v.ma_suat_chieu = sc.ma_suat_chieu
                  JOIN " . $this->table_rooms . " p ON sc.ma_phong = p.ma_phong
                  WHERE DATE(sc.ngay_chieu) = :date";
        
        if ($cinema_id != 'all') {
            $query .= " AND p.ma_rap = :cinema_id";
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':date', $date);
        
        if ($cinema_id != 'all') {
            $stmt->bindParam(':cinema_id', $cinema_id);
        }
        
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy doanh thu theo khoảng thời gian
    public function getRevenueByTimeRange($time_range, $cinema_id = 'all') {
        $query = "SELECT COALESCE(SUM(v.gia), 0) as revenue FROM " . $this->table_tickets . " v
                  JOIN " . $this->table_showtimes . " sc ON v.ma_suat_chieu = sc.ma_suat_chieu
                  JOIN " . $this->table_rooms . " p ON sc.ma_phong = p.ma_phong
                  WHERE sc.ngay_chieu BETWEEN :start_date AND :end_date";
        
        if ($cinema_id != 'all') {
            $query .= " AND p.ma_rap = :cinema_id";
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':start_date', $time_range['start_date']);
        $stmt->bindParam(':end_date', $time_range['end_date']);
        
        if ($cinema_id != 'all') {
            $stmt->bindParam(':cinema_id', $cinema_id);
        }
        
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy tỷ lệ lấp đầy ghế - ĐÃ SỬA LOGIC
    public function getSeatFillRate($time_range, $cinema_id = 'all') {
        $query = "SELECT 
                    ROUND(
                        COALESCE(
                            (COUNT(DISTINCT v.ma_ve) * 100.0 / 
                            NULLIF(
                                (SELECT COUNT(DISTINCT g.ma_ghe) 
                                 FROM " . $this->table_seats . " g 
                                 JOIN " . $this->table_showtimes . " sc2 ON g.ma_phong = sc2.ma_phong
                                 WHERE sc2.ngay_chieu BETWEEN :start_date AND :end_date
                                 " . ($cinema_id != 'all' ? " AND g.ma_phong IN (SELECT ma_phong FROM " . $this->table_rooms . " WHERE ma_rap = :cinema_id)" : "") . "
                                ), 0)
                            ), 0
                        ), 1
                    ) as fill_rate
                  FROM " . $this->table_tickets . " v
                  JOIN " . $this->table_showtimes . " sc ON v.ma_suat_chieu = sc.ma_suat_chieu
                  JOIN " . $this->table_rooms . " p ON sc.ma_phong = p.ma_phong
                  WHERE sc.ngay_chieu BETWEEN :start_date AND :end_date";
        
        if ($cinema_id != 'all') {
            $query .= " AND p.ma_rap = :cinema_id";
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':start_date', $time_range['start_date']);
        $stmt->bindParam(':end_date', $time_range['end_date']);
        
        if ($cinema_id != 'all') {
            $stmt->bindParam(':cinema_id', $cinema_id);
        }
        
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy số phim đang chiếu
    public function getMoviesCount() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_movies . " 
                  WHERE ngay_khoi_chieu <= CURDATE()";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy doanh thu 7 ngày gần nhất
    public function getLast7DaysRevenue($cinema_id = 'all') {
        $query = "SELECT DATE(sc.ngay_chieu) as date, COALESCE(SUM(v.gia), 0) as revenue 
                  FROM " . $this->table_tickets . " v
                  JOIN " . $this->table_showtimes . " sc ON v.ma_suat_chieu = sc.ma_suat_chieu
                  JOIN " . $this->table_rooms . " p ON sc.ma_phong = p.ma_phong
                  WHERE sc.ngay_chieu >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
        
        if ($cinema_id != 'all') {
            $query .= " AND p.ma_rap = :cinema_id";
        }
        
        $query .= " GROUP BY DATE(sc.ngay_chieu)
                  ORDER BY date ASC
                  LIMIT 7";
                  
        $stmt = $this->conn->prepare($query);
        
        if ($cinema_id != 'all') {
            $stmt->bindParam(':cinema_id', $cinema_id);
        }
        
        $stmt->execute();
        
        // Đảm bảo luôn có 7 ngày dữ liệu
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $sevenDaysData = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $found = false;
            
            foreach ($results as $row) {
                if ($row['date'] == $date) {
                    $sevenDaysData[] = $row;
                    $found = true;
                    break;
                }
            }
            
            if (!$found) {
                $sevenDaysData[] = ['date' => $date, 'revenue' => 0];
            }
        }
        
        return $sevenDaysData;
    }

    // Lấy phân bố thể loại phim
    public function getGenreDistribution($time_range = null, $cinema_id = 'all') {
        $query = "SELECT p.the_loai, COUNT(DISTINCT p.ma_phim) as count 
                  FROM " . $this->table_movies . " p
                  JOIN " . $this->table_showtimes . " sc ON p.ma_phim = sc.ma_phim
                  JOIN " . $this->table_rooms . " r ON sc.ma_phong = r.ma_phong
                  WHERE 1=1";
        
        $params = [];
        
        if ($time_range) {
            $query .= " AND sc.ngay_chieu BETWEEN :start_date AND :end_date";
            $params[':start_date'] = $time_range['start_date'];
            $params[':end_date'] = $time_range['end_date'];
        }
        
        if ($cinema_id != 'all') {
            $query .= " AND r.ma_rap = :cinema_id";
            $params[':cinema_id'] = $cinema_id;
        }
        
        $query .= " GROUP BY p.the_loai";
        
        $stmt = $this->conn->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy top 5 phim bán chạy - ĐÃ SỬA THÊM XỬ LÝ RỖNG
    public function getTopMovies($time_range, $cinema_id = 'all') {
        $query = "SELECT p.ma_phim, p.ten_phim, 
                           COUNT(v.ma_ve) as sold_tickets, 
                           COALESCE(SUM(v.gia), 0) as revenue
                  FROM " . $this->table_movies . " p
                  JOIN " . $this->table_showtimes . " sc ON p.ma_phim = sc.ma_phim
                  JOIN " . $this->table_rooms . " r ON sc.ma_phong = r.ma_phong
                  LEFT JOIN " . $this->table_tickets . " v ON sc.ma_suat_chieu = v.ma_suat_chieu
                  WHERE sc.ngay_chieu BETWEEN :start_date AND :end_date";
        
        if ($cinema_id != 'all') {
            $query .= " AND r.ma_rap = :cinema_id";
        }
        
        $query .= " GROUP BY p.ma_phim
                  HAVING revenue > 0
                  ORDER BY revenue DESC
                  LIMIT 5";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':start_date', $time_range['start_date']);
        $stmt->bindParam(':end_date', $time_range['end_date']);
        
        if ($cinema_id != 'all') {
            $stmt->bindParam(':cinema_id', $cinema_id);
        }
        
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Nếu không có dữ liệu, trả về mảng rỗng
        return $results ?: [];
    }

    // Lấy lịch chiếu với filter
    public function getShowtimesWithFilter($date = null, $movie_id = null, $room_id = null, $cinema_id = 'all') {
        $query = "SELECT p.ten_phim, ph.ten_phong, r.ten_rap, sc.gio_bat_dau, 
                           COUNT(v.ma_ve) as sold_tickets,
                           COALESCE(SUM(v.gia), 0) as revenue,
                           ROUND(
                               COALESCE(
                                   (COUNT(v.ma_ve) * 100.0 / 
                                   NULLIF((SELECT COUNT(*) FROM " . $this->table_seats . " WHERE ma_phong = ph.ma_phong), 0)
                                   ), 0
                               ), 1
                           ) as fill_rate
                  FROM " . $this->table_showtimes . " sc
                  JOIN " . $this->table_movies . " p ON sc.ma_phim = p.ma_phim
                  JOIN " . $this->table_rooms . " ph ON sc.ma_phong = ph.ma_phong
                  JOIN " . $this->table_cinemas . " r ON ph.ma_rap = r.ma_rap
                  LEFT JOIN " . $this->table_tickets . " v ON sc.ma_suat_chieu = v.ma_suat_chieu
                  WHERE 1=1";

        $params = [];

        if ($date) {
            $query .= " AND DATE(sc.ngay_chieu) = :date";
            $params[':date'] = $date;
        }

        if ($movie_id && $movie_id != 'all') {
            $query .= " AND p.ma_phim = :movie_id";
            $params[':movie_id'] = $movie_id;
        }

        if ($room_id && $room_id != 'all') {
            $query .= " AND ph.ma_phong = :room_id";
            $params[':room_id'] = $room_id;
        }

        if ($cinema_id && $cinema_id != 'all') {
            $query .= " AND r.ma_rap = :cinema_id";
            $params[':cinema_id'] = $cinema_id;
        }

        $query .= " GROUP BY sc.ma_suat_chieu
                    ORDER BY sc.gio_bat_dau";

        $stmt = $this->conn->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $results ?: [];
    }

    // Lấy danh sách phim cho filter
    public function getMoviesForFilter() {
        $query = "SELECT ma_phim, ten_phim FROM " . $this->table_movies . " 
                  WHERE ngay_khoi_chieu <= CURDATE()
                  ORDER BY ten_phim";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách phòng cho filter
    public function getRoomsForFilter() {
        $query = "SELECT ma_phong, ten_phong FROM " . $this->table_rooms . " ORDER BY ten_phong";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách rạp cho filter
    public function getCinemasForFilter() {
        $query = "SELECT ma_rap, ten_rap FROM " . $this->table_cinemas . " ORDER BY ten_rap";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>