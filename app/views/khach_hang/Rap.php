<?php
// NHÚNG HEADER (CHỨA MENU, LOGO, VÀ CÁC THÀNH PHẦN CHUNG)
require_once __DIR__ . '/../chung/header.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lịch Chiếu Phim - Chi tiết rạp</title>
    
    <!-- CSS RESET VÀ BASE STYLES -->
    <link rel="stylesheet" href="publics/css/reset.css" />
    <link rel="stylesheet" href="publics/css/variables.css" />
    <link rel="stylesheet" href="publics/css/container.css" />
    <link rel="stylesheet" href="publics/css/button.css" />
    <link rel="stylesheet" href="publics/css/card.css" />
    <link rel="stylesheet" href="publics/css/Rap.css" /> <!-- CSS RIÊNG CHO TRANG RẠP -->
    
    <!-- FONT AWESOME ICONS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    
    <!-- CUSTOM STYLES -->
    <link rel="stylesheet" href="publics/css/styles.css" />
</head>
<body>
    <main class="container">
        <div class="page-layout">
            <!-- =========================================== -->
            <!-- PHẦN CHÍNH: THÔNG TIN RẠP VÀ BÌNH LUẬN -->
            <!-- =========================================== -->
            <section class="cinema-info">
                <!-- TIÊU ĐỀ RẠP -->
                <h1 class="cinema-title">
                    <?php echo htmlspecialchars($rap['ten_rap']); ?>, <?php echo htmlspecialchars($rap['thanh_pho']); ?>
                </h1>

                <!-- ẢNH RẠP -->
                <img
                    src="<?php echo htmlspecialchars($rap['anh_rap']); ?>"
                    alt="Hình ảnh <?php echo htmlspecialchars($rap['ten_rap']); ?>"
                    class="cinema-image"
                />

                <!-- THÔNG TIN CHI TIẾT RẠP -->
                <p>
                    Rạp **<?php echo htmlspecialchars($rap['ten_rap']); ?>** tọa lạc tại **<?php echo htmlspecialchars($rap['dia_chi']); ?>**.
                    (Số điện thoại: <?php echo htmlspecialchars($rap['SDT']); ?>)
                </p>
                <!-- MÔ TẢ RẠP (GIỮ LẠI NGẮT DÒNG VỚI nl2br) -->
                <p><?php echo nl2br(htmlspecialchars($rap['mo_ta_rap'])); ?></p>

                <!-- PHẦN BÌNH LUẬN -->
                <div class="comments-section">
                    <h3 class="comments-title">Bình luận (<?php echo count($danh_gia_list); ?>)</h3>
                    
                    <!-- THÔNG BÁO THÀNH CÔNG/THẤT BẠI (FLASH MESSAGES) -->
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success">
                            <?php echo $_SESSION['success']; ?>
                            <?php unset($_SESSION['success']); // XÓA MESSAGE SAU KHI HIỂN THỊ ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-error">
                            <?php echo $_SESSION['error']; ?>
                            <?php unset($_SESSION['error']); // XÓA MESSAGE SAU KHI HIỂN THỊ ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- FORM BÌNH LUẬN -->
                    <form class="comment-form" method="POST" action="index.php?controller=rap&action=index">
                        <input type="hidden" name="ma_rap" value="<?php echo $ma_rap; ?>">
                        
                        <div class="form-group">
                            <textarea rows="4" placeholder="Viết bình luận của bạn..." name="noi_dung" required></textarea>
                        </div>
                        
                        <!-- KIỂM TRA ĐĂNG NHẬP -->
                        <?php 
                        // KIỂM TRA NẾU KHÁCH HÀNG CHƯA ĐĂNG NHẬP
                        if (!$isLoggedIn): ?>
                            <p class="login-required">
                                Vui lòng <a href="index.php?controller=KhachHang&action=index">đăng nhập</a> để bình luận
                            </p>
                        <?php else: ?>
                            <!-- NẾU ĐÃ ĐĂNG NHẬP, HIỆN NÚT GỬI BÌNH LUẬN -->
                            <button type="submit" class="btn btn-primary">Gửi bình luận</button>
                        <?php endif; ?>
                    </form>

                    <!-- DANH SÁCH BÌNH LUẬN -->
                    <div class="comment-list">
                        <?php if (empty($danh_gia_list)): ?>
                            <p>Chưa có bình luận nào.</p>
                        <?php endif; ?>

                        <!-- LẶP QUA DANH SÁCH BÌNH LUẬN -->
                        <?php foreach ($danh_gia_list as $danh_gia): ?>
                            <div class="comment">
                                <!-- AVATAR NGƯỜI BÌNH LUẬN -->
                                <img
                                    src="<?php echo htmlspecialchars($danh_gia['avatar'] ?? '../../publics/img/avatar_default.png'); ?>"
                                    alt="Avatar"
                                    class="comment-avatar"
                                />
                                <div class="comment-content">
                                    <!-- TÊN NGƯỜI BÌNH LUẬN -->
                                    <div class="comment-author"><?php echo htmlspecialchars($danh_gia['ho_ten']); ?></div>
                                    <!-- NỘI DUNG BÌNH LUẬN -->
                                    <p class="comment-body"><?php echo htmlspecialchars($danh_gia['noi_dung']); ?></p>
                                    <!-- CÁC HÀNH ĐỘNG (LIKE, REPLY, THỜI GIAN) -->
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

            <!-- =========================================== -->
            <!-- SIDEBAR: PHIM ĐANG HOT -->
            <!-- =========================================== -->
            <aside class="hot-movies">
                <h2 class="section-title">PHIM ĐANG HOT</h2>
                <div class="movie-grid">
                    <!-- LẶP QUA DANH SÁCH PHIM HOT -->
                    <?php foreach ($hot_movies as $phim): ?>
                        <div class="movie-item">
                            <!-- CARD PHIM -->
                            <div class="card">
                                <img
                                    src="<?php echo htmlspecialchars($phim['anh_trailer']); ?>"
                                    alt="<?php echo htmlspecialchars($phim['ten_phim']); ?>"
                                />
                                <!-- TAG GIỚI HẠN ĐỘ TUỔI -->
                                <span class="movie-tag">T<?php echo htmlspecialchars($phim['gioi_han_do_tuoi']); ?></span>
                            </div>
                            <!-- LINK ĐẾN TRANG CHI TIẾT PHIM -->
                            <a href="index.php?controller=phim&action=detail&ma_phim=<?php echo $phim['ma_phim']; ?>&ma_rap=<?php echo $ma_rap; ?>">
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
// NHÚNG FOOTER (CHỨA COPYRIGHT, LINKS, VÀ CÁC THÀNH PHẦN CHUNG)
require_once __DIR__ . '/../chung/footer.php';
?>