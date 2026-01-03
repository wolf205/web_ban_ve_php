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
    <!-- INCLUDE HEADER VÀ SIDEBAR CHUNG -->
    <?php include __DIR__ . '/../chung/header_sidebar.php'; ?>

    <main class="main-content">
        <!-- HEADER TRANG VỚI TIÊU ĐỀ VÀ NÚT THÊM RẠP -->
        <div class="page-header">
            <h3>DANH SÁCH RẠP</h3>
            <!-- NÚT THÊM RẠP MỚI -->
            <a href="index.php?controller=adminRap&action=create&page=<?php echo $page; ?>" class="add-btn">+ Thêm Rạp</a>
        </div>

        <!-- PHẦN BỘ LỌC VÀ TÌM KIẾM RẠP -->
        <div class="filter-section">
            <h4>BỘ LỌC & TÌM KIẾM</h4>
            <form method="GET" action="" class="filter-form">
                <!-- CÁC TRƯỜNG ẨN ĐỂ XÁC ĐỊNH CONTROLLER VÀ ACTION -->
                <input type="hidden" name="controller" value="adminRap">
                <input type="hidden" name="action" value="index">
                
                <div class="filter-row">
                    <!-- LỌC THEO THÀNH PHỐ -->
                    <div class="filter-group">
                        <label for="thanh_pho">Thành phố:</label>
                        <select name="thanh_pho" id="thanh_pho">
                            <option value="all">Tất cả thành phố</option>
                            <?php 
                            // LẤY DANH SÁCH THÀNH PHỐ TỪ CONTROLLER
                            $cities = $cities ?? [];
                            // LẤY THÀNH PHỐ ĐANG ĐƯỢC CHỌN TỪ BỘ LỌC
                            $selected_city = $filter_params['thanh_pho'] ?? null;
                            // HIỂN THỊ DANH SÁCH THÀNH PHỐ TRONG DROPDOWN
                            foreach ($cities as $city): 
                            ?>
                                <option value="<?php echo htmlspecialchars($city); ?>" 
                                    <?php echo ($selected_city == $city) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($city); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- TÌM KIẾM THEO TỪ KHÓA -->
                    <div class="filter-group">
                        <label for="search">Tìm kiếm:</label>
                        <input type="text" name="search" id="search" 
                               placeholder="Tên rạp hoặc địa chỉ" 
                               value="<?php echo htmlspecialchars($filter_params['search'] ?? ''); ?>">
                    </div>
                    
                    <!-- CÁC NÚT HÀNH ĐỘNG CHO BỘ LỌC -->
                    <div class="filter-actions">
                        <button type="submit" class="btn-filter">Lọc</button>
                        <a href="index.php?controller=adminRap&action=index" class="btn-reset">Xóa lọc</a>
                    </div>
                </div>
            </form>
        </div>

        <!-- HIỂN THỊ THÔNG TIN BỘ LỌC ĐANG ÁP DỤNG (NẾU CÓ) -->
        <?php if (!empty($filter_params['thanh_pho']) || !empty($filter_params['search'])): ?>
            <div class="active-filters">
                <small>
                    <strong>Đang lọc:</strong>
                    <?php 
                    $filters = [];
                    // THÊM THÔNG TIN LỌC THEO THÀNH PHỐ (NẾU CÓ)
                    if (!empty($filter_params['thanh_pho']) && $filter_params['thanh_pho'] != 'all') {
                        $filters[] = "Thành phố: " . htmlspecialchars($filter_params['thanh_pho']);
                    }
                    // THÊM THÔNG TIN TÌM KIẾM (NẾU CÓ)
                    if (!empty($filter_params['search'])) {
                        $filters[] = "Tìm kiếm: " . htmlspecialchars($filter_params['search']);
                    }
                    // HIỂN THỊ TẤT CẢ CÁC BỘ LỌC ĐANG ÁP DỤNG
                    echo implode(', ', $filters);
                    ?>
                    <!-- NÚT XÓA TẤT CẢ BỘ LỌC -->
                    <a href="index.php?controller=adminRap&action=index" style="margin-left: 10px; color: #e74c3c;">
                        [Xóa tất cả]
                    </a>
                </small>
            </div>
        <?php endif; ?>

        <!-- FORM THÊM RẠP MỚI (CHỈ HIỂN THỊ KHI ACTION = 'create') -->
        <?php if (isset($action) && $action === 'create'): ?>
        <div class="form-container">
            <h4>THÊM RẠP MỚI</h4>
            <!-- FORM THÊM RẠP VỚI ENCTYPE CHO UPLOAD FILE -->
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
                <!-- TEXTAREA CHIẾM TOÀN BỘ CHIỀU RỘNG -->
                <div class="form-group form-group-full">
                    <label>Mô tả rạp</label>
                    <textarea name="mo_ta_rap" placeholder="Mô tả ngắn về rạp"></textarea>
                </div>
                <!-- INPUT FILE ĐỂ UPLOAD ẢNH RẠP -->
                <div class="form-group">
                    <label>Ảnh rạp</label>
                    <input type="file" name="anh_rap" accept="image/*">
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-save">Lưu</button>
                    <a href="index.php?controller=adminRap&action=index&page=<?php echo $page; ?>" class="btn-cancel">Hủy</a>
                </div>
            </form>
        </div>
        <?php endif; ?>

        <!-- FORM CHỈNH SỬA RẠP (CHỈ HIỂN THỊ KHI CÓ RẠP CẦN SỬA) -->
        <?php if (isset($edit_id) && isset($rap_to_edit)): ?>
        <div class="form-container">
            <h4>CHỈNH SỬA RẠP</h4>
            <form action="index.php?controller=adminRap&action=update" method="POST" enctype="multipart/form-data" class="form-grid">
                <!-- ẨN MÃ RẠP ĐỂ SỬ DỤNG KHI UPDATE -->
                <input type="hidden" name="ma_rap" value="<?php echo $rap_to_edit['ma_rap']; ?>">
                <div class="form-group">
                    <label>Tên Rạp *</label>
                    <!-- ĐIỀN SẴN DỮ LIỆU HIỆN TẠI -->
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
                    <!-- INPUT FILE ĐỂ THAY ĐỔI ẢNH (KHÔNG BẮT BUỘC) -->
                    <input type="file" name="anh_rap" accept="image/*">
                    <small class="file-help">Để trống nếu không đổi ảnh</small>
                    <!-- HIỂN THỊ ẢNH HIỆN TẠI NẾU CÓ -->
                    <?php if (!empty($rap_to_edit['anh_rap'])): ?>
                        <div class="image-preview">
                            <img src="<?php echo htmlspecialchars($rap_to_edit['anh_rap']); ?>" alt="Ảnh hiện tại">
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-save">Lưu</button>
                    <a href="index.php?controller=adminRap&action=index&page=<?php echo $page; ?>" class="btn-cancel">Hủy</a>
                </div>
            </form>
        </div>
        <?php endif; ?>

        <!-- HIỂN THỊ SỐ LƯỢNG KẾT QUẢ (CHỈ KHI KHÔNG CÓ FORM THÊM MỚI) -->
        <?php if (!isset($action) || $action !== 'create'): ?>
            <div class="result-count">
                <span>Hiển thị <?php echo count($danhSachRap); ?> rạp</span>
            </div>
        <?php endif; ?>

        <!-- PHẦN BẢNG DỮ LIỆU RẠP -->
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
                        <!-- KIỂM TRA NẾU KHÔNG CÓ RẠP NÀO -->
                        <?php if (empty($danhSachRap)): ?>
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 30px;">
                                    <?php if (!empty($filter_params['thanh_pho']) || !empty($filter_params['search'])): ?>
                                        <!-- THÔNG BÁO KHI CÓ BỘ LỌC NHƯNG KHÔNG CÓ KẾT QUẢ -->
                                        Không tìm thấy rạp nào phù hợp với bộ lọc.
                                        <br>
                                        <a href="index.php?controller=adminRap&action=index" style="color: #4a90e2; text-decoration: underline;">
                                            Xem tất cả rạp
                                        </a>
                                    <?php else: ?>
                                        <!-- THÔNG BÁO KHI CHƯA CÓ RẠP NÀO -->
                                        Chưa có rạp nào.
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php else: ?>
                            <!-- LẶP QUA DANH SÁCH RẠP VÀ HIỂN THỊ -->
                            <?php foreach ($danhSachRap as $rap): ?>
                                <tr>
                                    <td><?php echo $rap['ma_rap']; ?></td>
                                    <td><?php echo htmlspecialchars($rap['ten_rap']); ?></td>
                                    <td><?php echo htmlspecialchars($rap['thanh_pho']); ?></td>
                                    <td><?php echo htmlspecialchars($rap['dia_chi']); ?></td>
                                    <td><?php echo htmlspecialchars($rap['SDT']); ?></td>
                                    <td><?php echo $rap['so_phong']; ?></td>
                                    <td>
                                        <!-- HIỂN THỊ ẢNH RẠP NẾU CÓ, NẾU KHÔNG HIỂN THỊ VĂN BẢN -->
                                        <?php if (!empty($rap['anh_rap'])): ?>
                                            <img src="<?php echo htmlspecialchars($rap['anh_rap']); ?>" alt="Ảnh rạp" class="table-image">
                                        <?php else: ?>
                                            <span class="no-image">Không có ảnh</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <!-- NÚT SỬA RẠP -->
                                        <a href="index.php?controller=adminRap&action=edit&id=<?php echo $rap['ma_rap']; ?>&page=<?php echo $page; ?>" class="action-btn edit-btn">Sửa</a>
                                        <!-- NÚT XÓA RẠP (CÓ XÁC NHẬN) -->
                                        <a href="index.php?controller=adminRap&action=destroy&id=<?php echo $rap['ma_rap']; ?>" class="action-btn delete-btn" onclick="return confirm('Bạn có chắc chắn muốn xóa rạp này?');">Xóa</a>
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
            'add_success' => 'Thêm rạp thành công!',
            'add_error' => 'Có lỗi xảy ra khi thêm rạp!',
            'update_success' => 'Cập nhật rạp thành công!',
            'update_error' => 'Có lỗi xảy ra khi cập nhật rạp!',
            'delete_success' => 'Xóa rạp thành công!',
            'delete_error' => 'Có lỗi xảy ra khi xóa rạp!',
            'delete_error_fk' => 'Không thể xóa rạp vì có phòng hoặc suất chiếu liên quan!',
            'not_found' => 'Không tìm thấy rạp!'
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