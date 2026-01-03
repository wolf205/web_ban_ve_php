<?php
// app/views/admin/showtime_view.php
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>CINETIX - Quản lý suất chiếu</title>
    <link rel="stylesheet" href="publics/css/admin-layout1.css" />
    <link rel="stylesheet" href="publics/css/admin-showtime1.css" />
</head>
<body>
    <!-- INCLUDE HEADER VÀ SIDEBAR CHUNG -->
    <?php include __DIR__ . '/../chung/header_sidebar.php'; ?>

    <main class="main-content">
        <!-- HEADER TRANG VỚI TIÊU ĐỀ VÀ NÚT THÊM SUẤT CHIẾU -->
        <div class="page-header">
            <h3>QUẢN LÝ SUẤT CHIẾU</h3>
            <a href="index.php?controller=adminShowtime&action=create&page=<?php echo $page; ?>" class="add-btn">+ Thêm Suất Chiếu</a>
        </div>

        <!-- PHẦN BỘ LỌC SUẤT CHIẾU -->
        <div class="filter-section">
            <h4>Bộ lọc suất chiếu</h4>
            <form class="filter-form" method="GET" action="index.php">
                <!-- CÁC TRƯỜNG ẨN ĐỂ XÁC ĐỊNH CONTROLLER VÀ ACTION -->
                <input type="hidden" name="controller" value="adminShowtime">
                <input type="hidden" name="action" value="index">
                
                <div class="filter-row">
                    <!-- LỌC THEO NGÀY CHIẾU -->
                    <div class="filter-group">
                        <label for="ngay_chieu">Ngày chiếu</label>
                        <!-- INPUT DATE CHO NGÀY CHIẾU, GIỮ LẠI GIÁ TRỊ ĐÃ CHỌN -->
                        <input type="date" id="ngay_chieu" name="ngay_chieu" value="<?php echo $_GET['ngay_chieu'] ?? ''; ?>">
                    </div>

                    <!-- LỌC THEO TÊN RẠP -->
                    <div class="filter-group">
                        <label for="ten_rap">Rạp</label>
                        <select id="ten_rap" name="ten_rap">
                            <option value="">Tất cả rạp</option>
                            <?php foreach ($danhSachRap as $rap): ?>
                                <option value="<?php echo htmlspecialchars($rap['ten_rap']); ?>" 
                                    <?php echo (isset($_GET['ten_rap']) && $_GET['ten_rap'] == $rap['ten_rap']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($rap['ten_rap']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- LỌC THEO TÊN PHIM -->
                    <div class="filter-group">
                        <label for="ten_phim">Phim</label>
                        <select id="ten_phim" name="ten_phim">
                            <option value="">Tất cả phim</option>
                            <?php foreach ($danhSachPhim as $phim): ?>
                                <option value="<?php echo htmlspecialchars($phim['ten_phim']); ?>" 
                                    <?php echo (isset($_GET['ten_phim']) && $_GET['ten_phim'] == $phim['ten_phim']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($phim['ten_phim']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- CÁC NÚT HÀNH ĐỘNG CHO BỘ LỌC -->
                    <div class="filter-actions">
                        <button type="submit" class="btn-filter">Lọc</button>
                        <a href="index.php?controller=adminShowtime&action=index" class="btn-reset">Đặt lại</a>
                    </div>
                </div>
            </form>
        </div>

        <!-- HIỂN THỊ THÔNG TIN BỘ LỌC ĐANG ÁP DỤNG (NẾU CÓ) -->
        <?php if (!empty($_GET['ngay_chieu']) || !empty($_GET['ten_rap']) || !empty($_GET['ten_phim'])): ?>
            <div class="active-filters">
                <small>
                    Đang lọc theo: 
                    <?php 
                    $filters = [];
                    // THÊM THÔNG TIN LỌC THEO NGÀY CHIẾU (NẾU CÓ)
                    if (!empty($_GET['ngay_chieu'])) {
                        $filters[] = "<strong>Ngày chiếu</strong>: " . htmlspecialchars($_GET['ngay_chieu']);
                    }
                    // THÊM THÔNG TIN LỌC THEO RẠP (NẾU CÓ)
                    if (!empty($_GET['ten_rap'])) {
                        $filters[] = "<strong>Rạp</strong>: " . htmlspecialchars($_GET['ten_rap']);
                    }
                    // THÊM THÔNG TIN LỌC THEO PHIM (NẾU CÓ)
                    if (!empty($_GET['ten_phim'])) {
                        $filters[] = "<strong>Phim</strong>: " . htmlspecialchars($_GET['ten_phim']);
                    }
                    // HIỂN THỊ TẤT CẢ CÁC BỘ LỌC ĐANG ÁP DỤNG
                    echo implode(', ', $filters);
                    ?>
                    <!-- NÚT XÓA TẤT CẢ BỘ LỌC -->
                    <a href="index.php?controller=adminShowtime&action=index">[Xóa tất cả]</a>
                </small>
            </div>
        <?php endif; ?>

        <!-- FORM THÊM SUẤT CHIẾU MỚI (CHỈ HIỂN THỊ KHI ACTION = 'create') -->
        <?php if (isset($action) && $action === 'create'): ?>
        <div class="form-container">
            <h4>THÊM SUẤT CHIẾU MỚI</h4>
            <form action="index.php?controller=adminShowtime&action=store" method="POST" class="form-grid">
                <div class="form-group">
                    <label>Phim *</label>
                    <select name="ma_phim" required>
                        <option value="">Chọn phim</option>
                        <?php foreach ($danhSachPhim as $phim): ?>
                            <option value="<?php echo $phim['ma_phim']; ?>">
                                <?php echo htmlspecialchars($phim['ten_phim']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Phòng *</label>
                    <select name="ma_phong" required>
                        <option value="">Chọn phòng</option>
                        <?php foreach ($danhSachPhong as $phong): ?>
                            <option value="<?php echo $phong['ma_phong']; ?>">
                                <?php echo htmlspecialchars($phong['ten_phong']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Ngày chiếu *</label>
                    <input type="date" name="ngay_chieu" required>
                </div>
                <div class="form-group">
                    <label>Giờ bắt đầu *</label>
                    <input type="time" name="gio_bat_dau" required>
                </div>
                <div class="form-group">
                    <label>Giờ kết thúc *</label>
                    <input type="time" name="gio_ket_thuc" required>
                </div>
                <div class="form-group">
                    <label>Giá vé cơ bản *</label>
                    <input type="number" name="gia_ve_co_ban" min="0" required>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-save">Lưu</button>
                    <a href="index.php?controller=adminShowtime&action=index&page=<?php echo $page; ?>" class="btn-cancel">Hủy</a>
                </div>
            </form>
        </div>
        <?php endif; ?>

        <!-- FORM CHỈNH SỬA SUẤT CHIẾU (CHỈ HIỂN THỊ KHI CÓ SUẤT CHIẾU CẦN SỬA) -->
        <?php if (isset($edit_id) && isset($suatChieuToEdit)): ?>
        <div class="form-container">
            <h4>CHỈNH SỬA SUẤT CHIẾU</h4>
            <form action="index.php?controller=adminShowtime&action=update" method="POST" class="form-grid">
                <!-- ẨN MÃ SUẤT CHIẾU ĐỂ SỬ DỤNG KHI UPDATE -->
                <input type="hidden" name="ma_suat_chieu" value="<?php echo $suatChieuToEdit['ma_suat_chieu']; ?>">
                <div class="form-group">
                    <label>Phim *</label>
                    <select name="ma_phim" required>
                        <option value="">Chọn phim</option>
                        <?php foreach ($danhSachPhim as $phim): ?>
                            <!-- CHỌN PHIM HIỆN TẠI CỦA SUẤT CHIẾU -->
                            <option value="<?php echo $phim['ma_phim']; ?>" 
                                <?php echo ($phim['ma_phim'] == $suatChieuToEdit['ma_phim']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($phim['ten_phim']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Phòng *</label>
                    <select name="ma_phong" required>
                        <option value="">Chọn phòng</option>
                        <?php foreach ($danhSachPhong as $phong): ?>
                            <!-- CHỌN PHÒNG HIỆN TẠI CỦA SUẤT CHIẾU -->
                            <option value="<?php echo $phong['ma_phong']; ?>" 
                                <?php echo ($phong['ma_phong'] == $suatChieuToEdit['ma_phong']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($phong['ten_phong']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Ngày chiếu *</label>
                    <!-- ĐIỀN SẴN NGÀY CHIẾU HIỆN TẠI -->
                    <input type="date" name="ngay_chieu" value="<?php echo $suatChieuToEdit['ngay_chieu']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Giờ bắt đầu *</label>
                    <input type="time" name="gio_bat_dau" value="<?php echo $suatChieuToEdit['gio_bat_dau']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Giờ kết thúc *</label>
                    <input type="time" name="gio_ket_thuc" value="<?php echo $suatChieuToEdit['gio_ket_thuc']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Giá vé cơ bản *</label>
                    <input type="number" name="gia_ve_co_ban" value="<?php echo $suatChieuToEdit['gia_ve_co_ban']; ?>" min="0" required>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-save">Lưu</button>
                    <a href="index.php?controller=adminShowtime&action=index&page=<?php echo $page; ?>" class="btn-cancel">Hủy</a>
                </div>
            </form>
        </div>
        <?php endif; ?>

        <!-- HIỂN THỊ SỐ LƯỢNG SUẤT CHIẾU (CHỈ KHI CÓ DỮ LIỆU) -->
        <?php if (!empty($danhSachSuatChieu)): ?>
            <div class="result-count">
                Hiển thị <?php echo count($danhSachSuatChieu); ?> suất chiếu
            </div>
        <?php endif; ?>

        <!-- PHẦN BẢNG DỮ LIỆU SUẤT CHIẾU -->
        <section class="data-section">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên phim</th>
                            <th>Rạp</th>
                            <th>Phòng</th>
                            <th>Ngày chiếu</th>
                            <th>Thời gian</th>
                            <th>Giá vé</th>
                            <th>Ghế</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- KIỂM TRA NẾU KHÔNG CÓ SUẤT CHIẾU NÀO -->
                        <?php if (empty($danhSachSuatChieu)): ?>
                            <tr>
                                <td colspan="9" class="no-results">
                                    <?php 
                                    // THÔNG BÁO KHÁC NHAU TÙY THEO CÓ BỘ LỌC HAY KHÔNG
                                    if (!empty($_GET['ngay_chieu']) || !empty($_GET['ten_rap']) || !empty($_GET['ten_phim'])) {
                                        echo 'Không có suất chiếu nào phù hợp với bộ lọc';
                                    } else {
                                        echo 'Chưa có suất chiếu nào.';
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php else: ?>
                            <!-- LẶP QUA DANH SÁCH SUẤT CHIẾU VÀ HIỂN THỊ -->
                            <?php foreach ($danhSachSuatChieu as $suatChieu): ?>
                                <tr>
                                    <td><?php echo $suatChieu['ma_suat_chieu']; ?></td>
                                    <td><?php echo htmlspecialchars($suatChieu['ten_phim']); ?></td>
                                    <td><?php echo htmlspecialchars($suatChieu['ten_rap']); ?></td>
                                    <td><?php echo htmlspecialchars($suatChieu['ten_phong']); ?></td>
                                    <td><?php echo $suatChieu['ngay_chieu']; ?></td>
                                    <!-- KẾT HỢP GIỜ BẮT ĐẦU VÀ KẾT THÚC -->
                                    <td><?php echo $suatChieu['gio_bat_dau'] . ' - ' . $suatChieu['gio_ket_thuc']; ?></td>
                                    <!-- ĐỊNH DẠNG SỐ TIỀN THEO KIỂU VIỆT NAM -->
                                    <td><?php echo number_format($suatChieu['gia_ve'], 0, ',', '.'); ?> đ</td>
                                    <!-- HIỂN THỊ SỐ GHẾ TRỐNG / TỔNG SỐ GHẾ -->
                                    <td><?php echo $suatChieu['so_ghe_trong'] . '/' . $suatChieu['tong_so_ghe']; ?></td>
                                    <td>
                                        <!-- NÚT SỬA SUẤT CHIẾU -->
                                        <a href="index.php?controller=adminShowtime&action=edit&id=<?php echo $suatChieu['ma_suat_chieu']; ?>&page=<?php echo $page; ?>" class="action-btn edit-btn">Sửa</a>
                                        <!-- NÚT XÓA SUẤT CHIẾU (CÓ XÁC NHẬN) -->
                                        <a href="index.php?controller=adminShowtime&action=destroy&id=<?php echo $suatChieu['ma_suat_chieu']; ?>" class="action-btn delete-btn" onclick="return confirm('Bạn có chắc chắn muốn xóa suất chiếu này?');">Xóa</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- PHÂN TRANG (CHỈ HIỂN THỊ KHI CÓ NHIỀU TRANG) -->
            <?php if ($totalPages > 1): ?>
            <div class="simple-pagination">
                <?php 
                // GIỮ LẠI TẤT CẢ THAM SỐ GET NGOẠI TRỪ 'page'
                $queryParams = $_GET;
                unset($queryParams['page']);
                $baseUrl = 'index.php?' . http_build_query($queryParams);
                ?>
                
                <!-- TẠO CÁC LIÊN KẾT PHÂN TRANG -->
                <?php for($i = 1; $i <= $totalPages; $i++): ?>
                    <?php if($i == $page): ?>
                        <!-- TRANG HIỆN TẠI - HIỂN THỊ ĐẬM -->
                        <strong><?php echo $i; ?></strong>
                    <?php else: ?>
                        <!-- CÁC TRANG KHÁC - CÓ THỂ CLICK -->
                        <a href="<?php echo $baseUrl . '&page=' . $i; ?>"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
            <?php endif; ?>
        </section>
    </main>

    <!-- PHẦN HIỂN THỊ THÔNG BÁO TỪ SESSION (FLASH MESSAGES) -->
    <?php if (isset($_SESSION['flash_status'])): ?>
    <script>
        <?php
        // MẢNG CHỨA CÁC THÔNG BÁO TƯƠNG ỨNG VỚI STATUS
        $statusMessages = [
            'add_success' => 'Thêm suất chiếu thành công!',
            'add_error' => 'Lỗi khi thêm suất chiếu!',
            'update_success' => 'Cập nhật suất chiếu thành công!',
            'update_error' => 'Lỗi khi cập nhật suất chiếu!',
            'delete_success' => 'Xóa suất chiếu thành công!',
            'delete_error' => 'Lỗi khi xóa suất chiếu!',
            'not_found' => 'Không tìm thấy suất chiếu!'
        ];
        
        // LẤY STATUS TỪ SESSION
        $statusKey = $_SESSION['flash_status'];
        
        // HIỂN THỊ ALERT NẾU CÓ THÔNG BÁO TƯƠNG ỨNG
        if (isset($statusMessages[$statusKey])) {
            echo 'alert("' . $statusMessages[$statusKey] . '");';
        }
        
        // XÓA STATUS SAU KHI ĐÃ HIỂN THỊ ĐỂ TRÁNH HIỂN THỊ LẠI KHI REFRESH
        unset($_SESSION['flash_status']);
        ?>
    </script>
    <?php endif; ?>
</body>
</html>