<?php
// app/views/admin/phong_view.php
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quản lý Phòng chiếu & Ghế</title>
    <link rel="stylesheet" href="publics/css/admin-layout1.css" />
    <link rel="stylesheet" href="publics/css/admin-room.css" />
</head>
<body>
   <?php include __DIR__ . '/../chung/header_sidebar.php'; ?>

        <main class="main-content">
            <div class="page-header">
                <h3>DANH SÁCH PHÒNG CHIẾU</h3>
                <button type="button" class="add-btn" onclick="toggleForm('add-phong-form')">+ Thêm phòng</button>
            </div>

            <!-- BỘ LỌC & TÌM KIẾM -->
<div class="filter-section">
    <h4>BỘ LỌC & TÌM KIẾM</h4>
    <form method="GET" action="" class="filter-form">
        <input type="hidden" name="controller" value="adminPhong">
        <input type="hidden" name="action" value="index">
        <?php if (isset($selected_phong_id)): ?>
            <input type="hidden" name="selected_phong" value="<?php echo $selected_phong_id; ?>">
        <?php endif; ?>
        
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
                    $screen_types = $loai_man_hinh_list ?? [];
                    foreach ($screen_types as $type): 
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
                <a href="index.php?controller=adminPhong&action=index<?php echo isset($selected_phong_id) ? '&selected_phong=' . $selected_phong_id : ''; ?>" class="btn-reset">Xóa lọc</a>
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
            <a href="index.php?controller=adminPhong&action=index<?php echo isset($selected_phong_id) ? '&selected_phong=' . $selected_phong_id : ''; ?>" style="margin-left: 10px; color: #e74c3c;">
                [Xóa tất cả]
            </a>
        </small>
    </div>
<?php endif; ?>

            <!-- Form thêm phòng -->
            <div id="add-phong-form" class="form-container" style="display: none;">
                <h4>THÊM PHÒNG MỚI</h4>
                <form action="index.php?controller=adminPhong&action=store" method="POST" class="form-grid">
                    <input type="hidden" name="add_phong" value="1">
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
                        <select name="loai_man_hinh" required>
                            <option value="">Chọn loại màn hình</option>
                            <option value="2D">2D</option>
                            <option value="3D">3D</option>
                            <option value="IMAX">IMAX</option>
                            <option value="4DX">4DX</option>
                        </select>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-save">Lưu</button>
                        <button type="button" class="btn-cancel" onclick="toggleForm('add-phong-form')">Hủy</button>
                    </div>
                </form>
            </div>

            <section class="data-section">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID phòng</th>
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
                                    <td colspan="6" style="text-align: center;">Chưa có phòng nào.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($danhSachPhong as $phong): ?>
                                    <tr class="<?php echo ($selected_phong_id == $phong['ma_phong']) ? 'selected-row' : ''; ?>">
                                        <td><?php echo $phong['ma_phong']; ?></td>
                                        <td><?php echo htmlspecialchars($phong['ten_phong']); ?></td>
                                        <td><?php echo htmlspecialchars($phong['ten_rap']); ?></td>
                                        <td><?php echo $phong['so_luong_ghe']; ?></td>
                                        <td><?php echo $phong['loai_man_hinh']; ?></td>
                                        <td>
                                            <a href="index.php?controller=adminPhong&action=index&selected_phong=<?php echo $phong['ma_phong']; ?>" class="action-btn edit-btn">Xem ghế</a>
                                            <button type="button" class="action-btn edit-btn" onclick="editPhong(<?php echo htmlspecialchars(json_encode($phong)); ?>)">Sửa</button>
                                            <a href="index.php?controller=adminPhong&action=destroy&id=<?php echo $phong['ma_phong']; ?>" class="action-btn delete-btn" onclick="return confirm('Bạn có chắc chắn muốn xóa phòng này?');">Xóa</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Phần quản lý ghế (chỉ hiển thị khi có selected_phong) -->
            <?php if (isset($selected_phong_info)): ?>
            <section class="data-section" style="margin-top: 30px" id="seat-manager">
                <div class="page-header">
                    <h3>QUẢN LÝ GHẾ - <?php echo htmlspecialchars($selected_phong_info['ten_phong']); ?> (<?php echo htmlspecialchars($selected_phong_info['ten_rap']); ?>)</h3>
                    <button type="button" class="add-btn" onclick="toggleForm('add-ghe-form')">+ Thêm ghế</button>
                </div>

                <!-- Form thêm ghế (ẩn/hiện) -->
                <div id="add-ghe-form" class="form-container" style="display: none;">
                    <h4>THÊM GHẾ MỚI</h4>
                    <form action="index.php?controller=adminPhong&action=addGhe" method="POST" class="form-grid">
                        <input type="hidden" name="add_ghe" value="1">
                        <input type="hidden" name="ma_phong" value="<?php echo $selected_phong_info['ma_phong']; ?>">
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
                            <button type="button" class="btn-cancel" onclick="toggleForm('add-ghe-form')">Hủy</button>
                        </div>
                    </form>
                </div>

                <!-- Form sửa ghế (ẩn/hiện) -->
                <div id="edit-ghe-form" class="form-container" style="display: none;">
                    <h4>CHỈNH SỬA GHẾ</h4>
                    <form action="index.php?controller=adminPhong&action=updateGhe" method="POST" class="form-grid">
                        <input type="hidden" name="update_ghe" value="1">
                        <input type="hidden" name="ma_ghe" id="edit_ma_ghe">
                        <input type="hidden" name="ma_phong" value="<?php echo $selected_phong_info['ma_phong']; ?>">
                        <div class="form-group">
                            <label>Vị trí *</label>
                            <input type="text" name="vi_tri" id="edit_vi_tri" required>
                        </div>
                        <div class="form-group">
                            <label>Loại ghế *</label>
                            <select name="loai_ghe" id="edit_loai_ghe" required>
                                <option value="Thường">Thường</option>
                                <option value="VIP">VIP</option>
                                <option value="Đôi">Đôi</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Trạng thái *</label>
                            <select name="trang_thai" id="edit_trang_thai" required>
                                <option value="Hoạt động">Hoạt động</option>
                                <option value="Bảo trì">Bảo trì</option>
                                <option value="Hỏng">Hỏng</option>
                            </select>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn-save">Lưu</button>
                            <button type="button" class="btn-cancel" onclick="toggleForm('edit-ghe-form')">Hủy</button>
                        </div>
                    </form>
                </div>

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
                                    <td colspan="5" style="text-align: center;">Chưa có ghế nào trong phòng này.</td>
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
                                            <button type="button" class="action-btn edit-btn" onclick="editGhe(<?php echo htmlspecialchars(json_encode($ghe)); ?>)">Sửa</button>
                                            <a href="index.php?controller=adminPhong&action=destroyGhe&ma_ghe=<?php echo $ghe['ma_ghe']; ?>&ma_phong=<?php echo $selected_phong_info['ma_phong']; ?>" class="action-btn delete-btn" onclick="return confirm('Bạn có chắc chắn muốn xóa ghế này?');">Xóa</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
            <?php endif; ?>
        </main>
    </div>

    <script>
        function toggleForm(formId) {
            const form = document.getElementById(formId);
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }

        function editPhong(phong) {
            // Tạo form sửa phòng động
            const formHtml = `
                <div class="form-container">
                    <h4>CHỈNH SỬA PHÒNG</h4>
                    <form action="index.php?controller=adminPhong&action=update" method="POST" class="form-grid">
                        <input type="hidden" name="update_phong" value="1">
                        <input type="hidden" name="ma_phong" value="${phong.ma_phong}">
                        <div class="form-group">
                            <label>Tên Phòng *</label>
                            <input type="text" name="ten_phong" value="${phong.ten_phong}" required>
                        </div>
                        <div class="form-group">
                            <label>Rạp *</label>
                            <select name="ma_rap" required>
                                <?php foreach ($danhSachRap as $rap): ?>
                                    <option value="<?php echo $rap['ma_rap']; ?>" ${phong.ma_rap == '<?php echo $rap['ma_rap']; ?>' ? 'selected' : ''}>
                                        <?php echo htmlspecialchars($rap['ten_rap']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Loại màn hình *</label>
                            <select name="loai_man_hinh" required>
                                <option value="2D" ${phong.loai_man_hinh == '2D' ? 'selected' : ''}>2D</option>
                                <option value="3D" ${phong.loai_man_hinh == '3D' ? 'selected' : ''}>3D</option>
                                <option value="IMAX" ${phong.loai_man_hinh == 'IMAX' ? 'selected' : ''}>IMAX</option>
                                <option value="4DX" ${phong.loai_man_hinh == '4DX' ? 'selected' : ''}>4DX</option>
                            </select>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn-save">Lưu</button>
                            <button type="button" class="btn-cancel" onclick="this.closest('.form-container').remove()">Hủy</button>
                        </div>
                    </form>
                </div>
            `;
            
            // Chèn form vào trước bảng
            const tableContainer = document.querySelector('.table-container');
            tableContainer.insertAdjacentHTML('afterbegin', formHtml);
        }

        function editGhe(ghe) {
            document.getElementById('edit_ma_ghe').value = ghe.ma_ghe;
            document.getElementById('edit_vi_tri').value = ghe.vi_tri;
            document.getElementById('edit_loai_ghe').value = ghe.loai_ghe;
            document.getElementById('edit_trang_thai').value = ghe.tinh_trang;
            toggleForm('edit-ghe-form');
        }
    </script>

    <?php if (isset($_GET['status'])): ?>
        <script>
            <?php
            $statusMessages = [
                'add_success' => 'Thêm phòng thành công!',
                'add_error' => 'Lỗi khi thêm phòng!',
                'update_success' => 'Cập nhật phòng thành công!',
                'update_error' => 'Lỗi khi cập nhật phòng!',
                'delete_success' => 'Xóa phòng thành công!',
                'delete_error_fk' => 'Không thể xóa phòng vì có ghế liên quan!',
                'add_ghe_success' => 'Thêm ghế thành công!',
                'add_ghe_error' => 'Lỗi khi thêm ghế!',
                'update_ghe_success' => 'Cập nhật ghế thành công!',
                'update_ghe_error' => 'Lỗi khi cập nhật ghế!',
                'delete_ghe_success' => 'Xóa ghế thành công!',
                'delete_ghe_error' => 'Lỗi khi xóa ghế!',
                'not_found' => 'Không tìm thấy dữ liệu!'
            ];
            
            if (isset($statusMessages[$_GET['status']])) {
                echo 'alert("' . $statusMessages[$_GET['status']] . '");';
            }
            ?>
        </script>
    <?php endif; ?>
</body>
</html>