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
  <link rel="stylesheet" href="publics/css/Rap.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="publics/css/styles.css" />
</head>
<body>
  <main class="container">
    <div class="page-layout">
      <section class="cinema-info">
        <h1 class="cinema-title">
          <?php echo htmlspecialchars($rap['ten_rap']); ?>, <?php echo htmlspecialchars($rap['thanh_pho']); ?>
        </h1>

        <img
          src="<?php echo htmlspecialchars($rap['anh_rap']); ?>"
          alt="Hình ảnh <?php echo htmlspecialchars($rap['ten_rap']); ?>"
          class="cinema-image"
        />

        <p>
          Rạp <?php echo htmlspecialchars($rap['ten_rap']); ?> tọa lạc tại <?php echo htmlspecialchars($rap['dia_chi']); ?>.
          (Số điện thoại: <?php echo htmlspecialchars($rap['SDT']); ?>)
        </p>
        <p><?php echo nl2br(htmlspecialchars($rap['mo_ta_rap'])); ?></p>

        <div class="comments-section">
          <h3 class="comments-title">Bình luận (<?php echo count($danh_gia_list); ?>)</h3>
          
          <!-- HIỂN THỊ THÔNG BÁO -->
          <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
              <?php echo $_SESSION['success']; ?>
              <?php unset($_SESSION['success']); ?>
            </div>
          <?php endif; ?>
          
          <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
              <?php echo $_SESSION['error']; ?>
              <?php unset($_SESSION['error']); ?>
            </div>
          <?php endif; ?>
          
          <!-- FORM BÌNH LUẬN -->
          <!-- FORM BÌNH LUẬN -->
<form class="comment-form" method="POST" action="index.php?controller=rap&action=showDetails">
    <input type="hidden" name="ma_rap" value="<?php echo $ma_rap; ?>">
    
    <div class="form-group">
        <textarea rows="4" placeholder="Viết bình luận của bạn..." name="noi_dung" required></textarea>
    </div>
    
    <?php 
    // SỬA Ở ĐÂY: Kiểm tra $_SESSION['khach_hang'] thay vì $_SESSION['ma_kh']
    if (!isset($_SESSION['khach_hang']) || empty($_SESSION['khach_hang']['ma_kh'])): ?>
        <p class="login-required">
            Vui lòng <a href="index.php?controller=KhachHang&action=index">đăng nhập</a> để bình luận
        </p>
    <?php else: ?>
        <button type="submit" class="btn btn-primary">Gửi bình luận</button>
    <?php endif; ?>
</form>

          <div class="comment-list">
            <?php if (empty($danh_gia_list)): ?>
              <p>Chưa có bình luận nào.</p>
            <?php endif; ?>

            <?php foreach ($danh_gia_list as $danh_gia): ?>
              <div class="comment">
                <img
                  src="<?php echo htmlspecialchars($danh_gia['avatar'] ?? '../../publics/img/avatar_default.png'); ?>"
                  alt="Avatar"
                  class="comment-avatar"
                />
                <div class="comment-content">
                  <div class="comment-author"><?php echo htmlspecialchars($danh_gia['ho_ten']); ?></div>
                  <p class="comment-body"><?php echo htmlspecialchars($danh_gia['noi_dung']); ?></p>
                  <div class="comment-actions">
                    <a href="#">Thích</a>
                    <span>·</span>
                    <a href="#">Phản hồi</a>
                    <span>·</span>
                    <span class="comment-time"><?php echo htmlspecialchars($danh_gia['ngay_danh_gia']); ?></span>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </section>

      <aside class="hot-movies">
        <h2 class="section-title">PHIM ĐANG HOT</h2>
        <div class="movie-grid">
          <?php foreach ($hot_movies as $phim): ?>
            <div class="movie-item">
              <div class="card">
                <img
                  src="<?php echo htmlspecialchars($phim['anh_trailer']); ?>"
                  alt="<?php echo htmlspecialchars($phim['ten_phim']); ?>"
                />
                <span class="movie-tag">T<?php echo htmlspecialchars($phim['gioi_han_do_tuoi']); ?></span>
              </div>
              <a href="index.php?controller=phim&action=detail&ma_phim=<?php echo $phim['ma_phim']; ?>&ma_rap=<?php echo $selected_rap_id; ?>">
                <h3 class="movie-title"><?php echo htmlspecialchars($phim['ten_phim']); ?></h3>
              </a>
            </div>
          <?php endforeach; ?>
        </div>
      </aside>
    </div>
  </main>
</body>
</html>
<?php
require_once __DIR__ . '/../chung/footer.php';
?>