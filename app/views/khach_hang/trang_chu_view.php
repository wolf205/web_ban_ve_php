<?php
require_once __DIR__ . '/../chung/header.php';
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
  <link rel="stylesheet" href="publics/css/Phim.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="publics/css/styles.css" />
  <link rel="stylesheet" href="publics/css/trang_chu1.css" />
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
        const slides = document.querySelectorAll('.hero-slider .slide-item');
        
        if (slides.length === 0) return; // Nếu không có slide nào thì thoát
        
        let currentSlide = 0;
        
        // Hiển thị slide đầu tiên
        slides[currentSlide].classList.add('active');
        
        // Nếu chỉ có 1 slide thì không cần chạy auto-slide
        if (slides.length <= 1) return;
        
        function nextSlide() {
            // Ẩn slide hiện tại
            slides[currentSlide].classList.remove('active');
            
            // Tính slide tiếp theo
            currentSlide = (currentSlide + 1) % slides.length;
            
            // Hiển thị slide tiếp theo
            slides[currentSlide].classList.add('active');
        }
        
        // Tự động chuyển slide mỗi 5 giây
        setInterval(nextSlide, 5000);
    });
</script>
</body>
</html>
<?php
require_once __DIR__ . '/../chung/footer.php';
?>