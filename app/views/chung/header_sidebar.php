<?php
// app/views/admin/partials/header_sidebar.php

// Xác định controller hiện tại từ URL
$current_controller = $_GET['controller'] ?? '';
?>
    <header class="top-bar">
        <div class="logo">
            <img src="publics/img/avata1.jpg" alt="CINETIX Logo" />
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
                </ul>
            </nav>
        </aside>