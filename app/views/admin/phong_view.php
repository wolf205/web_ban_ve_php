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
    <link rel="stylesheet" href="publics/css/admin-room1.css" />
</head>
<body>
    <!-- INCLUDE HEADER VÀ SIDEBAR CHUNG -->
    <?php include __DIR__ . '/../chung/header_sidebar.php'; ?>

    <main class="main-content">
        <!-- HEADER TRANG VỚI TIÊU ĐỀ VÀ NÚT THÊM PHÒNG -->
        <div class="page-header">
            <h3>DANH SÁCH PHÒNG CHIẾU</h3>
            <!-- NÚT THÊM PHÒNG MỚI (GIỮ LẠI TRANG HIỆN TẠI) -->
            <a href="index.php?controller=adminPhong&action=create&page=<?php echo $page; ?>" class="add-btn">+ Thêm Phòng</a>
        </div>

        <!-- PHẦN BỘ LỌC VÀ TÌM KIẾM -->
        <div class="filter-section">
            <h4>BỘ LỌC & TÌM KIẾM</h4>
            <form method="GET" action="" class="filter-form">
                <!-- CÁC TRƯỜNG ẨN ĐỂ XÁC ĐỊNH CONTROLLER VÀ ACTION -->
                <input type="hidden" name="controller" value="adminPhong">
                <input type="hidden" name="action" value="index">
                
                <div class="filter-row">
                    <!-- LỌC THEO RẠP -->
                    <div class="filter-group">
                        <label for="ma_rap">Rạp:</label>
                        <select name="ma_rap" id="ma_rap">
                            <option value="all">Tất cả rạp</option>
                            <?php 
                            // LẤY RẠP ĐANG ĐƯỢC CHỌN TỪ BỘ LỌC
                            $selected_rap = $filter_params['ma_rap'] ?? null;
                            // HIỂN THỊ DANH SÁCH RẠP TRONG DROPDOWN
                            foreach ($danhSachRap as $rap): 
                            ?>
                                <option value="<?php echo $rap['ma_rap']; ?>" 
                                    <?php echo ($selected_rap == $rap['ma_rap']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($rap['ten_rap']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- LỌC THEO LOẠI MÀN HÌNH -->
                    <div class="filter-group">
                        <label for="loai_man_hinh">Loại màn hình:</label>
                        <select name="loai_man_hinh" id="loai_man_hinh">
                            <option value="all">Tất cả loại</option>
                            <?php 
                            // LẤY LOẠI MÀN HÌNH ĐANG ĐƯỢC CHỌN
                            $selected_screen = $filter_params['loai_man_hinh'] ?? null;
                            // HIỂN THỊ DANH SÁCH LOẠI MÀN HÌNH
                            foreach ($loai_man_hinh_list as $type): 
                            ?>
                                <option value="<?php echo htmlspecialchars($type); ?>" 
                                    <?php echo ($selected_screen == $type) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($type); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- TÌM KIẾM THEO TỪ KHÓA -->
                    <div class="filter-group">
                        <label for="search">Tìm kiếm:</label>
                        <input type="text" name="search" id="search" 
                               placeholder="Tên phòng hoặc rạp" 
                               value="<?php echo htmlspecialchars($filter_params['search'] ?? ''); ?>">
                    </div>
                    
                    <!-- CÁC NÚT HÀNH ĐỘNG CHO BỘ LỌC -->
                    <div class="filter-actions">
                        <button type="submit" class="btn-filter">Lọc</button>
                        <a href="index.php?controller=adminPhong&action=index" class="btn-reset">Xóa lọc</a>
                    </div>
                </div>
            </form>
        </div>

        <!-- HIỂN THỊ THÔNG TIN BỘ LỌC ĐANG ÁP DỤNG (NẾU CÓ) -->
        <?php if (!empty($filter_params['ma_rap']) || !empty($filter_params['search']) || !empty($filter_params['loai_man_hinh'])): ?>
            <div class="active-filters">
                <small>
                    <strong>Đang lọc:</strong>
                    <?php 
                    $filters = [];
                    // THÊM THÔNG TIN LỌC THEO RẠP (NẾU CÓ)
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
                    // THÊM THÔNG TIN LỌC THEO LOẠI MÀN HÌNH (NẾU CÓ)
                    if (!empty($filter_params['loai_man_hinh']) && $filter_params['loai_man_hinh'] != 'all') {
                        $filters[] = "Màn hình: " . htmlspecialchars($filter_params['loai_man_hinh']);
                    }
                    // THÊM THÔNG TIN TÌM KIẾM (NẾU CÓ)
                    if (!empty($filter_params['search'])) {
                        $filters[] = "Tìm kiếm: " . htmlspecialchars($filter_params['search']);
                    }
                    // HIỂN THỊ TẤT CẢ CÁC BỘ LỌC ĐANG ÁP DỤNG
                    echo implode(', ', $filters);
                    ?>
                </small>
            </div>
        <?php endif; ?>

        <!-- FORM THÊM PHÒNG MỚI (CHỈ HIỂN THỊ KHI ACTION = 'create') -->
        <?php if (isset($action) && $action === 'create'): ?>
        <div class="form-container">
            <h4>THÊM PHÒNG MỚI</h4>
            <!-- FORM XỬ LÝ TRONG TRANG (DÙNG POST) -->
            <form action="" method="POST" class="form-grid">
                <input type="hidden" name="controller" value="adminPhong">
                <input type="hidden" name="action" value="store">
                <!-- GIỮ LẠI TRANG HIỆN TẠI ĐỂ QUAY LẠI ĐÚNG VỊ TRÍ -->
                <input type="hidden" name="page" value="<?php echo isset($_GET['page']) ? (int)$_GET['page'] : 1; ?>">

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
                    <a href="index.php?controller=adminPhong&action=index&page=<?php echo isset($_GET['page']) ? (int)$_GET['page'] : 1; ?>" class="btn-cancel">Hủy</a>
                </div>
            </form>
        </div>
        <?php endif; ?>

        <!-- FORM CHỈNH SỬA PHÒNG (CHỈ HIỂN THỊ KHI CÓ PHÒNG CẦN SỬA) -->
        <?php if (isset($edit_id) && isset($phong_to_edit)): ?>
        <div class="form-container">
            <h4>CHỈNH SỬA PHÒNG</h4>
            <form action="index.php?controller=adminPhong&action=update" method="POST" class="form-grid">
                <input type="hidden" name="ma_phong" value="<?php echo $phong_to_edit['ma_phong']; ?>">
                <!-- GIỮ LẠI TRANG HIỆN TẠI -->
                <input type="hidden" name="page" value="<?php echo isset($_GET['page']) ? (int)$_GET['page'] : 1; ?>">
                <div class="form-group">
                    <label>Tên Phòng *</label>
                    <!-- ĐIỀN SẴN DỮ LIỆU HIỆN TẠI -->
                    <input type="text" name="ten_phong" value="<?php echo htmlspecialchars($phong_to_edit['ten_phong']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Rạp *</label>
                    <select name="ma_rap" required>
                        <?php foreach ($danhSachRap as $rap): ?>
                            <!-- CHỌN RẠP HIỆN TẠI CỦA PHÒNG -->
                            <option value="<?php echo $rap['ma_rap']; ?>" 
                                <?php echo ($phong_to_edit['ma_rap'] == $rap['ma_rap']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($rap['ten_rap']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Loại màn hình *</label>
                    <!-- ĐIỀN SẴN LOẠI MÀN HÌNH HIỆN TẠI -->
                    <input type="text" name="loai_man_hinh" 
                            value="<?php echo isset($phong_to_edit['loai_man_hinh']) ? htmlspecialchars($phong_to_edit['loai_man_hinh']) : ''; ?>" 
                            placeholder="Ví dụ: 2D, 3D..." 
                            required>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-save">Lưu</button>
                    <a href="index.php?controller=adminPhong&action=index&page=<?php echo isset($_GET['page']) ? (int)$_GET['page'] : 1; ?>" class="btn-cancel">Hủy</a>
                </div>
            </form>
        </div>
        <?php endif; ?>

        <!-- HIỂN THỊ SỐ LƯỢNG PHÒNG (CHỈ KHI KHÔNG CÓ FORM THÊM MỚI) -->
        <?php if (!isset($action) || $action !== 'create'): ?>
            <div class="result-count">
                <span>Hiển thị <?php echo count($danhSachPhong); ?> phòng</span>
            </div>
        <?php endif; ?>

        <!-- PHẦN BẢNG DỮ LIỆU -->
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
                        <!-- KIỂM TRA NẾU KHÔNG CÓ PHÒNG NÀO -->
                        <?php if (empty($danhSachPhong)): ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 30px;">
                                    <?php if (!empty($filter_params['ma_rap']) || !empty($filter_params['search']) || !empty($filter_params['loai_man_hinh'])): ?>
                                        <!-- THÔNG BÁO KHI CÓ BỘ LỌC NHƯNG KHÔNG CÓ KẾT QUẢ -->
                                        Không tìm thấy phòng nào phù hợp với bộ lọc.
                                        <br>
                                        <a href="index.php?controller=adminPhong&action=index" style="color: #4a90e2; text-decoration: underline;">
                                            Xem tất cả phòng
                                        </a>
                                    <?php else: ?>
                                        <!-- THÔNG BÁO KHI CHƯA CÓ PHÒNG NÀO -->
                                        Chưa có phòng nào.
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php else: ?>
                            <!-- LẶP QUA DANH SÁCH PHÒNG VÀ HIỂN THỊ -->
                            <?php foreach ($danhSachPhong as $phong): ?>
                                <tr>
                                    <td><?php echo $phong['ma_phong']; ?></td>
                                    <td><?php echo htmlspecialchars($phong['ten_phong']); ?></td>
                                    <td><?php echo htmlspecialchars($phong['ten_rap']); ?></td>
                                    <td><?php echo $phong['so_luong_ghe']; ?></td>
                                    <td><?php echo $phong['loai_man_hinh']; ?></td>
                                    <td>
                                        <!-- NÚT XEM GHẾ (CHUYỂN ĐẾN TRANG QUẢN LÝ GHẾ) -->
                                        <a href="index.php?controller=adminPhong&action=manageSeats&ma_phong=<?php echo $phong['ma_phong']; ?>" class="action-btn edit-btn">Xem ghế</a>
                                        <!-- NÚT SỬA PHÒNG (GIỮ LẠI TRANG HIỆN TẠI) -->
                                        <a href="index.php?controller=adminPhong&action=edit&id=<?php echo $phong['ma_phong']; ?>&page=<?php echo isset($_GET['page']) ? (int)$_GET['page'] : 1; ?>" class="action-btn edit-btn">Sửa</a>
                                        <!-- NÚT XÓA PHÒNG (CÓ XÁC NHẬN, GIỮ TRANG HIỆN TẠI) -->
                                        <a href="index.php?controller=adminPhong&action=destroy&id=<?php echo $phong['ma_phong']; ?>&page=<?php echo isset($_GET['page']) ? (int)$_GET['page'] : 1; ?>" class="action-btn delete-btn" onclick="return confirm('Bạn có chắc chắn muốn xóa phòng này?');">Xóa</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- PHÂN TRANG (CHỈ HIỂN THỊ KHI CÓ NHIỀU TRANG VÀ KHÔNG CÓ FORM) -->
            <?php if (isset($totalPages) && $totalPages > 1): ?>
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
            'add_success' => 'Thêm phòng thành công!',
            'add_error' => 'Có lỗi xảy ra khi thêm phòng!',
            'update_success' => 'Cập nhật phòng thành công!',
            'update_error' => 'Có lỗi xảy ra khi cập nhật phòng!',
            'delete_success' => 'Xóa phòng thành công!',
            'delete_error' => 'Có lỗi xảy ra khi xóa phòng!',
            'delete_error_fk' => 'Không thể xóa phòng vì có ghế hoặc suất chiếu liên quan!',
            'not_found' => 'Không tìm thấy dữ liệu!'
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