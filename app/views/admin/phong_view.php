<?php
// app/views/admin/phong_view.php
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quản lý Phòng chiếu</title>
    <link rel="stylesheet" href="publics/css/admin-layout1.css" />
    <link rel="stylesheet" href="publics/css/admin-room.css" />
</head>
<body>
    <?php include __DIR__ . '/../chung/header_sidebar.php'; ?>

        <main class="main-content">
            <!-- PAGE HEADER -->
            <div class="page-header">
                <h3>DANH SÁCH PHÒNG CHIẾU</h3>
                <a href="index.php?controller=adminPhong&action=create" class="add-btn">+ Thêm Phòng</a>
            </div>

            <!-- BỘ LỌC & TÌM KIẾM -->
            <div class="filter-section">
                <h4>BỘ LỌC & TÌM KIẾM</h4>
                <form method="GET" action="" class="filter-form">
                    <input type="hidden" name="controller" value="adminPhong">
                    <input type="hidden" name="action" value="index">
                    
                    <div class="filter-row">
                        <div class="filter-group">
                            <label for="ma_rap">Rạp:</label>
                            <select name="ma_rap" id="ma_rap">
                                <option value="all">Tất cả rạp</option>
                                <?php 
                                $selected_rap = $filter_params['ma_rap'] ?? null;
                                foreach ($danhSachRap as $rap): 
                                ?>
                                    <option value="<?php echo $rap['ma_rap']; ?>" 
                                        <?php echo ($selected_rap == $rap['ma_rap']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($rap['ten_rap']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label for="loai_man_hinh">Loại màn hình:</label>
                            <select name="loai_man_hinh" id="loai_man_hinh">
                                <option value="all">Tất cả loại</option>
                                <?php 
                                $selected_screen = $filter_params['loai_man_hinh'] ?? null;
                                foreach ($loai_man_hinh_list as $type): 
                                ?>
                                    <option value="<?php echo htmlspecialchars($type); ?>" 
                                        <?php echo ($selected_screen == $type) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($type); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label for="search">Tìm kiếm:</label>
                            <input type="text" name="search" id="search" 
                                   placeholder="Tên phòng hoặc rạp" 
                                   value="<?php echo htmlspecialchars($filter_params['search'] ?? ''); ?>">
                        </div>
                        
                        <div class="filter-actions">
                            <button type="submit" class="btn-filter">Lọc</button>
                            <a href="index.php?controller=adminPhong&action=index" class="btn-reset">Xóa lọc</a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- THÔNG TIN BỘ LỌC ĐANG ÁP DỤNG -->
            <?php if (!empty($filter_params['ma_rap']) || !empty($filter_params['search']) || !empty($filter_params['loai_man_hinh'])): ?>
                <div class="active-filters">
                    <small>
                        <strong>Đang lọc:</strong>
                        <?php 
                        $filters = [];
                        if (!empty($filter_params['ma_rap']) && $filter_params['ma_rap'] != 'all') {
                            $rap_name = '';
                            foreach ($danhSachRap as $rap) {
                                if ($rap['ma_rap'] == $filter_params['ma_rap']) {
                                    $rap_name = $rap['ten_rap'];
                                    break;
                                }
                            }
                            $filters[] = "Rạp: " . htmlspecialchars($rap_name);
                        }
                        if (!empty($filter_params['loai_man_hinh']) && $filter_params['loai_man_hinh'] != 'all') {
                            $filters[] = "Màn hình: " . htmlspecialchars($filter_params['loai_man_hinh']);
                        }
                        if (!empty($filter_params['search'])) {
                            $filters[] = "Tìm kiếm: " . htmlspecialchars($filter_params['search']);
                        }
                        echo implode(', ', $filters);
                        ?>
                    </small>
                </div>
            <?php endif; ?>

            <!-- FORM THÊM MỚI -->
            <?php if (isset($action) && $action === 'create'): ?>
            <div class="form-container">
                <h4>THÊM PHÒNG MỚI</h4>
                <form action="" method="POST" class="form-grid">
                    <input type="hidden" name="controller" value="adminPhong">
                    <input type="hidden" name="action" value="store">

                    <div class="form-group">
                        <label>Tên Phòng *</label>
                        <input type="text" name="ten_phong" placeholder="Tên phòng" required>
                    </div>
                    <div class="form-group">
                        <label>Rạp *</label>
                        <select name="ma_rap" required>
                            <option value="">Chọn rạp</option>
                            <?php foreach ($danhSachRap as $rap): ?>
                                <option value="<?php echo $rap['ma_rap']; ?>">
                                    <?php echo htmlspecialchars($rap['ten_rap']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Loại màn hình *</label>
                        <input type="text" name="loai_man_hinh" 
                                placeholder="Ví dụ: 2D, 3D..." 
                                required>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-save">Lưu</button>
                        <a href="index.php?controller=adminPhong&action=index" class="btn-cancel">Hủy</a>
                    </div>
                </form>
            </div>
            <?php endif; ?>

            <!-- FORM CHỈNH SỬA -->
            <?php if (isset($edit_id) && isset($phong_to_edit)): ?>
            <div class="form-container">
                <h4>CHỈNH SỬA PHÒNG</h4>
                <form action="index.php?controller=adminPhong&action=update" method="POST" class="form-grid">
                    <input type="hidden" name="ma_phong" value="<?php echo $phong_to_edit['ma_phong']; ?>">
                    <div class="form-group">
                        <label>Tên Phòng *</label>
                        <input type="text" name="ten_phong" value="<?php echo htmlspecialchars($phong_to_edit['ten_phong']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Rạp *</label>
                        <select name="ma_rap" required>
                            <?php foreach ($danhSachRap as $rap): ?>
                                <option value="<?php echo $rap['ma_rap']; ?>" 
                                    <?php echo ($phong_to_edit['ma_rap'] == $rap['ma_rap']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($rap['ten_rap']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Loại màn hình *</label>
                        <input type="text" name="loai_man_hinh" 
                                value="<?php echo isset($phong_to_edit['loai_man_hinh']) ? htmlspecialchars($phong_to_edit['loai_man_hinh']) : ''; ?>" 
                                placeholder="Ví dụ: 2D, 3D..." 
                                required>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-save">Lưu</button>
                        <a href="index.php?controller=adminPhong&action=index" class="btn-cancel">Hủy</a>
                    </div>
                </form>
            </div>
            <?php endif; ?>

            <!-- SỐ LƯỢNG KẾT QUẢ -->
            <?php if (!isset($action) || $action !== 'create'): ?>
                <div class="result-count">
                    <span>Hiển thị <?php echo count($danhSachPhong); ?> phòng</span>
                </div>
            <?php endif; ?>

            <!-- BẢNG DỮ LIỆU -->
            <section class="data-section">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên phòng</th>
                                <th>Rạp</th>
                                <th>Số lượng ghế</th>
                                <th>Màn hình</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($danhSachPhong)): ?>
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 30px;">
                                        <?php if (!empty($filter_params['ma_rap']) || !empty($filter_params['search']) || !empty($filter_params['loai_man_hinh'])): ?>
                                            Không tìm thấy phòng nào phù hợp với bộ lọc.
                                            <br>
                                            <a href="index.php?controller=adminPhong&action=index" style="color: #4a90e2; text-decoration: underline;">
                                                Xem tất cả phòng
                                            </a>
                                        <?php else: ?>
                                            Chưa có phòng nào.
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($danhSachPhong as $phong): ?>
                                    <tr>
                                        <td><?php echo $phong['ma_phong']; ?></td>
                                        <td><?php echo htmlspecialchars($phong['ten_phong']); ?></td>
                                        <td><?php echo htmlspecialchars($phong['ten_rap']); ?></td>
                                        <td><?php echo $phong['so_luong_ghe']; ?></td>
                                        <td><?php echo $phong['loai_man_hinh']; ?></td>
                                        <td>
                                            <a href="index.php?controller=adminPhong&action=manageSeats&ma_phong=<?php echo $phong['ma_phong']; ?>" class="action-btn edit-btn">Xem ghế</a>
                                            <a href="index.php?controller=adminPhong&action=edit&id=<?php echo $phong['ma_phong']; ?>" class="action-btn edit-btn">Sửa</a>
                                            <a href="index.php?controller=adminPhong&action=destroy&id=<?php echo $phong['ma_phong']; ?>" class="action-btn delete-btn" onclick="return confirm('Bạn có chắc chắn muốn xóa phòng này?');">Xóa</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- PHÂN TRANG (CÓ THỂ THÊM SAU NẾU CẦN) -->
            <?php if (count($danhSachPhong) > 0 && !isset($action)): ?>
                <div class="pagination">
                    <div class="pagination-info">
                        Hiển thị tất cả <?php echo count($danhSachPhong); ?> phòng
                    </div>
                </div>
            <?php endif; ?>
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
                    case 'add_success':
                        message = 'Thêm phòng thành công!';
                        type = 'success';
                        break;
                    case 'add_error':
                        message = 'Có lỗi xảy ra khi thêm phòng!';
                        type = 'error';
                        break;
                    case 'update_success':
                        message = 'Cập nhật phòng thành công!';
                        type = 'success';
                        break;
                    case 'update_error':
                        message = 'Có lỗi xảy ra khi cập nhật phòng!';
                        type = 'error';
                        break;
                    case 'delete_success':
                        message = 'Xóa phòng thành công!';
                        type = 'success';
                        break;
                    case 'delete_error_fk':
                        message = 'Không thể xóa phòng vì có ghế hoặc suất chiếu liên quan!';
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