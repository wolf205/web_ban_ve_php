<?php
// app/views/admin/showtime_view.php
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>CINETIX - Quản lý suất chiếu</title>
    <link rel="stylesheet" href="publics/css/admin-layout.css" />
    <link rel="stylesheet" href="publics/css/admin-showtime.css" />
</head>
<body>
    <header class="top-bar">
        <div class="logo">
            <img src="publics/img/avata1.jpg" alt="CINETIX Logo" />
            <h1>CINETIX</h1>
        </div>
        <div class="user-profile">
            <span>Alice</span>
            <div class="user-icon">A</div>
        </div>
    </header>

    <div class="content-container">
        <aside class="sidebar">
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="index.php?controller=adminDashboard&action=index">Dashboard</a></li>
                    <li><a href="index.php?controller=adminCustomer&action=index">Tài khoản người dùng</a></li>
                    <li><a href="index.php?controller=adminStaff&action=index">Tài khoản nhân sự</a></li>
                    <li><a href="index.php?controller=adminPhim&action=index">Quản lý phim</a></li>
                    <li class="active">
                        <a href="index.php?controller=adminShowtime&action=index">Quản lý suất chiếu</a>
                    </li>
                    <li>
                        <a href="index.php?controller=adminRap&action=index">Quản lý rạp</a>
                    </li>
                    <li>
                        <a href="index.php?controller=adminPhong&action=index">Quản lý phòng chiếu</a>
                    </li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h3>QUẢN LÝ SUẤT CHIẾU</h3>
                <a href="index.php?controller=adminShowtime&action=create" class="add-btn">+ Thêm Suất Chiếu</a>
            </div>

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
                <!-- Thêm vào showtime_view.php sau thẻ mở <section class="data-section"> -->

<!-- Bộ lọc mới -->
<div class="filter-container">
    <h3 class="filter-title">Bộ lọc suất chiếu</h3>
    <form class="filter-form" method="GET" action="index.php">
        <input type="hidden" name="controller" value="adminShowtime">
        <input type="hidden" name="action" value="index">
        
        <div class="form-group">
            <label for="ngay_chieu">Ngày chiếu</label>
            <input type="date" id="ngay_chieu" name="ngay_chieu" value="<?php echo $_GET['ngay_chieu'] ?? ''; ?>">
        </div>

        <div class="form-group">
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

        <div class="form-group">
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

        <div class="filter-buttons">
            <button type="submit" class="filter-btn">Lọc</button>
            <a href="index.php?controller=adminShowtime&action=index" class="reset-btn">Đặt lại</a>
        </div>
    </form>
</div>
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
                        <!-- Cập nhật phần tbody trong showtime_view.php -->

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
            <!-- ... phần hiển thị từng suất chiếu ... -->
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