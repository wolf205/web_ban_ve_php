<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= htmlspecialchars($rap['ten_rap']) ?></title>

    <link rel="stylesheet" href="/Project1/publics/css/reset.css" />
    <link rel="stylesheet" href="/Project1/publics/css/variables.css" />
    <link rel="stylesheet" href="/Project1/publics/css/container.css" />
    <link rel="stylesheet" href="/Project1/publics/css/button.css" />
    <link rel="stylesheet" href="/Project1/publics/css/card.css" />
    <link rel="stylesheet" href="/Project1/publics/css/Rap.css" />
  </head>

  <body>
    <header class="fake-header">
      <a href="index.php?page=rap">Rạp</a>
      <a href="index.php?page=lichchieu">Lịch chiếu</a>

    </header>

    <main class="container">
      <div class="page-layout">
        <section class="cinema-info">
          <h1 class="cinema-title"><?= htmlspecialchars($rap['ten_rap']) ?></h1>

          <img
            src="<?= $rap['hinh_anh'] ?>"
            alt="Hình ảnh <?= htmlspecialchars($rap['ten_rap']) ?>"
            class="cinema-image"
          />

          <?php foreach ($rap['mo_ta'] as $doan): ?>
            <p><?= htmlspecialchars($doan) ?></p>
          <?php endforeach; ?>

          <div class="comments-section">
            <h3 class="comments-title">Bình luận (<?= count($binhluan) ?>)</h3>

            <form class="comment-form" method="POST" action="">
              <div class="form-group">
                <textarea
                  rows="4"
                  name="comment"
                  placeholder="Viết bình luận của bạn..."
                  required
                ></textarea>
              </div>
              <button type="submit" class="btn btn-primary">
                Gửi bình luận
              </button>
            </form>

            <div class="comment-list">
              <?php foreach ($binhluan as $cmt): ?>
                <div class="comment">
                  <img
                    src="<?= $cmt['anh_dai_dien'] ?: '../../publics/img/default_avatar.png' ?>"
                    alt="Avatar"
                    class="comment-avatar"
                  />
                  <div class="comment-content">
                    <div class="comment-author"><?= htmlspecialchars($cmt['ten']) ?></div>
                    <p class="comment-body"><?= htmlspecialchars($cmt['noi_dung']) ?></p>
                    <div class="comment-actions">
                      <a href="#">Thích</a>
                      <span>·</span>
                      <a href="#">Phản hồi</a>
                      <span>·</span>
                      <span class="comment-time"><?= htmlspecialchars($cmt['thoi_gian']) ?></span>
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
            <?php foreach ($phim_hot as $phim): ?>
              <div class="movie-item">
                <div class="card">
                  <img
                    src="<?= $phim['poster'] ?>"
                    alt="Poster <?= htmlspecialchars($phim['ten']) ?>"
                  />
                  <span class="movie-tag"><?= htmlspecialchars($phim['tag']) ?></span>
                </div>
                <h3 class="movie-title"><?= htmlspecialchars($phim['ten']) ?></h3>
              </div>
            <?php endforeach; ?>
          </div>
        </aside>
      </div>
    </main>

    <footer class="fake-footer"></footer>
  </body>
</html>
