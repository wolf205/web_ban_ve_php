<?php
// app/views/admin/partials/header_sidebar.php

// Xác định controller hiện tại từ URL
$current_controller = $_GET['controller'] ?? '';

// Bắt đầu session nếu chưa có (an toàn khi include file nhiều lần)
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Mặc định hiển thị
$displayName = 'Khách';
$avatarPath = 'publics/img/avatar/default.jpg';
$userRole = '';
$isManager = false;

// Chỉ dùng dữ liệu từ bảng `khach_hang` trong `$_SESSION['khach_hang']`
if (!empty($_SESSION['khach_hang'])) {
    $kh = $_SESSION['khach_hang'];
    $role = mb_strtolower(trim($kh['vai_tro'] ?? ''), 'UTF-8');
    if ($role === 'quản lý' || $role === 'quan ly') {
        $displayName = $kh['tai_khoan'] ?? $kh['ho_ten'] ?? $displayName;
        
        // Xử lý avatar: nếu có avatar và file tồn tại thì dùng, không thì dùng mặc định
        if (!empty($kh['avatar']) && file_exists($kh['avatar'])) {
            $avatarPath = $kh['avatar'];
        }
        
        $userRole = $kh['vai_tro'] ?? '';
        $isManager = true;
    }
}
?>
    <header class="top-bar">
    <div class="logo">
        <img src="publics/img/avata1.jpg" alt="CINEMA PLUS Logo" />
        <h1>CINEMA PLUS</h1>
    </div>
    
    <?php if (!empty($isManager)): ?>
    <div class="user-profile">
        <img src="<?php echo htmlspecialchars($avatarPath); ?>" alt="Avatar" 
             style="width: 38px; height: 38px; border-radius: 50%; object-fit: cover; border: 2px solid #f0c419;" />
        <div class="user-info" style="display: flex; flex-direction: column; gap: 2px;">
            <span style="font-weight: 600; font-size: 14px; color: #f0f0f0;"><?php echo htmlspecialchars($displayName); ?></span>
            <?php if (!empty($userRole)): ?>
                <span style="font-size: 11px; color: #f0c419;"><?php echo htmlspecialchars($userRole); ?></span>
            <?php endif; ?>
        </div>
        <a href="index.php?controller=KhachHang&action=logout" 
           style="margin-left: 15px; padding: 7px 14px; background-color: #f0c419; color: #111; 
                  text-decoration: none; border-radius: 5px; font-size: 13px; font-weight: 600;">
            <i class="fas fa-sign-out-alt"></i> Đăng Xuất
        </a>
    </div>
    <?php endif; ?>
</header>

    <div class="content-container">
        <?php if (!empty($isManager)): ?>
        <aside class="sidebar">
            <nav class="sidebar-nav">
                <ul>
                    <li class="<?php echo ($current_controller == 'adminDashboard') ? 'active' : ''; ?>">
                        <a href="index.php?controller=Dashboard&action=index">Dashboard</a>
                    </li>
                    <li class="<?php echo ($current_controller == 'adminCustomer') ? 'active' : ''; ?>">
                        <a href="index.php?controller=adminKhachHang&action=index">Quản lý người dùng</a>
                    </li>
                    <li class="<?php echo ($current_controller == 'adminPhim') ? 'active' : ''; ?>">
                        <a href="index.php?controller=adminPhim&action=index">Quản lý phim</a>
                    </li>
                    <li class="<?php echo ($current_controller == 'adminShowtime') ? 'active' : ''; ?>">
                        <a href="index.php?controller=adminShowtime&action=index">Quản lý suất chiếu</a>
                    </li>
                    <li class="<?php echo ($current_controller == 'adminRap') ? 'active' : ''; ?>">
                        <a href="index.php?controller=adminRap&action=index">Quản lý rạp</a>
                    </li>
                    <li class="<?php echo ($current_controller == 'adminPhong') ? 'active' : ''; ?>">
                        <a href="index.php?controller=adminPhong&action=index">Quản lý phòng chiếu</a>
                    </li>
                    
                    <li class="<?php echo ($current_controller == 'adminHoaDon') ? 'active' : ''; ?>">
                        <a href="index.php?controller=adminHoaDon&action=index">Quản lý hoá đơn</a>
                    </li>
                    <li class="<?php echo ($current_controller == 'adminCombo') ? 'active' : ''; ?>">
                        <a href="index.php?controller=adminCombo&action=index">Quản lý Combo</a>
                    </li>
                </ul>
            </nav>
        </aside>
        <?php endif; ?>