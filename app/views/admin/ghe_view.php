<?php
// app/views/admin/ghe_view.php
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quản lý Ghế - <?php echo htmlspecialchars($selected_phong_info['ten_phong']); ?></title>
    <link rel="stylesheet" href="publics/css/admin-layout1.css" />
    <link rel="stylesheet" href="publics/css/admin-room1.css" />
    <link rel="stylesheet" href="publics/css/admin-ghe1.css" />
</head>
<body>
    <?php include __DIR__ . '/../chung/header_sidebar.php'; ?>

        <main class="main-content">
            <!-- PAGE HEADER -->
            <div class="page-header">
                <h3>QUẢN LÝ GHẾ - <?php echo htmlspecialchars($selected_phong_info['ten_phong']); ?> (<?php echo htmlspecialchars($selected_phong_info['ten_rap']); ?>)</h3>
                <div>
                    <a href="index.php?controller=adminPhong&action=createGhe&ma_phong=<?php echo $selected_phong_info['ma_phong']; ?>&page=<?php echo isset($_GET['page']) ? (int)$_GET['page'] : 1; ?>" class="add-btn">+ Thêm Ghế</a>
                    <a href="index.php?controller=adminPhong&action=index" class="btn-cancel back-btn">← Quay lại danh sách phòng</a>
                </div>
            </div>

            <!-- THÔNG TIN PHÒNG -->
            <div class="info-section">
                <div class="info-grid">
                    <div>
                        <strong>Mã phòng:</strong> <?php echo $selected_phong_info['ma_phong']; ?>
                    </div>
                    <div>
                        <strong>Tên phòng:</strong> <?php echo htmlspecialchars($selected_phong_info['ten_phong']); ?>
                    </div>
                    <div>
                        <strong>Loại màn hình:</strong> <?php echo $selected_phong_info['loai_man_hinh']; ?>
                    </div>
                    <div>
                        <strong>Tổng số ghế:</strong> <?php echo count($danhSachGhe); ?>
                    </div>
                </div>
            </div>

            <!-- FORM THÊM GHẾ -->
            <?php if (isset($action) && $action === 'create_ghe'): ?>
            <div class="form-container">
                <h4>THÊM GHẾ MỚI</h4>
                <form action="index.php?controller=adminPhong&action=storeGhe" method="POST" class="form-grid">
                    <input type="hidden" name="ma_phong" value="<?php echo $selected_phong_info['ma_phong']; ?>">
                    <input type="hidden" name="page" value="<?php echo isset($_GET['page']) ? (int)$_GET['page'] : 1; ?>">
                    <div class="form-group">
                        <label>Vị trí *</label>
                        <input type="text" name="vi_tri" placeholder="Ví dụ: A1, B2, C3..." required>
                    </div>
                    <div class="form-group">
                        <label>Loại ghế *</label>
                        <select name="loai_ghe" required>
                            <option value="">Chọn loại ghế</option>
                            <option value="Thường">Thường</option>
                            <option value="VIP">VIP</option>
                            <option value="Đôi">Đôi</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Trạng thái *</label>
                        <select name="trang_thai" required>
                            <option value="Hoạt động">Hoạt động</option>
                            <option value="Bảo trì">Bảo trì</option>
                            <option value="Hỏng">Hỏng</option>
                        </select>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-save">Lưu</button>
                        <a href="index.php?controller=adminPhong&action=manageSeats&ma_phong=<?php echo $selected_phong_info['ma_phong']; ?>&page=<?php echo isset($_GET['page']) ? (int)$_GET['page'] : 1; ?>" class="btn-cancel">Hủy</a>
                    </div>
                </form>
            </div>
            <?php endif; ?>

            <!-- FORM CHỈNH SỬA GHẾ -->
            <?php if (isset($ghe_to_edit)): ?>
            <div class="form-container">
                <h4>CHỈNH SỬA GHẾ</h4>
                <form action="index.php?controller=adminPhong&action=updateGhe" method="POST" class="form-grid">
                    <input type="hidden" name="ma_ghe" value="<?php echo $ghe_to_edit['ma_ghe']; ?>">
                    <input type="hidden" name="ma_phong" value="<?php echo $selected_phong_info['ma_phong']; ?>">
                    <input type="hidden" name="page" value="<?php echo isset($_GET['page']) ? (int)$_GET['page'] : 1; ?>">
                    <div class="form-group">
                        <label>Vị trí *</label>
                        <input type="text" name="vi_tri" value="<?php echo htmlspecialchars($ghe_to_edit['vi_tri']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Loại ghế *</label>
                        <select name="loai_ghe" required>
                            <option value="Thường" <?php echo ($ghe_to_edit['loai_ghe'] == 'Thường') ? 'selected' : ''; ?>>Thường</option>
                            <option value="VIP" <?php echo ($ghe_to_edit['loai_ghe'] == 'VIP') ? 'selected' : ''; ?>>VIP</option>
                            <option value="Đôi" <?php echo ($ghe_to_edit['loai_ghe'] == 'Đôi') ? 'selected' : ''; ?>>Đôi</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Trạng thái *</label>
                        <select name="trang_thai" required>
                            <option value="Hoạt động" <?php echo ($ghe_to_edit['tinh_trang'] == 'Hoạt động') ? 'selected' : ''; ?>>Hoạt động</option>
                            <option value="Bảo trì" <?php echo ($ghe_to_edit['tinh_trang'] == 'Bảo trì') ? 'selected' : ''; ?>>Bảo trì</option>
                            <option value="Hỏng" <?php echo ($ghe_to_edit['tinh_trang'] == 'Hỏng') ? 'selected' : ''; ?>>Hỏng</option>
                        </select>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-save">Lưu</button>
                        <a href="index.php?controller=adminPhong&action=manageSeats&ma_phong=<?php echo $selected_phong_info['ma_phong']; ?>&page=<?php echo isset($_GET['page']) ? (int)$_GET['page'] : 1; ?>" class="btn-cancel">Hủy</a>
                    </div>
                </form>
            </div>
            <?php endif; ?>

            <!-- SỐ LƯỢNG KẾT QUẢ -->
            <?php if (!isset($action)): ?>
                <div class="result-count">
                    <span>Hiển thị <?php echo count($danhSachGhe); ?> ghế</span>
                </div>
            <?php endif; ?>

            <!-- BẢNG DỮ LIỆU GHẾ -->
            <section class="data-section">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID Ghế</th>
                                <th>Vị trí</th>
                                <th>Loại ghế</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($danhSachGhe)): ?>
                                <tr>
                                    <td colspan="5" class="no-data">
                                        Chưa có ghế nào trong phòng này.
                                        <br>
                                        <a href="index.php?controller=adminPhong&action=createGhe&ma_phong=<?php echo $selected_phong_info['ma_phong']; ?>" class="add-link">
                                            Thêm ghế mới
                                        </a>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($danhSachGhe as $ghe): ?>
                                    <tr>
                                        <td><?php echo $ghe['ma_ghe']; ?></td>
                                        <td><?php echo htmlspecialchars($ghe['vi_tri']); ?></td>
                                        <td><?php echo $ghe['loai_ghe']; ?></td>
                                        <td>
                                            <?php if ($ghe['tinh_trang'] == 'Hoạt động'): ?>
                                                <span class="status-active"><?php echo $ghe['tinh_trang']; ?></span>
                                            <?php elseif ($ghe['tinh_trang'] == 'Bảo trì'): ?>
                                                <span class="status-maintenance"><?php echo $ghe['tinh_trang']; ?></span>
                                            <?php else: ?>
                                                <span class="status-inactive"><?php echo $ghe['tinh_trang']; ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="index.php?controller=adminPhong&action=editGhe&ma_ghe=<?php echo $ghe['ma_ghe']; ?>&ma_phong=<?php echo $selected_phong_info['ma_phong']; ?>&page=<?php echo isset($_GET['page']) ? (int)$_GET['page'] : 1; ?>" class="action-btn edit-btn">Sửa</a>
                                            <a href="index.php?controller=adminPhong&action=destroyGhe&ma_ghe=<?php echo $ghe['ma_ghe']; ?>&ma_phong=<?php echo $selected_phong_info['ma_phong']; ?>&page=<?php echo isset($_GET['page']) ? (int)$_GET['page'] : 1; ?>" class="action-btn delete-btn" onclick="return confirm('Bạn có chắc chắn muốn xóa ghế này?');">Xóa</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>


                <!-- PHÂN TRANG -->
            <?php if (isset($totalPages) && $totalPages > 1 && !isset($action)): ?>
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

    <!-- THÔNG BÁO -->
    <?php if (isset($_GET['status'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var status = '<?php echo $_GET["status"]; ?>';
                var message = '';
                var type = 'info';
                
                switch(status) {
                    case 'add_ghe_success':
                        message = 'Thêm ghế thành công!';
                        type = 'success';
                        break;
                    case 'add_ghe_error':
                        message = 'Có lỗi xảy ra khi thêm ghế!';
                        type = 'error';
                        break;
                    case 'update_ghe_success':
                        message = 'Cập nhật ghế thành công!';
                        type = 'success';
                        break;
                    case 'update_ghe_error':
                        message = 'Có lỗi xảy ra khi cập nhật ghế!';
                        type = 'error';
                        break;
                    case 'delete_ghe_success':
                        message = 'Xóa ghế thành công!';
                        type = 'success';
                        break;
                    case 'delete_ghe_error':
                        message = 'Có lỗi xảy ra khi xóa ghế!';
                        type = 'error';
                        break;
                    case 'not_found':
                        message = 'Không tìm thấy dữ liệu!';
                        type = 'error';
                        break;
                }
                
                if (message) {
                    alert(message);
                }
            });
        </script>
    <?php endif; ?>
</body>
</html>