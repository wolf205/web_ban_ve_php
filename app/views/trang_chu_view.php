<?php
// Giả định Controller (trang_chu_controller.php) đã cung cấp các biến:
// 1. $banners (cho slider)
// 2. $phimDangChieu (cho partial view)
// 3. $phimSapChieu (cho partial view)
// 4. $suatChieuDacBiet (cho partial view)
require_once __DIR__ . '/header.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ - Beta Cinemas</title>

    <link rel="stylesheet" href="publics/css/reset.css" />
  <link rel="stylesheet" href="publics/css/variables.css" />
  <link rel="stylesheet" href="publics/css/container.css" />
  <link rel="stylesheet" href="publics/css/button.css" />
  <link rel="stylesheet" href="publics/css/card.css" />
  <link rel="stylesheet" href="publics/css/LichChieu.css" />
  <link rel="stylesheet" href="publics/css/Phim.css" />
  <link rel="stylesheet" href="publics/css/trang_chu.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="publics/css/styles.css" />

</head>
<body>
    <main>
        <section class="hero-slider">
            <?php foreach ($banners as $banner): ?>
                <div class="slide-item">
                    <img src="<?php echo $banner['anh_trailer']; ?>" alt="Banner <?php echo $banner['ten_phim']; ?>">
                </div>
            <?php endforeach; ?>
        </section>

        <?php require_once __DIR__ . '/_phim_tabs_view.php'; ?>

    </main>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // 1. Tìm tất cả các slide
            const slides = document.querySelectorAll('.hero-slider .slide-item');
            if (slides.length > 1) { // Chỉ chạy nếu có nhiều hơn 1 slide
                let currentSlide = 0; // Bắt đầu từ slide đầu tiên

                // 2. Hiển thị slide đầu tiên ngay lập tức
                slides[currentSlide].classList.add('active');

                // 3. Hàm chuyển slide
                function nextSlide() {
                    // Ẩn slide hiện tại
                    slides[currentSlide].classList.remove('active');
                    
                    // Tính toán slide tiếp theo
                    currentSlide = (currentSlide + 1) % slides.length;
                    
                    // Hiển thị slide tiếp theo
                    slides[currentSlide].classList.add('active');
                }

                // 4. Tự động gọi hàm nextSlide() mỗi 5 giây
                setInterval(nextSlide, 5000); // 5000 mili-giây = 5 giây
            } else if (slides.length === 1) {
                // Nếu chỉ có 1 slide, hiển thị nó luôn
                slides[0].classList.add('active');
            }
        });
    </script>
</body>
</html>
<?php
require_once __DIR__ . '/footer.php';
?>