<?php
// Sử dụng HeaderHandler để xử lý logic
require_once __DIR__ . '/../../services/HeaderHandler.php';

$headerHandler = new HeaderHandler();
$headerData = $headerHandler->getHeaderData();

// Trích xuất biến từ headerData
$all_raps = $headerData['all_raps'];
$selected_theater_id = $headerData['selected_theater_id'];
$selected_theater_name = $headerData['selected_theater_name'];
$header_rap_link_template = $headerData['header_rap_link_template'];
$current_controller = $headerData['current_controller'];
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