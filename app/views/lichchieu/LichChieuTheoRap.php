<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lịch Chiếu Phim</title>

    <link rel="stylesheet" href="/Project1/publics/css/reset.css" />
    <link rel="stylesheet" href="/Project1/publics/css/variables.css" />
    <link rel="stylesheet" href="/Project1/publics/css/container.css" />
    <link rel="stylesheet" href="/Project1/publics/css/button.css" />
    <link rel="stylesheet" href="/Project1/publics/css/card.css" />
    <link rel="stylesheet" href="/Project1/publics/css/LichChieu.css" />
  </head>
  <body>
    <header class="placeholder-nav">
        <a href="index.php?page=rap">Rạp</a>
<a href="index.php?page=lichchieu">Lịch chiếu</a>

    </header>

    <main>
      <div class="container">
        <!-- Bộ chọn ngày -->
        <section class="date-selector section">
  <?php
  // Danh sách ngày (có thể tạo động bằng date() nếu muốn)
  $ds_ngay = ['25/10 - T7', '26/10 - CN', '27/10 - T2', '28/10 - T3', '29/10 - T4', '30/10 - T5'];

  foreach ($ds_ngay as $i => $item):
      // Tách phần ngày (vd: "25/10" từ "25/10 - T7")
      $ngay_value = explode(' ', $item)[0];

      // Đánh dấu ngày đang được chọn (so sánh với $ngay trong controller)
      $active = ($ngay_value === $ngay) ? 'active' : '';

      // Giữ nguyên tham số "rap" hiện tại để không mất khi đổi ngày
      $url = "index.php?page=lichchieu&rap=" . urlencode($rap) . "&ngay=" . urlencode($ngay_value);
  ?>
      <a href="<?= $url ?>" class="date-item <?= $active ?>">
        <?= htmlspecialchars($item) ?>
      </a>
  <?php endforeach; ?>
</section>

        <!-- Danh sách phim -->
        <section class="movie-grid">
          <?php foreach ($lich_chieu_loc as $phim): ?>
            <article class="movie-listing">
              <div class="movie-poster-card card">
                <img src="<?= htmlspecialchars($phim['poster']) ?>" alt="Poster phim" />
                <span class="movie-tag"><?= htmlspecialchars($phim['tag']) ?></span>
              </div>

              <div class="movie-info-details">
                <h3 class="movie-title"><?= htmlspecialchars($phim['ten']) ?></h3>
                <p class="movie-details">
                  <?= htmlspecialchars($phim['the_loai']) ?> | <?= htmlspecialchars($phim['thoi_luong']) ?> phút
                </p>

                <div class="showtimes">
                  <h4>2D PHỤ ĐỀ</h4>
                  <div class="showtimes-grid">
                    <?php foreach ($phim['suat_chieu'] as $suat): ?>
                      <a href="#" class="showtime-slot">
                        <span class="time"><?= htmlspecialchars($suat['gio']) ?></span>
                        <span class="seats"><?= htmlspecialchars($suat['ghe_trong']) ?> ghế trống</span>
                      </a>
                    <?php endforeach; ?>
                  </div>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        </section>
      </div>
    </main>

    <footer class="placeholder-nav"></footer>
  </body>
</html>
