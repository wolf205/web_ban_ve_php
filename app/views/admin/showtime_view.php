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
    <?php include __DIR__ . '/../chung/header_sidebar.php'; ?>

        <main class="main-content">
            <div class="page-header">
                <h3>QUẢN LÝ SUẤT CHIẾU</h3>
                <a href="index.php?controller=adminShowtime&action=create" class="add-btn">+ Thêm Suất Chiếu</a>
            </div>

            <!-- Bộ lọc sử dụng class CSS chuẩn từ admin-layout1.css -->
                <div class="filter-section">
                    <h4>Bộ lọc suất chiếu</h4>
                    <form class="filter-form" method="GET" action="index.php">
                        <input type="hidden" name="controller" value="adminShowtime">
                        <input type="hidden" name="action" value="index">
                        
                        <div class="filter-row">
                            <div class="filter-group">
                                <label for="ngay_chieu">Ngày chiếu</label>
                                <input type="date" id="ngay_chieu" name="ngay_chieu" value="<?php echo $_GET['ngay_chieu'] ?? ''; ?>">
                            </div>

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

                            <div class="filter-actions">
                                <button type="submit" class="btn-filter">Lọc</button>
                                <a href="index.php?controller=adminShowtime&action=index" class="btn-reset">Đặt lại</a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Thông tin bộ lọc đang áp dụng -->
                <?php if (!empty($_GET['ngay_chieu']) || !empty($_GET['ten_rap']) || !empty($_GET['ten_phim'])): ?>
                    <div class="active-filters">
                        <small>
                            Đang lọc theo: 
                            <?php 
                            $filters = [];
                            if (!empty($_GET['ngay_chieu'])) {
                                $filters[] = "<strong>Ngày chiếu</strong>: " . htmlspecialchars($_GET['ngay_chieu']);
                            }
                            if (!empty($_GET['ten_rap'])) {
                                $filters[] = "<strong>Rạp</strong>: " . htmlspecialchars($_GET['ten_rap']);
                            }
                            if (!empty($_GET['ten_phim'])) {
                                $filters[] = "<strong>Phim</strong>: " . htmlspecialchars($_GET['ten_phim']);
                            }
                            echo implode(', ', $filters);
                            ?>
                            <a href="index.php?controller=adminShowtime&action=index">[Xóa tất cả]</a>
                        </small>
                    </div>
                <?php endif; ?>

            <?php if (isset($action) && $action === 'create'): ?>
            <!-- FORM THÊM MỚI -->
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
                        <a href="index.php?controller=adminShowtime&action=index" class="btn-cancel">Hủy</a>
                    </div>
                </form>
            </div>
            <?php endif; ?>

            <?php if (isset($edit_id) && isset($suatChieuToEdit)): ?>
            <!-- FORM CHỈNH SỬA -->
            <div class="form-container">
                <h4>CHỈNH SỬA SUẤT CHIẾU</h4>
                <form action="index.php?controller=adminShowtime&action=update" method="POST" class="form-grid">
                    <input type="hidden" name="ma_suat_chieu" value="<?php echo $suatChieuToEdit['ma_suat_chieu']; ?>">
                    <div class="form-group">
                        <label>Phim *</label>
                        <select name="ma_phim" required>
                            <option value="">Chọn phim</option>
                            <?php foreach ($danhSachPhim as $phim): ?>
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
                                <option value="<?php echo $phong['ma_phong']; ?>" 
                                    <?php echo ($phong['ma_phong'] == $suatChieuToEdit['ma_phong']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($phong['ten_phong']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Ngày chiếu *</label>
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
                        <a href="index.php?controller=adminShowtime&action=index" class="btn-cancel">Hủy</a>
                    </div>
                </form>
            </div>
            <?php endif; ?>

            <section class="data-section">
                

                <!-- Số lượng kết quả -->
                <?php if (!empty($danhSachSuatChieu)): ?>
                    <div class="result-count">
                        Hiển thị <?php echo count($danhSachSuatChieu); ?> suất chiếu
                    </div>
                <?php endif; ?>

                <!-- Bảng dữ liệu -->
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
                            <?php if (empty($danhSachSuatChieu)): ?>
                                <tr>
                                    <td colspan="9" class="no-results">
                                        <?php 
                                        if (!empty($_GET['ngay_chieu']) || !empty($_GET['ten_rap']) || !empty($_GET['ten_phim'])) {
                                            echo 'Không có suất chiếu nào phù hợp với bộ lọc';
                                        } else {
                                            echo 'Chưa có suất chiếu nào.';
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($danhSachSuatChieu as $suatChieu): ?>
                                    <tr>
                                        <td><?php echo $suatChieu['ma_suat_chieu']; ?></td>
                                        <td><?php echo htmlspecialchars($suatChieu['ten_phim']); ?></td>
                                        <td><?php echo htmlspecialchars($suatChieu['ten_rap']); ?></td>
                                        <td><?php echo htmlspecialchars($suatChieu['ten_phong']); ?></td>
                                        <td><?php echo $suatChieu['ngay_chieu']; ?></td>
                                        <td><?php echo $suatChieu['gio_bat_dau'] . ' - ' . $suatChieu['gio_ket_thuc']; ?></td>
                                        <td><?php echo number_format($suatChieu['gia_ve'], 0, ',', '.'); ?> đ</td>
                                        <td><?php echo $suatChieu['so_ghe_trong'] . '/' . $suatChieu['tong_so_ghe']; ?></td>
                                        <td>
                                            <a href="index.php?controller=adminShowtime&action=edit&id=<?php echo $suatChieu['ma_suat_chieu']; ?>" class="action-btn edit-btn">Sửa</a>
                                            <a href="index.php?controller=adminShowtime&action=destroy&id=<?php echo $suatChieu['ma_suat_chieu']; ?>" class="action-btn delete-btn" onclick="return confirm('Bạn có chắc chắn muốn xóa suất chiếu này?');">Xóa</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
<!-- Phân trang (THÊM VÀO CUỐI BẢNG) -->
<?php if ($totalPages > 1): ?>
<div class="simple-pagination">
    <?php 
    $queryParams = $_GET;
    unset($queryParams['page']);
    $baseUrl = 'index.php?' . http_build_query($queryParams);
    ?>
    
    <?php for($i = 1; $i <= $totalPages; $i++): ?>
        <?php if($i == $page): ?>
            <strong><?php echo $i; ?></strong>
        <?php else: ?>
            <a href="<?php echo $baseUrl . '&page=' . $i; ?>"><?php echo $i; ?></a>
        <?php endif; ?>
    <?php endfor; ?>
</div>
<?php endif; ?>
            </section>
        </main>
    </div>

    <?php if (isset($_GET['status'])): ?>
        <script>
            <?php
            $statusMessages = [
                'add_success' => 'Thêm suất chiếu thành công!',
                'add_error' => 'Lỗi khi thêm suất chiếu!',
                'update_success' => 'Cập nhật suất chiếu thành công!',
                'update_error' => 'Lỗi khi cập nhật suất chiếu!',
                'delete_success' => 'Xóa suất chiếu thành công!',
                'delete_error' => 'Lỗi khi xóa suất chiếu!',
                'not_found' => 'Không tìm thấy suất chiếu!'
            ];
            
            if (isset($statusMessages[$_GET['status']])) {
                echo 'alert("' . $statusMessages[$_GET['status']] . '");';
            }
            ?>
        </script>
    <?php endif; ?>
</body>
</html>