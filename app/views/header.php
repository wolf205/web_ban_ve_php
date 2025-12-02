<?php
$selected_theater_name = 'Chọn rạp';
$selected_theater_id = null;

if (isset($rap) && is_array($rap) && isset($rap['ma_rap'])) {
    $selected_theater_name = $rap['ten_rap'];
    $selected_theater_id = $rap['ma_rap'];
} 
else if (isset($all_raps) && is_array($all_raps)) {
    foreach ($all_raps as $r) {
        if ($r['ma_rap'] == '1') { 
            $selected_theater_name = $r['ten_rap'];
            $selected_theater_id = $r['ma_rap'];
            break;
        }
    }
    if ($selected_theater_id === null && !empty($all_raps)) {
        $selected_theater_name = $all_raps[0]['ten_rap'];
        $selected_theater_id = $all_raps[0]['ma_rap'];
    }
}

if (!isset($header_rap_link_template)) {
    $header_rap_link_template = 'index.php?controller=rap&action=showDetails&ma_rap=__MA_RAP__';
}

$current_controller = $_GET['controller'] ?? 'phim';

?>
<header id="page-header">
  <div class="top-bar">
    <div class="top-bar-content">
      <div class="contact-info">
        <span><i class="fas fa-phone"></i> Hotline: 1900 1234</span>
        <span><i class="fas fa-envelope"></i> support@cinemaplus.vn</span>
      </div>
      <div class="social-links">
        <a href="#"><i class="fab fa-facebook"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
        <a href="#"><i class="fab fa-youtube"></i></a>
        <a href="#"><i class="fab fa-tiktok"></i></a>
      </div>
    </div>
  </div>

  <div class="main-header">
    <a href="index.php?controller=trangchu" class="logo">
      <i class="fas fa-film"></i>
      CINEMA PLUS
    </a>

    <div class="cinema-filter">
      <button class="filter-btn" id="filterBtn">
        <i class="fas fa-map-marker-alt"></i>
        <span id="selectedCity"><?php echo htmlspecialchars($selected_theater_name); ?></span>
        <i class="fas fa-chevron-down"></i>
      </button>

      <div class="filter-dropdown" id="filterDropdown">
        <?php if (isset($all_raps) && is_array($all_raps) && !empty($all_raps)): ?>
          <?php foreach ($all_raps as $rap_item): ?>
            <?php
              $active_class = ($rap_item['ma_rap'] == $selected_theater_id) ? ' active' : '';
              $link_href = str_replace('__MA_RAP__', htmlspecialchars($rap_item['ma_rap']), $header_rap_link_template);
            ?>
            <a href="<?php echo $link_href; ?>" class="filter-item<?php echo $active_class; ?>" data-ma-rap="<?php echo htmlspecialchars($rap_item['ma_rap']); ?>">
              <?php echo htmlspecialchars($rap_item['ten_rap']); ?>
            </a>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="filter-item">Không có rạp nào</div>
        <?php endif; ?>
      </div>
    </div>

    <nav>
      <a href="index.php?controller=phim" class="nav-link <?php echo ($current_controller == 'phim') ? 'active' : ''; ?>">Phim</a>
      <a href="index.php?controller=lichchieu" class="nav-link <?php echo ($current_controller == 'lichchieu') ? 'active' : ''; ?>">Lịch Chiếu Theo Rạp</a>
      <a href="index.php?controller=rap" class="nav-link <?php echo ($current_controller == 'rap') ? 'active' : ''; ?>">Rạp</a>
      
      <a href="index.php?controller=KhachHang&action=profile"
       class="nav-link <?php echo ($current_controller == 'KhachHang') ? 'active' : ''; ?>">
   Tài Khoản
</a>

    </nav>

    <div class="header-actions">
    <?php if (isset($_SESSION['khach_hang'])): ?>
        <!-- Nếu đã đăng nhập → Hiện nút Đăng Xuất -->
        <a href="index.php?controller=KhachHang&action=logout" class="btn btn-primary">
            <i class="fas fa-sign-out-alt"></i>
            Đăng Xuất
        </a>
    <?php else: ?>
        <!-- Nếu chưa đăng nhập → Hiện nút Đăng Nhập -->
        <a href="index.php?controller=KhachHang&action=index" class="btn btn-primary">
            <i class="fas fa-user"></i>
            Đăng Nhập
        </a>
    <?php endif; ?>
</div>

  </div>
</header>
<script src="publics/js/header.js" defer></script>