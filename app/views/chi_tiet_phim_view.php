<?php
// Giả định Controller đã cung cấp các biến:
// $phim = [ 'ma_phim' => 1, 'ten_phim' => '...', 'mo_ta' => '...', ... ];
// $lichChieuTheoNgay = [
//    "01/11 - T7" => [
//        "dinh_dang" => "2D PHỤ ĐỀ",
//        "gio" => [ ['id' => 101, 'thoi_gian' => '10:30'], ... ]
//    ],
//    "02/11 - CN" => [ ... ]
// ];
require_once __DIR__ . '/header.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Phim - <?php echo $phim['ten_phim']; ?></title>

    <link rel="stylesheet" href="publics/css/reset.css" />
    <link rel="stylesheet" href="publics/css/variables.css" />
    <link rel="stylesheet" href="publics/css/container.css" />
    <link rel="stylesheet" href="publics/css/button.css" />
    <link rel="stylesheet" href="publics/css/card.css" />
    <link rel="stylesheet" href="publics/css/chi_tiet_phim.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="publics/css/styles.css" />
</head>
<body>
    <main>
        <div class="container">
            <section class="movie-detail-grid">
                
                <div class="movie-detail-poster">
                    <div class="card">
                        <img src="<?php echo $phim['anh_trailer']; ?>" alt="Poster <?php echo $phim['ten_phim']; ?>">
                        <span class="movie-tag"><?php echo $phim['gioi_han_do_tuoi']; ?></span>
                    </div>
                </div>

                <div class="movie-detail-content">
                    <h1><?php echo $phim['ten_phim']; ?></h1>
                    <p class="synopsis">
                        <?php echo $phim['mo_ta']; ?>
                    </p>

                    <ul class="movie-metadata">
                        <li>
                            <strong>Đạo diễn:</strong>
                            <span><?php echo $phim['dao_dien']; ?></span>
                        </li>
                        <li>
                            <strong>Diễn viên:</strong>
                            <span><?php echo $phim['dien_vien']; ?></span>
                        </li>
                        <li>
                            <strong>Thể loại:</strong>
                            <span><?php echo $phim['the_loai']; ?></span>
                        </li>
                        <li>
                            <strong>Thời lượng:</strong>
                            <span><?php echo $phim['thoi_luong']; ?> phút</span>
                        </li>
                        <li>
                            <strong>Ngôn ngữ:</strong>
                            <span><?php echo $phim['ngon_ngu'] ?? 'Tiếng Việt'; ?></span>
                        </li>
                        <li>
                            <strong>Ngày khởi chiếu:</strong>
                            <span><?php echo date('d/m/Y', strtotime($phim['ngay_khoi_chieu'])); ?></span>
                        </li>
                    </ul>
                </div>
            </section>

            <section class="movie-showtimes">
                <section class="date-selector section">
                    <?php if (empty($lichChieuTheoNgay)): ?>
                        <a href="#" class="date-item active">Không có lịch chiếu</a>
                    <?php else: ?>
                        <?php $isFirstTab = true; ?>
                        <?php foreach ($lichChieuTheoNgay as $ngay => $data): ?>
                            <a href="#tab-<?php echo str_replace('/', '-', $ngay); ?>" 
                               class="date-item <?php echo $isFirstTab ? 'active' : ''; ?>" 
                               data-tab="tab-<?php echo str_replace('/', '-', $ngay); ?>">
                                <?php echo $ngay; ?>
                            </a>
                            <?php $isFirstTab = false; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </section>

                <?php $isFirstContent = true; ?>
                <?php foreach ($lichChieuTheoNgay as $ngay => $data): ?>
                    <div id="tab-<?php echo str_replace('/', '-', $ngay); ?>" 
                         class="showtimes tab-content" 
                         style="<?php echo $isFirstContent ? '' : 'display:none;'; ?>">
                        
                        <h4><?php echo $data['dinh_dang']; ?></h4>
                        <div class="showtimes-grid">
                            
                            <?php foreach ($data['gio'] as $suat): ?>
                                <a href="index.php?controller=dat_ve&action=chon_ghe&suat_chieu_id=<?php echo $suat['id']; ?>" class="showtime-slot">
                                    <span class="time"><?php echo $suat['thoi_gian']; ?></span>
                                    <span class="seats">Trống</span>
                                </a>
                            <?php endforeach; ?>

                        </div>
                    </div>
                    <?php $isFirstContent = false; ?>
                <?php endforeach; ?>
            </section>

        </div> 
    </main>
    <script>
        document.querySelectorAll('.date-selector .date-item').forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove active class from all tabs
                document.querySelectorAll('.date-selector .date-item').forEach(item => {
                    item.classList.remove('active');
                });
                
                // Hide all tab contents
                document.querySelectorAll('.showtimes.tab-content').forEach(content => {
                    content.style.display = 'none';
                });

                // Add active class to clicked tab
                this.classList.add('active');
                
                // Show corresponding content
                const targetId = this.getAttribute('data-tab');
                if (targetId) {
                    document.getElementById(targetId).style.display = 'block';
                }
            });
        });
    </script>
</body>
</html>
<?php
require_once __DIR__ . '/footer.php';
?>