<?php
// app/views/admin/rap_view.php
// Các biến $danhSachRap, $action, $edit_id, $rap_to_edit
// được truyền từ AdminRapController
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quản lý Rạp</title>
    <link rel="stylesheet" href="publics/css/admin-layout.css" />
    <link rel="stylesheet" href="publics/css/admin-rap.css" />
</head>
<body>
    <header class="top-bar">
        <div class="logo">
            <img src="publics/img/avata1.jpg" alt="Logo" />
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
                    <li><a href="index.php?controller=adminShowtime&action=index">Quản lý suất chiếu</a></li>
                    <li class="active">
                        <a href="index.php?controller=adminRap&action=index">Quản lý rạp</a>
                    </li>
                    <li>
                        <a href="index.php?controller=adminPhong&action=index">Quản lý phòng chiếu</a>
                    </li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <!-- ĐÃ CẬP NHẬT: Thêm page-header giống showtime_view.php -->
            <div class="page-header">
                <h3>DANH SÁCH RẠP</h3>
                <a href="index.php?controller=adminRap&action=create" class="add-btn">+ Thêm Rạp</a>
            </div>

            <!-- ĐÃ CẬP NHẬT: Đưa form ra ngoài data-section giống showtime_view.php -->
            <?php if (isset($action) && $action === 'create'): ?>
            <!-- FORM THÊM MỚI -->
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

            <?php if (isset($edit_id) && isset($rap_to_edit)): ?>
            <!-- FORM CHỈNH SỬA -->
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

            <!-- ĐÃ CẬP NHẬT: Bảng nằm trong data-section riêng giống showtime_view.php -->
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
                                    <td colspan="8" style="text-align: center;">Chưa có rạp nào.</td>
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
            </section>
        </main>
    </div>
</body>
</html>