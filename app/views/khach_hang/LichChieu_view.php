<?php
require_once __DIR__ . '/../chung/header.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Lịch Chiếu Phim</title>
  <link rel="stylesheet" href="publics/css/reset.css" />
  <link rel="stylesheet" href="publics/css/variables.css" />
  <link rel="stylesheet" href="publics/css/container.css" />
  <link rel="stylesheet" href="publics/css/button.css" />
  <link rel="stylesheet" href="publics/css/card.css" />
  <link rel="stylesheet" href="publics/css/LichChieu.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="publics/css/styles.css" />
</head>
<body>
  <main>
    <div class="container">
      <section class="date-selector section">
        <?php foreach ($fixedDateList as $day): ?>
          <?php $isActive = ($day['sql'] == $selected_date) ? 'active' : ''; ?>
          <a href="<?php echo htmlspecialchars($day['link']); ?>" class="date-item <?php echo $isActive; ?>">
            <?php echo $day['display']; ?> - <?php echo $day['weekday']; ?>
          </a>
        <?php endforeach; ?>
      </section>

      <section class="movie-grid">
        <?php if (empty($moviesData)): ?>
          <p style="text-align: center; width: 100%; font-size: 1.2rem; color: var(--text-color);">
            Không có suất chiếu nào cho ngày <strong><?php echo date('d/m/Y', strtotime($selected_date)); ?></strong>.
          </p>
        <?php else: ?>
          <?php foreach ($moviesData as $movie): ?>
            <article class="movie-listing">
              <div class="movie-poster-card card">
                <img src="<?php echo htmlspecialchars($movie['anh_trailer']); ?>" alt="Poster <?php echo htmlspecialchars($movie['ten_phim']); ?>" />
              </div>

              <div class="movie-info-details">
                <a href="index.php?controller=phim&action=detail&ma_phim=<?php echo $movie['ma_phim'];?> &ma_rap=<?php echo $selected_rap_id; ?>">
                  <h3 class="movie-title"><?php echo htmlspecialchars($movie['ten_phim']); ?></h3>
                </a>
                <p class="movie-details">
                  <?php echo htmlspecialchars($movie['the_loai']); ?> | <?php echo htmlspecialchars($movie['thoi_luong']); ?> phút
                </p>

                <div class="showtimes">
                  <h4>2D PHỤ ĐỀ</h4>
                  <div class="showtimes-grid">
                    <?php foreach ($movie['showtimes'] as $showtime): ?>
                      <a href="index.php?controller=chonghe&ma_suat_chieu=<?php echo $showtime['ma_suat_chieu']; ?>" class="showtime-slot">
                        <span class="time"><?php echo date('H:i', strtotime($showtime['gio_bat_dau'])); ?></span>
                        <span class="seats"><?php echo $showtime['so_ghe_trong']; ?> ghế trống</span>
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
require_once __DIR__ . '/../chung/footer.php';
?>
