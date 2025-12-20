<?php
// app/views/admin/combo_view.php
// Logic hiển thị bộ lọc dựa trên cấu trúc của rap_view.php
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>CINETIX - Quản lý combo</title>
    <link rel="stylesheet" href="publics/css/admin-layout1.css" />
    <link rel="stylesheet" href="publics/css/admin-rap1.css" />
</head>
<body>
<?php include __DIR__ . '/../chung/header_sidebar.php'; ?>
    
    <main class="main-content">
        <div class="page-header">
            <h3>QUẢN LÝ COMBO</h3>
            <a href="index.php?controller=adminCombo&action=create" class="add-btn">+ Thêm combo</a>
        </div>

        <div class="filter-section">
            <h4>BỘ LỌC & TÌM KIẾM</h4>
            <form method="GET" action="" class="filter-form">
                <input type="hidden" name="controller" value="adminCombo">
                <input type="hidden" name="action" value="index">
                
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="search">Tên combo:</label>
                        <input type="text" name="search" id="search" 
                               placeholder="Nhập tên combo..." 
                               value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                    </div>

                    <div class="filter-group">
                        <label>Giá từ:</label>
                        <input type="number" name="min_price" 
                               placeholder="0" 
                               value="<?php echo htmlspecialchars($_GET['min_price'] ?? ''); ?>">
                    </div>
                    <div class="filter-group">
                        <label>Đến:</label>
                        <input type="number" name="max_price" 
                               placeholder="---" 
                               value="<?php echo htmlspecialchars($_GET['max_price'] ?? ''); ?>">
                    </div>
                    
                    <div class="filter-actions">
                        <button type="submit" class="btn-filter">Lọc</button>
                        <a href="index.php?controller=adminCombo&action=index" class="btn-reset">Xóa lọc</a>
                    </div>
                </div>
            </form>
        </div>

        <?php if (!empty($_GET['search']) || !empty($_GET['min_price']) || !empty($_GET['max_price'])): ?>
            <div class="active-filters">
                <small>
                    <strong>Đang lọc:</strong>
                    <?php 
                    $filters = [];
                    if (!empty($_GET['search'])) $filters[] = "Tên: " . htmlspecialchars($_GET['search']);
                    if (!empty($_GET['min_price'])) $filters[] = "Giá từ: " . number_format($_GET['min_price']);
                    if (!empty($_GET['max_price'])) $filters[] = "Đến: " . number_format($_GET['max_price']);
                    echo implode(', ', $filters);
                    ?>
                    <a href="index.php?controller=adminCombo&action=index" style="margin-left: 10px; color: #e74c3c;">[Xóa tất cả]</a>
                </small>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['action']) && $_GET['action'] === 'create'): ?>
        <div class="form-container">
            <h4>THÊM COMBO MỚI</h4>
            <form action="index.php?controller=adminCombo&action=store" method="POST" enctype="multipart/form-data" class="form-grid">
                <div class="form-group">
                    <label>Tên Combo *</label>
                    <input type="text" name="ten_combo" placeholder="Nhập tên combo" required>
                </div>
                <div class="form-group">
                    <label>Giá tiền (VND) *</label>
                    <input type="number" name="gia_tien" placeholder="Nhập giá tiền" required>
                </div>
                <div class="form-group form-group-full">
                    <label>Mô tả</label>
                    <textarea name="mo_ta" placeholder="Mô tả chi tiết combo"></textarea>
                </div>
                <div class="form-group">
                    <label>Ảnh minh họa</label>
                    <input type="file" name="anh_minh_hoa" accept="image/*">
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-save">Lưu</button>
                    <a href="index.php?controller=adminCombo&action=index" class="btn-cancel">Hủy</a>
                </div>
            </form>
        </div>
        <?php endif; ?>

        <?php if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($combo_to_edit)): ?>
        <div class="form-container">
            <h4>CHỈNH SỬA COMBO</h4>
            <form action="index.php?controller=adminCombo&action=update" method="POST" enctype="multipart/form-data" class="form-grid">
                <input type="hidden" name="ma_combo" value="<?php echo $combo_to_edit['ma_combo']; ?>">
                
                <div class="form-group">
                    <label>Tên Combo *</label>
                    <input type="text" name="ten_combo" value="<?php echo htmlspecialchars($combo_to_edit['ten_combo']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Giá tiền (VND) *</label>
                    <input type="number" name="gia_tien" value="<?php echo $combo_to_edit['gia_tien']; ?>" required>
                </div>
                <div class="form-group form-group-full">
                    <label>Mô tả</label>
                    <textarea name="mo_ta"><?php echo htmlspecialchars($combo_to_edit['mo_ta']); ?></textarea>
                </div>
                <div class="form-group">
                    <label>Ảnh minh họa</label>
                    <input type="file" name="anh_minh_hoa" accept="image/*">
                    <?php if (!empty($combo_to_edit['anh_minh_hoa'])): ?>
                        <div class="image-preview" style="margin-top: 10px;">
                            <img src="<?php echo htmlspecialchars($combo_to_edit['anh_minh_hoa']); ?>" alt="Ảnh hiện tại" style="height: 100px; border-radius: 4px;">
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-save">Cập nhật</button>
                    <a href="index.php?controller=adminCombo&action=index" class="btn-cancel">Hủy</a>
                </div>
            </form>
        </div>
        <?php endif; ?>

        <section class="data-section">
            <div class="table-container">
                <div class="result-count">
                    <span>Hiển thị <?php echo count($listCombo); ?> combo</span>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên combo</th>
                            <th>Mô tả</th>
                            <th>Ảnh minh họa</th>
                            <th>Giá tiền (VND)</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($listCombo)): ?>
                            <tr>
                                <td colspan="6" style="text-align:center; padding: 30px;">
                                    Không tìm thấy dữ liệu phù hợp.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($listCombo as $combo): ?>
                            <tr>
                                <td><?= $combo['ma_combo']; ?></td>
                                <td><strong><?= htmlspecialchars($combo['ten_combo']); ?></strong></td>
                                <td><?= htmlspecialchars($combo['mo_ta']); ?></td>
                                <td class="poster-cell">
                                    <?php if(!empty($combo['anh_minh_hoa'])): ?>
                                        <img src="<?= $combo['anh_minh_hoa']; ?>" class="movie-poster" style="width: 60px; height: auto;" />
                                    <?php endif; ?>
                                </td>
                                <td><?= number_format($combo['gia_tien']); ?></td>
                                <td>
                                    <button class="action-btn edit-btn"
                                      onclick="location.href='index.php?controller=adminCombo&action=edit&id=<?= $combo['ma_combo']; ?>'">
                                      Sửa
                                    </button>

                                    <button class="action-btn delete-btn"
                                      onclick="if(confirm('Xoá combo này?')) location.href='index.php?controller=adminCombo&action=delete&id=<?= $combo['ma_combo']; ?>'">
                                      Xóa
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <?php if (isset($_GET['status'])): ?>
        <script>
            alert('<?php echo ($_GET['status'] == 'success') ? "Thao tác thành công!" : "Có lỗi xảy ra!"; ?>');
        </script>
    <?php endif; ?>
</body>
</html>