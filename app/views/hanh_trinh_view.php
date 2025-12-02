<?php
require_once __DIR__ . '/header.php';
?>
<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Hành trình điện ảnh - Beta Cinemas</title>

    <link rel="stylesheet" href="publics/css/variables.css" />
    <link rel="stylesheet" href="publics/css/reset.css" />
    <link rel="stylesheet" href="publics/css/container.css" />
    <link rel="stylesheet" href="publics/css/button.css" />
    <link rel="stylesheet" href="publics/css/card.css" />
    <link rel="stylesheet" href="publics/css/taikhoan1.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    <link rel="stylesheet" href="publics/css/styles.css" />
  </head>
  <body>
    <main class="container account-section">
      <div class="account-layout">
        <div class="account-topnav">
          <nav>
            <a href="index.php?controller=khachhang&action=profile" class="sidebar-item">THÔNG TIN TÀI KHOẢN</a>
            <a href="index.php?controller=khachhang&action=hanhTrinh" class="sidebar-item active">
              HÀNH TRÌNH ĐIỆN ẢNH
            </a>
          </nav>
        </div>

        <div class="account-content">
          <div class="booking-history-table">
            <table class="history-table">
              <thead>
                <tr>
                  <th>Mã hóa đơn</th>
                  <th>Phim</th>
                  <th>Rạp chiếu</th>
                  <th>Suất chiếu</th>
                  <th>Ghế đã đặt</th>
                  <th>Combo/Package</th>
                  <th>Ngày đặt</th>
                </tr>
              </thead>
              <tbody>

                <?php if (!empty($bookingHistory)): ?>
                  <?php foreach ($bookingHistory as $row): ?>
                  <tr>
                    <td><?= $row['ma_hd'] ?></td>
                    <td><?= $row['ten_phim'] ?></td>
                    <td><?= $row['ten_rap'] ?></td>
                    <td><?= $row['ngay_chieu'] ?></td>
                    <td><?= $row['ghe_da_dat'] ?></td>
                    <td><?= $row['combo_da_mua'] ?? '—' ?></td>
                    <td><?= date('d/m/Y', strtotime($row['ngay_tao'])) ?></td>
                  </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="7" style="text-align:center;">Bạn chưa đặt vé nào.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>

      </div>
    </main>
  </body>
</html>
<?php
require_once __DIR__ . '/footer.php';
?>
