<?php
// app/controllers/ComboThanhToanController.php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/ComboModel.php';
require_once __DIR__ . '/../models/GheSuatChieuModel.php';
require_once __DIR__ . '/../models/RapModel.php';
require_once __DIR__ . '/../models/GheModel.php';
require_once __DIR__ . '/../models/HoaDonModel.php';

class ComboThanhToanController
{
    private $db;
    private $comboModel;
    private $gheSuatChieuModel;
    private $rapModel;
    private $gheModel;
    private $hoaDonModel;

    public function __construct()
    {
        $database   = new Database();
        $this->db   = $database->getConnection();

        $this->comboModel         = new ComboModel($this->db);
        $this->gheSuatChieuModel  = new GheSuatChieuModel($this->db);
        $this->rapModel           = new RapModel($this->db);
        $this->gheModel           = new GheModel($this->db);
        $this->hoaDonModel        = new HoaDonModel($this->db);
    }

    /**
     * Hiển thị màn hình chọn combo & thanh toán
     */
    public function index()
    {
        if (!isset($_GET['ma_suat_chieu'])) {
            die("Lỗi: Thiếu mã suất chiếu.");
        }

        $ma_suat_chieu = (int)$_GET['ma_suat_chieu'];

        /* SESSION */
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        /* DỮ LIỆU CŨ TỪ SESSION */
        $ticketSubtotal     = $_SESSION['ticket_subtotal']     ?? 0;
        $selectedSeatsLabel = $_SESSION['selected_seat_names'] ?? '—';
        $thongTinSuat       = $_SESSION['thong_tin_suat']      ?? null;
        $dsMaGhe = $_SESSION['selected_seat_ids'] ?? [];

        /* 1. LẤY DANH SÁCH COMBO */
        $dsCombo = $this->comboModel->getAllCombo();

        /* 2. LẤY THÔNG TIN SUẤT CHIẾU */
        $thongTinSuat = $this->gheSuatChieuModel->getThongTinSuatChieu($ma_suat_chieu);
        if (!$thongTinSuat) {
            die("Không tìm thấy suất chiếu.");
        }

        /* 3. CHUẨN HÓA ẢNH COMBO */
        foreach ($dsCombo as &$combo) {
            $path = $combo['anh_minh_hoa'] ?? '';
            if (
                $path !== '' &&
                strpos($path, 'http') !== 0 &&
                strpos($path, '../') !== 0
            ) {
                $combo['anh_minh_hoa'] = ltrim($path, '/');
            }
        }
        unset($combo);

        /* 4. BIẾN HIỂN THỊ SUẤT CHIẾU */
        $ten_phim    = $thongTinSuat['ten_phim'];
        $the_loai    = $thongTinSuat['the_loai'];
        $thoi_luong  = (int)$thongTinSuat['thoi_luong'];
        $phan_loai   = 'T' . (int)$thongTinSuat['gioi_han_do_tuoi'];

        $ten_rap     = $thongTinSuat['ten_rap'];
        $ngay_chieu  = date('d/m/Y', strtotime($thongTinSuat['ngay_chieu']));
        $gio_chieu   = date('H:i', strtotime($thongTinSuat['gio_bat_dau']));
        $phong_chieu = $thongTinSuat['ten_phong'];

        $poster     = $thongTinSuat['anh_trailer'];
        $giaVeCoBan = (float)$thongTinSuat['gia_ve_co_ban'];

        /* 5. FILTER RẠP */
        $ma_rap   = (int)$thongTinSuat['ma_rap'];
        $all_raps = $this->rapModel->getAllRap();
        $rap      = $this->rapModel->getRapById($ma_rap);

        /* XỬ LÝ GHẾ THANH TOÁN */
        $dsGheThanhToan = [];
        $tongTienGhe    = 0;

        if (!empty($dsMaGhe)) {
            $dsGhe = $this->gheModel->getGheByIds($dsMaGhe);

            foreach ($dsGhe as $ghe) {
                $donGia = $giaVeCoBan;

                switch (strtoupper($ghe['loai_ghe'])) {
                    case 'VIP':
                        $donGia *= 1.5;
                        break;
                    case 'DOI':
                        $donGia *= 2;
                        break;
                }

                $soLuong   = 1;
                $thanhTien = $soLuong * $donGia;

                $dsGheThanhToan[] = [
                    'ma_ghe'     => $ghe['ma_ghe'],
                    'loai_ghe'   => $ghe['loai_ghe'],
                    'vi_tri'     => $ghe['vi_tri'],
                    'so_luong'   => $soLuong,
                    'don_gia'    => $donGia,
                    'thanh_tien' => $thanhTien
                ];

                $tongTienGhe += $thanhTien;
            }
        }

        // Lưu thông tin ghế vào session để dùng sau
        $_SESSION['ds_ghe_thanh_toan'] = $dsGheThanhToan;
        $_SESSION['ma_suat_chieu'] = $ma_suat_chieu;

        /* LOAD VIEW */
        require_once __DIR__ . '/../views/khach_hang/combo_thanh_toan.php';
    }

    /**
     * Tạo hóa đơn mới với trạng thái "Chưa thanh toán"
     */
    public function createHoaDon()
    {
        header('Content-Type: application/json');
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        try {
            // Lấy dữ liệu từ POST
            $postData = json_decode(file_get_contents('php://input'), true);
            
            $ma_kh = $_SESSION['khach_hang']['ma_kh'] ?? null;
            $tongTien = $postData['tong_tien'] ?? 0;
            $phuongThucTT = $postData['phuong_thuc_thanh_toan'] ?? 'Ví điện tử';
            $combos = $postData['combos'] ?? [];
            $ma_suat_chieu = $_SESSION['ma_suat_chieu'] ?? null;
            $dsGheThanhToan = $_SESSION['ds_ghe_thanh_toan'] ?? [];

            if (!$ma_kh || !$ma_suat_chieu || empty($dsGheThanhToan)) {
                throw new Exception('Thiếu thông tin cần thiết');
            }

            // Lấy danh sách mã ghế
            $dsMaGhe = array_column($dsGheThanhToan, 'ma_ghe');

            // Kiểm tra ghế còn trống không
            if (!$this->gheSuatChieuModel->areGheConTrong($ma_suat_chieu, $dsMaGhe)) {
                throw new Exception('Một hoặc nhiều ghế đã được đặt bởi người khác. Vui lòng chọn lại.');
            }

            // Bắt đầu transaction
            $this->db->beginTransaction();

            // 1. Tạo hóa đơn với trạng thái "Chưa thanh toán"
            $dataHoaDon = [
                'ma_kh' => $ma_kh,
                'tong_tien' => $tongTien,
                'pttt' => $phuongThucTT,
                'trang_thai' => 'Chưa thanh toán'
            ];

            $ma_hoa_don = $this->hoaDonModel->createHoaDon($dataHoaDon);

            if (!$ma_hoa_don) {
                throw new Exception('Không thể tạo hóa đơn');
            }

            // 2. Tạo vé cho từng ghế
            foreach ($dsGheThanhToan as $seat) {
                $dataVe = [
                    'ma_suat_chieu' => $ma_suat_chieu,
                    'ma_ghe' => $seat['ma_ghe'],
                    'ma_hoa_don' => $ma_hoa_don,
                    'gia' => $seat['don_gia']
                ];
                
                $resultVe = $this->hoaDonModel->createVe($dataVe);
                if (!$resultVe) {
                    throw new Exception('Không thể tạo vé cho ghế ' . $seat['vi_tri']);
                }
            }

            // 3. Cập nhật trạng thái ghế thành 1 (Đang giữ/Đã chọn)
            $updateResult = $this->gheSuatChieuModel->updateTrangThaiNhieuGhe($ma_suat_chieu, $dsMaGhe, 1);
            
            if (!$updateResult) {
                throw new Exception('Không thể cập nhật trạng thái ghế');
            }

            // 4. Lưu combo (nếu có)
            if (!empty($combos)) {
                foreach ($combos as $combo) {
                    if ($combo['so_luong'] > 0) {
                        $dataCombo = [
                            'ma_hoa_don' => $ma_hoa_don,
                            'ma_combo' => $combo['ma_combo'],
                            'so_luong' => $combo['so_luong']
                        ];
                        
                        $this->hoaDonModel->addComboToHoaDon($dataCombo);
                    }
                }
            }

            // Commit transaction
            $this->db->commit();

            // Lưu mã hóa đơn vào session để dùng cho các bước tiếp theo
            $_SESSION['ma_hoa_don_hien_tai'] = $ma_hoa_don;

            echo json_encode([
                'success' => true,
                'message' => 'Tạo hóa đơn thành công!',
                'ma_hoa_don' => $ma_hoa_don
            ]);

        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Xác nhận thanh toán - chuyển trạng thái sang "Đã thanh toán"
     */
    public function confirmPayment()
    {
        header('Content-Type: application/json');
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        try {
            $postData = json_decode(file_get_contents('php://input'), true);
            $ma_hoa_don = $postData['ma_hoa_don'] ?? $_SESSION['ma_hoa_don_hien_tai'] ?? null;

            if (!$ma_hoa_don) {
                throw new Exception('Không tìm thấy mã hóa đơn');
            }

            // Bắt đầu transaction
            $this->db->beginTransaction();

            // Cập nhật trạng thái hóa đơn
            $result = $this->hoaDonModel->updateTrangThaiHoaDon($ma_hoa_don, 'Đã thanh toán');
            
            if (!$result) {
                throw new Exception('Không thể cập nhật trạng thái hóa đơn');
            }

            // GHẾ VẪN GIỮ NGUYÊN trạng thái = 1 (đã đặt)
            // Không cần cập nhật lại vì đã là 1 rồi

            // Commit transaction
            $this->db->commit();

            // Xóa session đặt vé
            unset($_SESSION['selected_seats']);
            unset($_SESSION['selected_seat_names']);
            unset($_SESSION['selected_seat_ids']);
            unset($_SESSION['thong_tin_suat']);
            unset($_SESSION['ds_ghe_thanh_toan']);
            unset($_SESSION['ma_suat_chieu']);
            unset($_SESSION['ma_hoa_don_hien_tai']);

            echo json_encode([
                'success' => true,
                'message' => 'Thanh toán thành công!',
                'ma_hoa_don' => $ma_hoa_don,
                'redirect' => 'index.php?controller=trangchu'
            ]);

        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ]);
        }
        exit;
    }

   /**
     * Hủy thanh toán - chuyển trạng thái sang "Hủy"
     * VÀ GIẢI PHÓNG GHẾ (cập nhật trạng thái về 0)
     */
    public function cancelPayment()
    {
        header('Content-Type: application/json');
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        try {
            $postData = json_decode(file_get_contents('php://input'), true);
            $ma_hoa_don = $postData['ma_hoa_don'] ?? $_SESSION['ma_hoa_don_hien_tai'] ?? null;

            if (!$ma_hoa_don) {
                throw new Exception('Không tìm thấy mã hóa đơn');
            }

            // Bắt đầu transaction
            $this->db->beginTransaction();

           // ... bên trong hàm cancelPayment ...

    // 1. LẤY THÔNG TIN VÉ
    $dsVe = $this->hoaDonModel->getVeByHoaDon($ma_hoa_don);
    
    if (empty($dsVe)) {
        throw new Exception('Không tìm thấy thông tin vé');
    }

    // DEBUG: Kiểm tra xem $dsVe có lấy được dữ liệu không
    // error_log(print_r($dsVe, true)); 

    // Lấy ma_suat_chieu (ép kiểu int để an toàn)
    $ma_suat_chieu = isset($dsVe[0]['ma_suat_chieu']) ? (int)$dsVe[0]['ma_suat_chieu'] : null;
    
    if (!$ma_suat_chieu) {
        throw new Exception('Không tìm thấy thông tin suất chiếu từ vé');
    }

    // Lấy danh sách mã ghế
    // LƯU Ý: Đảm bảo cột trong DB là 'ma_ghe'. Nếu HoaDonModel trả về 'MaGhe' hay tên khác thì phải sửa chuỗi bên dưới.
    $dsMaGhe = array_column($dsVe, 'ma_ghe');

    // Kiểm tra nếu danh sách ghế rỗng
    if (empty($dsMaGhe)) {
         throw new Exception('Không tìm thấy danh sách ghế cần hủy');
    }

    // 2. CẬP NHẬT TRẠNG THÁI HÓA ĐƠN
    $result = $this->hoaDonModel->updateTrangThaiHoaDon($ma_hoa_don, 'Hủy');
    
    if (!$result) {
        throw new Exception('Không thể hủy hóa đơn');
    }

    // 3. GIẢI PHÓNG GHẾ (chuyển về 0 = trống)
    // Gọi hàm đã sửa ở bước 1
    $updateGheResult = $this->gheSuatChieuModel->updateTrangThaiNhieuGhe(
        $ma_suat_chieu, 
        $dsMaGhe, 
        0 
    );
    
    // Lưu ý: Dù $updateGheResult trả về true, nhưng nếu ID sai thì vẫn không update dòng nào.
    // Việc ép kiểu ở bước 1 sẽ giúp giảm thiểu lỗi này.

    if (!$updateGheResult) {
        throw new Exception('Không thể giải phóng ghế');
    }

// ... tiếp tục commit transaction ...

            // Commit transaction
            $this->db->commit();

            // Xóa session
            unset($_SESSION['selected_seats']);
            unset($_SESSION['selected_seat_names']);
            unset($_SESSION['selected_seat_ids']);
            unset($_SESSION['thong_tin_suat']);
            unset($_SESSION['ds_ghe_thanh_toan']);
            unset($_SESSION['ma_suat_chieu']);
            unset($_SESSION['ma_hoa_don_hien_tai']);

            echo json_encode([
                'success' => true,
                'message' => 'Đã hủy thanh toán và giải phóng ghế',
                'redirect' => 'index.php?controller=trangchu',
                'debug' => [
                    'ma_hoa_don' => $ma_hoa_don,
                    'ma_suat_chieu' => $ma_suat_chieu,
                    'so_ghe_giai_phong' => count($dsMaGhe),
                    'ds_ma_ghe' => $dsMaGhe
                ]
            ]);

        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ]);
        }
        exit;
    }}