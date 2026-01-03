<?php
// INCLUDE HEADER (THÔNG TIN META, MENU, ETC.)
require_once __DIR__ . '/../chung/header.php';
?>
<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lịch Chiếu Phim</title>
    
    <!-- CSS RESET VÀ BASE STYLES -->
    <link rel="stylesheet" href="publics/css/reset.css" />
    <link rel="stylesheet" href="publics/css/variables.css" />
    <link rel="stylesheet" href="publics/css/container.css" />
    <link rel="stylesheet" href="publics/css/button.css" />
    <link rel="stylesheet" href="publics/css/card.css" />
    <link rel="stylesheet" href="publics/css/LichChieu.css" />
    
    <!-- FONT AWESOME ICONS -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    
    <!-- CUSTOM STYLES -->
    <link rel="stylesheet" href="publics/css/styles.css" />
  </head>
  <body>
    <main>
      <div class="container">
        <!-- PHẦN CHỌN NGÀY XEM PHIM -->
        <section class="date-selector section">
          <?php foreach ($fixedDateList as $day): ?>
          <!-- MỖI NGÀY LÀ MỘT LINK CÓ THỂ CLICK -->
          <a
            href="<?= htmlspecialchars($day['link']) ?>"
            class="date-item <?= $day['active'] ? 'active' : '' ?>"
          >
            <!-- HIỂN THỊ VĂN BẢN NGÀY (VÍ DỤ: "HÔM NAY", "THỨ 5 26/12") -->
            <?= $day['text'] ?>
          </a>
          <?php endforeach; ?>
        </section>

        <!-- DANH SÁCH PHIM VÀ SUẤT CHIẾU -->
        <section class="movie-grid">
          <!-- KIỂM TRA NẾU KHÔNG CÓ PHIM NÀO CÓ SUẤT CHIẾU TRONG NGÀY -->
          <?php if (empty($moviesData)): ?>
          <p
            style="
              text-align: center;
              width: 100%;
              font-size: 1.2rem;
              color: var(--text-color);
            "
          >
            Không có suất chiếu nào cho ngày
            <!-- HIỂN THỊ NGÀY ĐANG CHỌN ĐỊNH DẠNG dd/mm/yyyy -->
            <strong
              ><?php echo date('d/m/Y', strtotime($selected_date)); ?></strong
            >.
          </p>
          <?php else: ?>
          <!-- LẶP QUA DANH SÁCH PHIM CÓ SUẤT CHIẾU -->
          <?php foreach ($moviesData as $movie): ?>
          <article class="movie-listing">
            <!-- POSTER PHIM -->
            <div class="movie-poster-card card">
              <img
                src="<?php echo htmlspecialchars($movie['anh_trailer']); ?>"
                alt="Poster <?php echo htmlspecialchars($movie['ten_phim']); ?>"
              />
            </div>

            <!-- THÔNG TIN CHI TIẾT PHIM VÀ SUẤT CHIẾU -->
            <div class="movie-info-details">
              <!-- LINK ĐẾN TRANG CHI TIẾT PHIM -->
              <a
                href="index.php?controller=phim&action=detail&ma_phim=<?php echo $movie['ma_phim'];?> &ma_rap=<?php echo $selected_rap_id; ?>"
              >
                <h3 class="movie-title">
                  <?php echo htmlspecialchars($movie['ten_phim']); ?>
                </h3>
              </a>
              
              <!-- THÔNG TIN PHỤ: THỂ LOẠI VÀ THỜI LƯỢNG -->
              <p class="movie-details">
                <?php echo htmlspecialchars($movie['the_loai']); ?>
                |
                <?php echo htmlspecialchars($movie['thoi_luong']); ?>
                phút
              </p>

              <!-- DANH SÁCH SUẤT CHIẾU -->
              <div class="showtimes">
                <h4>2D PHỤ ĐỀ</h4> <!-- ĐỊNH DẠNG PHIM (CÓ THỂ ĐỘNG) -->
                <div class="showtimes-grid">
                  <!-- LẶP QUA CÁC SUẤT CHIẾU CỦA PHIM -->
                  <?php foreach ($movie['showtimes'] as $showtime): ?>
                  <!-- LINK ĐẾN TRANG CHỌN GHẾ -->
                  <a
                    href="index.php?controller=chonghe&ma_suat_chieu=<?php echo $showtime['ma_suat_chieu']; ?>"
                    class="showtime-slot"
                  >
                    <!-- GIỜ CHIẾU (ĐỊNH DẠNG HH:ii) -->
                    <span class="time"
                      ><?php echo date('H:i', strtotime($showtime['gio_bat_dau'])); ?></span
                    >
                    <!-- SỐ GHẾ TRỐNG -->
                    <span class="seats"
                      ><?php echo $showtime['so_ghe_trong']; ?>
                      ghế trống</span
                    >
                  </a>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
          </article>
          <?php endforeach; ?>
          <?php endif; ?>
        </section>
      </div>
    </main>
  </body>
</html>
<?php
// INCLUDE FOOTER (COPYRIGHT, LINKS, ETC.)
require_once __DIR__ . '/../chung/footer.php';
?>