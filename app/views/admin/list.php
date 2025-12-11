<?php
// app/views/admin/phim/list.php
// View này xử lý cả 3 trạng thái: Index (Danh sách), Create (Thêm), Edit (Sửa)
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>CINETIX - Quản lý phim</title>
    <link rel="stylesheet" href="publics/css/admin-layout1.css" />
    <link rel="stylesheet" href="publics/css/admin-phim.css" />
</head>
<body>
    <?php include __DIR__ . '/../chung/header_sidebar.php'; ?>

    <main class="main-content">
        <div class="page-header">
            <h3>QUẢN LÝ PHIM</h3>
            <?php if (!isset($action) || $action == 'index'): ?>
                <a href="index.php?controller=adminPhim&action=create" class="add-btn">+ Thêm Phim Mới</a>
            <?php endif; ?>
        </div>

        <?php if (isset($action) && ($action == 'create' || $action == 'edit')): ?>
            <?php 
                $isEdit = ($action == 'edit');
                $formTitle = $isEdit ? 'CHỈNH SỬA PHIM' : 'THÊM PHIM MỚI';
                $formAction = $isEdit ? 'update' : 'store';
                // Đổ dữ liệu cũ nếu là edit
                $p = isset($phim) ? $phim : []; 
            ?>
            
            <div class="form-container">
                <h4><?php echo $formTitle; ?></h4>
                
                <form action="index.php?controller=adminPhim&action=<?php echo $formAction; ?>" method="POST" enctype="multipart/form-data" class="form-grid">
                    
                    <?php if ($isEdit): ?>
                        <input type="hidden" name="ma_phim" value="<?php echo $p['ma_phim']; ?>">
                        <input type="hidden" name="anh_cu" value="<?php echo $p['anh_trailer']; ?>">
                    <?php endif; ?>

                    <div class="form-group">
                        <label>Tên phim *</label>
                        <input type="text" name="ten_phim" required 
                               value="<?php echo isset($p['ten_phim']) ? htmlspecialchars($p['ten_phim']) : ''; ?>" 
                               placeholder="Nhập tên phim...">
                    </div>

                    <div class="form-group">
                        <label>Ảnh Poster/Trailer <?php echo $isEdit ? '' : '*'; ?></label>
                        <input type="file" name="anh_trailer" accept="image/*" <?php echo $isEdit ? '' : 'required'; ?>>
                        <?php if ($isEdit && !empty($p['anh_trailer'])): ?>
                            <div class="current-image-preview">
                                <small>Ảnh hiện tại:</small>
                                <img src="<?php echo htmlspecialchars($p['anh_trailer']); ?>" alt="Poster">
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label>Thể loại</label>
                        <input type="text" name="the_loai" 
                               value="<?php echo isset($p['the_loai']) ? htmlspecialchars($p['the_loai']) : ''; ?>" 
                               placeholder="Ví dụ: Hành động, Hài hước">
                    </div>

                    <div class="form-group">
                        <label>Thời lượng (phút) *</label>
                        <input type="number" name="thoi_luong" required min="1"
                               value="<?php echo isset($p['thoi_luong']) ? $p['thoi_luong'] : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label>Ngày khởi chiếu *</label>
                        <input type="date" name="ngay_khoi_chieu" required
                               value="<?php echo isset($p['ngay_khoi_chieu']) ? $p['ngay_khoi_chieu'] : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label>Giới hạn độ tuổi</label>
                        <select name="gioi_han_do_tuoi">
                            <option value="0" <?php echo (isset($p['gioi_han_do_tuoi']) && $p['gioi_han_do_tuoi'] == 0) ? 'selected' : ''; ?>>P - Phổ biến</option>
                            <option value="13" <?php echo (isset($p['gioi_han_do_tuoi']) && $p['gioi_han_do_tuoi'] == 13) ? 'selected' : ''; ?>>C13 - Trên 13 tuổi</option>
                            <option value="16" <?php echo (isset($p['gioi_han_do_tuoi']) && $p['gioi_han_do_tuoi'] == 16) ? 'selected' : ''; ?>>C16 - Trên 16 tuổi</option>
                            <option value="18" <?php echo (isset($p['gioi_han_do_tuoi']) && $p['gioi_han_do_tuoi'] == 18) ? 'selected' : ''; ?>>C18 - Trên 18 tuổi</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Đạo diễn</label>
                        <input type="text" name="dao_dien" 
                               value="<?php echo isset($p['dao_dien']) ? htmlspecialchars($p['dao_dien']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label>Diễn viên</label>
                        <input type="text" name="dien_vien" 
                               value="<?php echo isset($p['dien_vien']) ? htmlspecialchars($p['dien_vien']) : ''; ?>">
                    </div>

                    <div class="form-group form-group-full">
                        <label>Mô tả phim</label>
                        <textarea name="mo_ta" rows="4"><?php echo isset($p['mo_ta']) ? htmlspecialchars($p['mo_ta']) : ''; ?></textarea>
                    </div>

                    <div class="form-group form-group-full checkbox-group">
                        <label>
                            <input type="checkbox" name="hot" value="1" 
                                   <?php echo (isset($p['hot']) && $p['hot'] == 1) ? 'checked' : ''; ?>>
                            <span>Đây là Phim HOT (Hiển thị banner)</span>
                        </label>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-save">Lưu Phim</button>
                        <a href="index.php?controller=adminPhim&action=index" class="btn-cancel">Hủy</a>
                    </div>
                </form>
            </div>
        <?php endif; ?>

        <section class="data-section">
            
            <div class="filter-section">
            <h4>BỘ LỌC PHIM</h4>
            <form action="index.php" method="GET" class="filter-form">
                <input type="hidden" name="controller" value="adminPhim">
                <input type="hidden" name="action" value="index">
                
                <div class="filter-row" style="margin-bottom: 15px;">
                    <div class="filter-group">
                        <label>Thể loại</label>
                        <input type="text" name="the_loai" placeholder="Nhập thể loại..." 
                               value="<?php echo isset($_GET['the_loai']) ? htmlspecialchars($_GET['the_loai']) : ''; ?>">
                    </div>

                    <div class="filter-group">
                        <label>Trạng thái</label>
                        <select name="trang_thai">
                            <option value="">-- Tất cả --</option>
                            <option value="dang_chieu" <?php echo (isset($_GET['trang_thai']) && $_GET['trang_thai'] == 'dang_chieu') ? 'selected' : ''; ?>>Đang chiếu</option>
                            <option value="sap_chieu" <?php echo (isset($_GET['trang_thai']) && $_GET['trang_thai'] == 'sap_chieu') ? 'selected' : ''; ?>>Sắp chiếu</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label>Độ tuổi</label>
                        <select name="gioi_han_do_tuoi">
                            <option value="">-- Tất cả --</option>
                            <option value="0" <?php echo (isset($_GET['gioi_han_do_tuoi']) && $_GET['gioi_han_do_tuoi'] == '0') ? 'selected' : ''; ?>>P</option>
                            <option value="13" <?php echo (isset($_GET['gioi_han_do_tuoi']) && $_GET['gioi_han_do_tuoi'] == '13') ? 'selected' : ''; ?>>C13</option>
                            <option value="16" <?php echo (isset($_GET['gioi_han_do_tuoi']) && $_GET['gioi_han_do_tuoi'] == '16') ? 'selected' : ''; ?>>C16</option>
                            <option value="18" <?php echo (isset($_GET['gioi_han_do_tuoi']) && $_GET['gioi_han_do_tuoi'] == '18') ? 'selected' : ''; ?>>C18</option>
                        </select>
                    </div>

                    <div class="filter-group" style="flex: 0.5;">
                        <label>Hot</label>
                        <select name="hot">
                            <option value="">--</option>
                            <option value="1" <?php echo (isset($_GET['hot']) && $_GET['hot'] == '1') ? 'selected' : ''; ?>>Có</option>
                            <option value="0" <?php echo (isset($_GET['hot']) && $_GET['hot'] == '0') ? 'selected' : ''; ?>>Không</option>
                        </select>
                    </div>
                </div>

                <div class="filter-row">
                    <div class="filter-group">
                        <label>Khởi chiếu từ ngày</label>
                        <input type="date" name="tu_ngay" value="<?php echo isset($_GET['tu_ngay']) ? $_GET['tu_ngay'] : ''; ?>">
                    </div>
                    <div class="filter-group">
                        <label>Đến ngày</label>
                        <input type="date" name="den_ngay" value="<?php echo isset($_GET['den_ngay']) ? $_GET['den_ngay'] : ''; ?>">
                    </div>

                    <div class="filter-actions" style="flex: 1; justify-content: flex-end;">
                        <button type="submit" class="btn-filter">Lọc Phim</button>
                        <a href="index.php?controller=adminPhim&action=index" class="btn-reset">Xóa lọc</a>
                    </div>
                </div>
            </form>
        </div>

            <?php if (isset($_GET['status'])): ?>
                <div class="active-filters">
                    <small>
                        <?php 
                            switch($_GET['status']) {
                                case 'add_success': echo '<strong style="color:#4caf50">Thêm phim thành công!</strong>'; break;
                                case 'update_success': echo '<strong style="color:#4caf50">Cập nhật phim thành công!</strong>'; break;
                                case 'delete_success': echo '<strong style="color:#4caf50">Xóa phim thành công!</strong>'; break;
                                case 'delete_error': echo '<strong style="color:#ff6b6b">Lỗi! Không thể xóa phim này (có thể do ràng buộc dữ liệu).</strong>'; break;
                                default: echo '<strong>Thao tác hoàn tất.</strong>';
                            }
                        ?>
                    </small>
                </div>
            <?php endif; ?>

            <div class="table-container">
                <h3>DANH SÁCH PHIM (<?php echo !empty($danhSachPhim) ? count($danhSachPhim) : 0; ?>)</h3>
                <table>
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th width="10%">Poster</th>
                            <th width="20%">Tên phim</th>
                            <th width="15%">Thể loại</th>
                            <th width="10%">Thời lượng</th>
                            <th width="10%">Khởi chiếu</th>
                            <th width="10%">Phân loại</th>
                            <th width="8%">Hot</th>
                            <th width="12%">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($danhSachPhim)): ?>
                            <?php foreach ($danhSachPhim as $p): ?>
                                <tr>
                                    <td><?php echo $p['ma_phim']; ?></td>
                                    <td>
                                        <?php if (!empty($p['anh_trailer'])): ?>
                                            <img src="<?php echo htmlspecialchars($p['anh_trailer']); ?>" 
                                                 class="poster-thumb" alt="Img">
                                        <?php else: ?>
                                            <span style="font-size:12px; color:#666;">No img</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="movie-name">
                                        <?php echo htmlspecialchars($p['ten_phim']); ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($p['the_loai']); ?></td>
                                    <td><?php echo $p['thoi_luong']; ?>'</td>
                                    <td><?php echo date('d/m/Y', strtotime($p['ngay_khoi_chieu'])); ?></td>
                                    <td>
                                        <span class="badge">
                                            <?php echo ($p['gioi_han_do_tuoi'] == 0) ? 'P' : 'C'.$p['gioi_han_do_tuoi']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($p['hot'] == 1): ?>
                                            <span class="star-icon">★</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="index.php?controller=adminPhim&action=edit&id=<?php echo $p['ma_phim']; ?>" 
                                           class="action-btn edit-btn">Sửa</a>
                                        
                                        <a href="index.php?controller=adminPhim&action=destroy&id=<?php echo $p['ma_phim']; ?>" 
                                           class="action-btn delete-btn"
                                           onclick="return confirm('Bạn có chắc chắn muốn xóa phim [<?php echo htmlspecialchars($p['ten_phim']); ?>] không?');">Xóa</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="no-results">
                                    Không tìm thấy phim nào.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>