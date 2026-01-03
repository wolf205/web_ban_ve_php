<?php
session_start();
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/RapModel.php';
require_once __DIR__ . '/../models/DanhGiaModel.php';
require_once __DIR__ . '/../models/PhimModel.php';

class RapController {
    private $db;
    private $rapModel;
    private $danhGiaModel;
    private $phimModel;

    /**
     * KHỞI TẠO CONTROLLER
     * - Thiết lập kết nối database
     * - Khởi tạo các model cần thiết cho quản lý rạp
     * - Kiểm tra kết nối database thành công
     */
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        if ($this->db === null) throw new \Exception("Không thể kết nối đến CSDL.");

        $this->rapModel = new RapModel($this->db);
        $this->danhGiaModel = new DanhGiaModel($this->db);
        $this->phimModel = new PhimModel($this->db);
    }

    /**
     * ACTION INDEX - HIỂN THỊ CHI TIẾT RẠP VÀ XỬ LÝ BÌNH LUẬN
     * - Xử lý tham số rạp từ GET/POST
     * - Xử lý form submit bình luận (nếu có)
     * - Lấy thông tin rạp, danh sách bình luận, phim hot
     * - Truyền dữ liệu đến view
     */
    public function index() {
        // =================================================================
        // 1. XỬ LÝ THAM SỐ ĐẦU VÀO - LẤY MÃ RẠP
        // =================================================================
        
        // LẤY MÃ RẠP TỪ GET HOẶC POST, MẶC ĐỊNH LÀ '1' NẾU KHÔNG CÓ
        $ma_rap = $_GET['ma_rap'] ?? $_POST['ma_rap'] ?? '1';

        // =================================================================
        // 2. KIỂM TRA ĐĂNG NHẬP CỦA NGƯỜI DÙNG
        // =================================================================
        
        // KIỂM TRA XEM KHÁCH HÀNG CÓ ĐĂNG NHẬP KHÔNG
        // DỰA TRÊN SỰ TỒN TẠI CỦA $_SESSION['khach_hang'] VÀ ma_kh
        $isLoggedIn = isset($_SESSION['khach_hang']) && isset($_SESSION['khach_hang']['ma_kh']);
        
        // =================================================================
        // 3. XỬ LÝ FORM BÌNH LUẬN NẾU LÀ POST REQUEST
        // =================================================================
        
        // KIỂM TRA NẾU LÀ PHƯƠNG THỨC POST VÀ CÓ NỘI DUNG BÌNH LUẬN
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['noi_dung'])) {
            // KIỂM TRA 1: NGƯỜI DÙNG CHƯA ĐĂNG NHẬP
            if (!$isLoggedIn) {
                $_SESSION['error'] = "Vui lòng đăng nhập để bình luận!";
                header("Location: index.php?controller=rap&action=index&ma_rap=" . $ma_rap);
                exit;
            } 
            // KIỂM TRA 2: NỘI DUNG BÌNH LUẬN KHÔNG RỖNG SAU KHI TRIM
            else if (!empty(trim($_POST['noi_dung']))) {
                // LẤY THÔNG TIN TỪ SESSION VÀ FORM
                $ma_kh = $_SESSION['khach_hang']['ma_kh']; // MÃ KHÁCH HÀNG TỪ SESSION
                $noi_dung = trim($_POST['noi_dung']);      // NỘI DUNG ĐÃ LÀM SẠCH
                
                // GỌI MODEL ĐỂ CHÈN BÌNH LUẬN VÀO DATABASE
                $result = $this->danhGiaModel->insertDanhGia($ma_rap, $ma_kh, $noi_dung);
                
                // XỬ LÝ KẾT QUẢ VÀ SET FLASH MESSAGE
                if ($result) {
                    $_SESSION['success'] = "Đã gửi bình luận thành công!";
                } else {
                    $_SESSION['error'] = "Có lỗi xảy ra khi gửi bình luận!";
                }
                
                // CHUYỂN HƯỚNG VỀ LẠI TRANG HIỆN TẠI ĐỂ HIỂN THỊ KẾT QUẢ
                header("Location: index.php?controller=rap&action=index&ma_rap=" . $ma_rap);
                exit;
            } 
            // KIỂM TRA 3: NỘI DUNG BÌNH LUẬN RỖNG
            else {
                $_SESSION['error'] = "Vui lòng nhập nội dung bình luận!";
                header("Location: index.php?controller=rap&action=index&ma_rap=" . $ma_rap);
                exit;
            }
        }

        // =================================================================
        // 4. LẤY THÔNG TIN CHI TIẾT CỦA RẠP
        // =================================================================
        
        // LẤY THÔNG TIN RẠP TỪ MODEL THEO MÃ RẠP
        $rap = $this->rapModel->getRapById($ma_rap);
        
        // KIỂM TRA NẾU KHÔNG TÌM THẤY RẠP, HIỂN THỊ THÔNG BÁO LỖI
        if (!$rap) {
            echo "404 - Không tìm thấy rạp.";
            exit;
        }

        // =================================================================
        // 5. LẤY DANH SÁCH BÌNH LUẬN (ĐÁNH GIÁ) CỦA RẠP
        // =================================================================
        
        // LẤY TẤT CẢ BÌNH LUẬN CỦA RẠP TỪ DATABASE
        $danh_gia_list = $this->danhGiaModel->getDanhGiaByRap($ma_rap);
        
        // =================================================================
        // 6. LẤY DANH SÁCH PHIM ĐANG HOT TẠI RẠP NÀY
        // =================================================================
        
        // LẤY TẤT CẢ PHIM HOT TẠI RẠP
        $all_hot_movies = $this->phimModel->getPhimHot($ma_rap);
        
        // CHỈ LẤY 4 PHIM ĐẦU TIÊN ĐỂ HIỂN THỊ TRONG SIDEBAR
        $hot_movies = array_slice($all_hot_movies, 0, 4);
        
        // =================================================================
        // 7. TRUYỀN DỮ LIỆU ĐẾN VIEW
        // =================================================================
        
        // INCLUDE FILE VIEW VÀ TRUYỀN TẤT CẢ BIẾN ĐÃ CHUẨN BỊ
        require __DIR__ . '/../views/khach_hang/Rap.php';
    }
}
?>