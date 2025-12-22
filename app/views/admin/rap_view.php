<?php
// app/views/admin/rap_view.php
// Các biến $danhSachRap, $action, $edit_id, $rap_to_edit, $cities, $filter_params
// được truyền từ AdminRapController
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quản lý Rạp</title>
    <link rel="stylesheet" href="publics/css/admin-layout1.css" />
    <link rel="stylesheet" href="publics/css/admin-rap.css" />
</head>
<body>
    <?php include __DIR__ . '/../chung/header_sidebar.php'; ?>

        <main class="main-content">
            <!-- PAGE HEADER -->
            <div class="page-header">
                <h3>DANH SÁCH RẠP</h3>
                <a href="index.php?controller=adminRap&action=create" class="add-btn">+ Thêm Rạp</a>
            </div>

            <!-- BỘ LỌC & TÌM KIẾM -->
            <div class="filter-section">
                <h4>BỘ LỌC & TÌM KIẾM</h4>
                <form method="GET" action="" class="filter-form">
                    <input type="hidden" name="controller" value="adminRap">
                    <input type="hidden" name="action" value="index">
                    
                    <div class="filter-row">
                        <div class="filter-group">
                            <label for="thanh_pho">Thành phố:</label>
                            <select name="thanh_pho" id="thanh_pho">
                                <option value="all">Tất cả thành phố</option>
                                <?php 
                                // Lấy danh sách thành phố từ controller
                                $cities = $cities ?? [];
                                $selected_city = $filter_params['thanh_pho'] ?? null;
                                foreach ($cities as $city): 
                                ?>
                                    <option value="<?php echo htmlspecialchars($city); ?>" 
                                        <?php echo ($selected_city == $city) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($city); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label for="search">Tìm kiếm:</label>
                            <input type="text" name="search" id="search" 
                                   placeholder="Tên rạp hoặc địa chỉ" 
                                   value="<?php echo htmlspecialchars($filter_params['search'] ?? ''); ?>">
                        </div>
                        
                        <div class="filter-actions">
                            <button type="submit" class="btn-filter">Lọc</button>
                            <a href="index.php?controller=adminRap&action=index" class="btn-reset">Xóa lọc</a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- THÔNG TIN BỘ LỌC ĐANG ÁP DỤNG -->
            <?php if (!empty($filter_params['thanh_pho']) || !empty($filter_params['search'])): ?>
                <div class="active-filters">
                    <small>
                        <strong>Đang lọc:</strong>
                        <?php 
                        $filters = [];
                        if (!empty($filter_params['thanh_pho']) && $filter_params['thanh_pho'] != 'all') {
                            $filters[] = "Thành phố: " . htmlspecialchars($filter_params['thanh_pho']);
                        }
                        if (!empty($filter_params['search'])) {
                            $filters[] = "Tìm kiếm: " . htmlspecialchars($filter_params['search']);
                        }
                        echo implode(', ', $filters);
                        ?>
                        <a href="index.php?controller=adminRap&action=index" style="margin-left: 10px; color: #e74c3c;">
                            [Xóa tất cả]
                        </a>
                    </small>
                </div>
            <?php endif; ?>

            <!-- FORM THÊM MỚI -->
            <?php if (isset($action) && $action === 'create'): ?>
            <div class="form-container">
                <h4>THÊM RẠP MỚI</h4>
                <form action="index.php?controller=adminRap&action=store" method="POST" enctype="multipart/form-data" class="form-grid">
                    <div class="form-group">
                        <label>Tên Rạp *</label>
                        <input type="text" name="ten_rap" placeholder="Tên rạp" required>
                    </div>
                    <div class="form-group">
                        <label>Thành phố *</label>
                        <input type="text" name="thanh_pho" placeholder="Thành phố" required>
                    </div>
                    <div class="form-group">
                        <label>Địa chỉ *</label>
                        <input type="text" name="dia_chi" placeholder="Địa chỉ" required>
                    </div>
                    <div class="form-group">
                        <label>Số điện thoại</label>
                        <input type="text" name="SDT" placeholder="Số điện thoại">
                    </div>
                    <div class="form-group form-group-full">
                        <label>Mô tả rạp</label>
                        <textarea name="mo_ta_rap" placeholder="Mô tả ngắn về rạp"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Ảnh rạp</label>
                        <input type="file" name="anh_rap" accept="image/*">
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-save">Lưu</button>
                        <a href="index.php?controller=adminRap&action=index" class="btn-cancel">Hủy</a>
                    </div>
                </form>
            </div>
            <?php endif; ?>

            <!-- FORM CHỈNH SỬA -->
            <?php if (isset($edit_id) && isset($rap_to_edit)): ?>
            <div class="form-container">
                <h4>CHỈNH SỬA RẠP</h4>
                <form action="index.php?controller=adminRap&action=update" method="POST" enctype="multipart/form-data" class="form-grid">
                    <input type="hidden" name="ma_rap" value="<?php echo $rap_to_edit['ma_rap']; ?>">
                    <div class="form-group">
                        <label>Tên Rạp *</label>
                        <input type="text" name="ten_rap" value="<?php echo htmlspecialchars($rap_to_edit['ten_rap']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Thành phố *</label>
                        <input type="text" name="thanh_pho" value="<?php echo htmlspecialchars($rap_to_edit['thanh_pho']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Địa chỉ *</label>
                        <input type="text" name="dia_chi" value="<?php echo htmlspecialchars($rap_to_edit['dia_chi']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Số điện thoại</label>
                        <input type="text" name="SDT" value="<?php echo htmlspecialchars($rap_to_edit['SDT']); ?>">
                    </div>
                    <div class="form-group form-group-full">
                        <label>Mô tả rạp</label>
                        <textarea name="mo_ta_rap"><?php echo htmlspecialchars($rap_to_edit['mo_ta_rap']); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Ảnh rạp</label>
                        <input type="file" name="anh_rap" accept="image/*">
                        <small class="file-help">Để trống nếu không đổi ảnh</small>
                        <?php if (!empty($rap_to_edit['anh_rap'])): ?>
                            <div class="image-preview">
                                <img src="<?php echo htmlspecialchars($rap_to_edit['anh_rap']); ?>" alt="Ảnh hiện tại">
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-save">Lưu</button>
                        <a href="index.php?controller=adminRap&action=index" class="btn-cancel">Hủy</a>
                    </div>
                </form>
            </div>
            <?php endif; ?>

            <!-- SỐ LƯỢNG KẾT QUẢ -->
<?php if (!isset($action) || $action !== 'create'): ?>
    <div class="result-count">
        <span>Hiển thị <?php echo count($danhSachRap); ?> rạp</span>
    </div>
<?php endif; ?>

            <!-- BẢNG DỮ LIỆU -->
            <section class="data-section">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên Rạp</th>
                                <th>Thành phố</th>
                                <th>Địa chỉ</th>
                                <th>SĐT</th>
                                <th>Số phòng</th>
                                <th>Ảnh</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($danhSachRap)): ?>
                                <tr>
                                    <td colspan="8" style="text-align: center; padding: 30px;">
                                        <?php if (!empty($filter_params['thanh_pho']) || !empty($filter_params['search'])): ?>
                                            Không tìm thấy rạp nào phù hợp với bộ lọc.
                                            <br>
                                            <a href="index.php?controller=adminRap&action=index" style="color: #4a90e2; text-decoration: underline;">
                                                Xem tất cả rạp
                                            </a>
                                        <?php else: ?>
                                            Chưa có rạp nào.
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($danhSachRap as $rap): ?>
                                    <tr>
                                        <td><?php echo $rap['ma_rap']; ?></td>
                                        <td><?php echo htmlspecialchars($rap['ten_rap']); ?></td>
                                        <td><?php echo htmlspecialchars($rap['thanh_pho']); ?></td>
                                        <td><?php echo htmlspecialchars($rap['dia_chi']); ?></td>
                                        <td><?php echo htmlspecialchars($rap['SDT']); ?></td>
                                        <td><?php echo $rap['so_phong']; ?></td>
                                        <td>
                                            <?php if (!empty($rap['anh_rap'])): ?>
                                                <img src="<?php echo htmlspecialchars($rap['anh_rap']); ?>" alt="Ảnh rạp" class="table-image">
                                            <?php else: ?>
                                                <span class="no-image">Không có ảnh</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="index.php?controller=adminRap&action=edit&id=<?php echo $rap['ma_rap']; ?>" class="action-btn edit-btn">Sửa</a>
                                            <a href="index.php?controller=adminRap&action=destroy&id=<?php echo $rap['ma_rap']; ?>" class="action-btn delete-btn" onclick="return confirm('Bạn có chắc chắn muốn xóa rạp này?');">Xóa</a>
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
                    case 'add_success':
                        message = 'Thêm rạp thành công!';
                        type = 'success';
                        break;
                    case 'add_error':
                        message = 'Có lỗi xảy ra khi thêm rạp!';
                        type = 'error';
                        break;
                    case 'update_success':
                        message = 'Cập nhật rạp thành công!';
                        type = 'success';
                        break;
                    case 'update_error':
                        message = 'Có lỗi xảy ra khi cập nhật rạp!';
                        type = 'error';
                        break;
                    case 'delete_success':
                        message = 'Xóa rạp thành công!';
                        type = 'success';
                        break;
                    case 'delete_error_fk':
                        message = 'Không thể xóa rạp vì có phòng hoặc suất chiếu liên quan!';
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