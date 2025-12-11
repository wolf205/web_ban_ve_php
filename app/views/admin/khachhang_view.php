<?php
// app/views/admin/khachhang_view.php
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quản lý Người Dùng</title>
    <link rel="stylesheet" href="publics/css/admin-layout1.css" />
    <link rel="stylesheet" href="publics/css/admin-rap1.css" />
    <style>
        /* Thêm một số style riêng cho trang khách hàng */
        .avatar-small {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }
        .no-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 12px;
        }
        .action-disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
            text-decoration: line-through;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../chung/header_sidebar.php'; ?>

    <div class="container">
        <main class="main-content">
            <!-- PAGE HEADER -->
            <div class="page-header">
                <h3>DANH SÁCH NGƯỜI DÙNG</h3>
                <a href="index.php?controller=adminKhachHang&action=create" class="add-btn">+ Thêm Quản Lý</a>
            </div>

            <!-- BỘ LỌC & TÌM KIẾM -->
            <div class="filter-section">
                <h4>BỘ LỌC & TÌM KIẾM</h4>
                <form method="GET" action="" class="filter-form">
                    <input type="hidden" name="controller" value="adminKhachHang">
                    <input type="hidden" name="action" value="index">
                    
                    <div class="filter-row">
                        <div class="filter-group">
                            <label for="vai_tro">Vai trò:</label>
                            <select name="vai_tro" id="vai_tro">
                                <option value="all">Tất cả vai trò</option>
                                <?php 
                                $roles = $roles ?? [];
                                $selected_role = $filter_params['vai_tro'] ?? null;
                                foreach ($roles as $role): 
                                ?>
                                    <option value="<?php echo htmlspecialchars($role); ?>" 
                                        <?php echo ($selected_role == $role) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($role); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label for="search">Tìm kiếm:</label>
                            <input type="text" name="search" id="search" 
                                   placeholder="Tên, email, tài khoản hoặc SĐT" 
                                   value="<?php echo htmlspecialchars($filter_params['search'] ?? ''); ?>">
                        </div>
                        
                        <div class="filter-actions">
                            <button type="submit" class="btn-filter">Lọc</button>
                            <a href="index.php?controller=adminKhachHang&action=index" class="btn-reset">Xóa lọc</a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- THÔNG TIN BỘ LỌC ĐANG ÁP DỤNG -->
            <?php if (!empty($filter_params['vai_tro']) || !empty($filter_params['search'])): ?>
                <div class="active-filters">
                    <small>
                        <strong>Đang lọc:</strong>
                        <?php 
                        $filters = [];
                        if (!empty($filter_params['vai_tro']) && $filter_params['vai_tro'] != 'all') {
                            $filters[] = "Vai trò: " . htmlspecialchars($filter_params['vai_tro']);
                        }
                        if (!empty($filter_params['search'])) {
                            $filters[] = "Tìm kiếm: " . htmlspecialchars($filter_params['search']);
                        }
                        echo implode(', ', $filters);
                        ?>
                        <a href="index.php?controller=adminKhachHang&action=index" style="margin-left: 10px; color: #e74c3c;">
                            [Xóa tất cả]
                        </a>
                    </small>
                </div>
            <?php endif; ?>

            <!-- FORM THÊM MỚI -->
            <?php if (isset($action) && $action === 'create'): ?>
            <div class="form-container">
                <h4>THÊM TÀI KHOẢN QUẢN LÝ MỚI</h4>
                <form action="index.php?controller=adminKhachHang&action=store" method="POST" enctype="multipart/form-data" class="form-grid">
                    <div class="form-group">
                        <label>Họ tên *</label>
                        <input type="text" name="ho_ten" placeholder="Họ và tên" required>
                    </div>
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email" placeholder="Email" required>
                    </div>
                    <div class="form-group">
                        <label>Số điện thoại</label>
                        <input type="text" name="SDT" placeholder="Số điện thoại">
                    </div>
                    <div class="form-group">
                        <label>Tài khoản *</label>
                        <input type="text" name="tai_khoan" placeholder="Tài khoản đăng nhập" required>
                    </div>
                    <div class="form-group">
                        <label>Mật khẩu</label>
                        <input type="password" name="mat_khau" placeholder="Mật khẩu (để trống sẽ dùng mặc định)">
                        <small class="file-help">Để trống sẽ dùng mật khẩu mặc định: 123456</small>
                    </div>
                    <div class="form-group">
                        <label>Vai trò</label>
                        <select name="vai_tro" disabled>
                            <option value="quản lý" selected>Quản lý</option>
                        </select>
                        <input type="hidden" name="vai_tro" value="quản lý">
                        <small class="file-help">Chỉ có thể thêm tài khoản với vai trò "Quản lý"</small>
                    </div>
                    <div class="form-group">
                        <label>Avatar</label>
                        <input type="file" name="avatar" accept="image/*">
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-save">Lưu</button>
                        <a href="index.php?controller=adminKhachHang&action=index" class="btn-cancel">Hủy</a>
                    </div>
                </form>
            </div>
            <?php endif; ?>

            <!-- FORM CHỈNH SỬA -->
            <?php if (isset($edit_id) && isset($khachhang_to_edit)): ?>
            <div class="form-container">
                <h4>CHỈNH SỬA TÀI KHOẢN <?php echo $khachhang_to_edit['vai_tro'] == 'quản lý' ? 'QUẢN LÝ' : htmlspecialchars(strtoupper($khachhang_to_edit['vai_tro'])); ?></h4>
                <form action="index.php?controller=adminKhachHang&action=update" method="POST" class="form-grid">
                    <input type="hidden" name="ma_kh" value="<?php echo $khachhang_to_edit['ma_kh']; ?>">
                    <div class="form-group">
                        <label>Họ tên *</label>
                        <input type="text" name="ho_ten" value="<?php echo htmlspecialchars($khachhang_to_edit['ho_ten']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($khachhang_to_edit['email']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Số điện thoại</label>
                        <input type="text" name="SDT" value="<?php echo htmlspecialchars($khachhang_to_edit['SDT']); ?>">
                    </div>
                    <div class="form-group">
                        <label>Tài khoản *</label>
                        <input type="text" name="tai_khoan" value="<?php echo htmlspecialchars($khachhang_to_edit['tai_khoan']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Mật khẩu mới</label>
                        <input type="password" name="mat_khau" placeholder="Chỉ nhập nếu muốn đổi mật khẩu">
                        <small class="file-help">Chỉ nhập nếu muốn thay đổi mật khẩu</small>
                    </div>
                    <div class="form-group">
                        <label>Vai trò</label>
                        <select name="vai_tro" <?php echo $khachhang_to_edit['vai_tro'] != 'quản lý' ? 'disabled' : ''; ?>>
                            <option value="khách hàng" <?php echo ($khachhang_to_edit['vai_tro'] == 'khách hàng') ? 'selected' : ''; ?>>Khách hàng</option>
                            <option value="admin" <?php echo ($khachhang_to_edit['vai_tro'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                            <option value="quản lý" <?php echo ($khachhang_to_edit['vai_tro'] == 'quản lý') ? 'selected' : ''; ?>>Quản lý</option>
                            <option value="nhân viên" <?php echo ($khachhang_to_edit['vai_tro'] == 'nhân viên') ? 'selected' : ''; ?>>Nhân viên</option>
                        </select>
                        <?php if ($khachhang_to_edit['vai_tro'] != 'quản lý'): ?>
                            <input type="hidden" name="vai_tro" value="<?php echo htmlspecialchars($khachhang_to_edit['vai_tro']); ?>">
                            <small class="file-help">Chỉ có thể sửa thông tin tài khoản "Quản lý"</small>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($khachhang_to_edit['avatar'])): ?>
                        <div class="form-group">
                            <label>Avatar hiện tại</label>
                            <div class="image-preview">
                                <img src="<?php echo htmlspecialchars($khachhang_to_edit['avatar']); ?>" alt="Avatar hiện tại" style="width: 100px; height: 100px; border-radius: 50%;">
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="form-actions">
                        <?php if ($khachhang_to_edit['vai_tro'] == 'quản lý'): ?>
                            <button type="submit" class="btn-save">Lưu</button>
                        <?php else: ?>
                            <button type="button" class="btn-save" disabled>Chỉ sửa tài khoản "Quản lý"</button>
                        <?php endif; ?>
                        <a href="index.php?controller=adminKhachHang&action=index" class="btn-cancel">Hủy</a>
                    </div>
                </form>
            </div>
            <?php endif; ?>

            <!-- SỐ LƯỢNG KẾT QUẢ -->
            <?php if (!isset($action) || $action !== 'create'): ?>
                <div class="result-count">
                    <span>Hiển thị <?php echo count($danhSachKhachHang); ?> người dùng</span>
                </div>
            <?php endif; ?>

            <!-- BẢNG DỮ LIỆU -->
            <section class="data-section">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Avatar</th>
                                <th>Họ tên</th>
                                <th>Email</th>
                                <th>SĐT</th>
                                <th>Tài khoản</th>
                                <th>Vai trò</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($danhSachKhachHang)): ?>
                                <tr>
                                    <td colspan="8" style="text-align: center; padding: 30px;">
                                        <?php if (!empty($filter_params['vai_tro']) || !empty($filter_params['search'])): ?>
                                            Không tìm thấy người dùng nào phù hợp với bộ lọc.
                                            <br>
                                            <a href="index.php?controller=adminKhachHang&action=index" style="color: #4a90e2; text-decoration: underline;">
                                                Xem tất cả người dùng
                                            </a>
                                        <?php else: ?>
                                            Chưa có người dùng nào.
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($danhSachKhachHang as $kh): ?>
                                    <tr>
                                        <td><?php echo $kh['ma_kh']; ?></td>
                                        <td>
                                            <?php if (!empty($kh['avatar'])): ?>
                                                <img src="<?php echo htmlspecialchars($kh['avatar']); ?>" alt="Avatar" class="avatar-small">
                                            <?php else: ?>
                                                <div class="no-avatar">No Avatar</div>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($kh['ho_ten']); ?></td>
                                        <td><?php echo htmlspecialchars($kh['email']); ?></td>
                                        <td><?php echo htmlspecialchars($kh['SDT']); ?></td>
                                        <td><?php echo htmlspecialchars($kh['tai_khoan']); ?></td>
                                        <td>
                                            <span class="badge <?php echo $kh['vai_tro'] == 'admin' ? 'badge-danger' : ($kh['vai_tro'] == 'quản lý' ? 'badge-warning' : 'badge-info'); ?>">
                                                <?php echo htmlspecialchars($kh['vai_tro']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($kh['vai_tro'] == 'quản lý'): ?>
                                                <a href="index.php?controller=adminKhachHang&action=edit&id=<?php echo $kh['ma_kh']; ?>" class="action-btn edit-btn">Sửa</a>
                                                <a href="index.php?controller=adminKhachHang&action=destroy&id=<?php echo $kh['ma_kh']; ?>" class="action-btn delete-btn" onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản quản lý này?');">Xóa</a>
                                            <?php else: ?>
                                                <span class="action-btn edit-btn action-disabled">Sửa</span>
                                                <span class="action-btn delete-btn action-disabled">Xóa</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- PHÂN TRANG -->
            <?php if (count($danhSachKhachHang) > 0 && !isset($action)): ?>
                <div class="pagination">
                    <div class="pagination-info">
                        Hiển thị tất cả <?php echo count($danhSachKhachHang); ?> người dùng
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
                        message = 'Thêm tài khoản quản lý thành công!';
                        type = 'success';
                        break;
                    case 'add_error':
                        message = 'Có lỗi xảy ra khi thêm tài khoản! Email hoặc tài khoản đã tồn tại.';
                        type = 'error';
                        break;
                    case 'update_success':
                        message = 'Cập nhật tài khoản thành công!';
                        type = 'success';
                        break;
                    case 'update_error':
                        message = 'Có lỗi xảy ra khi cập nhật tài khoản! Email hoặc tài khoản đã tồn tại.';
                        type = 'error';
                        break;
                    case 'delete_success':
                        message = 'Xóa tài khoản quản lý thành công!';
                        type = 'success';
                        break;
                    case 'delete_error_fk':
                        message = 'Không thể xóa tài khoản vì có hóa đơn liên quan!';
                        type = 'error';
                        break;
                    case 'delete_not_allowed':
                        message = 'Chỉ có thể xóa tài khoản có vai trò "Quản lý"!';
                        type = 'error';
                        break;
                    case 'not_found':
                        message = 'Không tìm thấy tài khoản!';
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