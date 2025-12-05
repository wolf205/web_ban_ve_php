<?php
// Giả định Controller đã cung cấp các biến:
// $phimDangChieu = [ [...], [...], ... ];
// $phimSapChieu = [ [...], [...], ... ];
// $suatChieuDacBiet = [ [...], [...], ... ]; (Giả định là phim hot)
require_once __DIR__ . '/../chung/header.php';
?>

<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Danh Sách Phim - Beta Cinemas</title>
    <link rel="stylesheet" href="publics/css/reset.css" />
  <link rel="stylesheet" href="publics/css/variables.css" />
  <link rel="stylesheet" href="publics/css/container.css" />
  <link rel="stylesheet" href="publics/css/button.css" />
  <link rel="stylesheet" href="publics/css/card.css" />
  <!-- <link rel="stylesheet" href="publics/css/LichChieu.css" /> -->
  <link rel="stylesheet" href="publics/css/Phim.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="publics/css/styles.css" />
  </head>
  <body>
    <main>
      <?php require_once __DIR__ . '/_phim_tabs_view.php'; ?>
    </main>
  </body>
</html>
<?php
require_once __DIR__ . '/../chung/footer.php';
?>