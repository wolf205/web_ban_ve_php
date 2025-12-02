<?php
// File này giả định Controller đã cung cấp 3 biến:
// $phimDangChieu = [ [...], [...], ... ];
// $phimSapChieu = [ [...], [...], ... ];
// $suatChieuDacBiet = [ [...], [...], ... ]; (Giả định là phim hot)
?>
<section class="section container">
    <nav class="movie-tabs">
      <a href="#dang-chieu" class="active" data-tab="dang-chieu">Phim Đang Chiếu</a>
      <a href="#sap-chieu" data-tab="sap-chieu">Phim Sắp Chiếu</a>
      <a href="#suat-chieu-dac-biet" data-tab="suat-chieu-dac-biet">Suất Chiếu Đặc Biệt</a>
    </nav>

    <div id="dang-chieu" class="movie-grid tab-content active">
      <?php foreach ($phimDangChieu as $phim): ?>
        <div class="movie-item">
            <a href="index.php?controller=phim&action=detail&ma_phim=<?php echo $phim['ma_phim']; ?>&ma_rap=<?php echo $selected_rap_id; ?>">
                <div class="card">
                    <img src="<?php echo $phim['anh_trailer']; ?>" alt="Poster <?php echo $phim['ten_phim']; ?>">
                    <span class="movie-tag"><?php echo $phim['gioi_han_do_tuoi']; ?></span>
                </div>
            </a>
            <div class="movie-info">
                <h3><a href="index.php?controller=phim&action=detail&ma_phim=<?php echo $phim['ma_phim']; ?>&ma_rap=<?php echo $selected_rap_id; ?>"><?php echo $phim['ten_phim']; ?></a></h3>
                <div class="movie-info-details">
                    Thể loại: <?php echo $phim['the_loai']; ?><br>
                    Thời lượng: <?php echo $phim['thoi_luong']; ?> phút
                </div>
                <a href="index.php?controller=phim&action=detail&ma_phim=<?php echo $phim['ma_phim']; ?>&ma_rap=<?php echo $selected_rap_id;?>" class="btn btn-primary">Mua vé</a>
            </div>
        </div>
      <?php endforeach; ?>
    </div>

    <div id="sap-chieu" class="movie-grid tab-content" style="display: none;">
      <?php foreach ($phimSapChieu as $phim): ?>
        <div class="movie-item">
            <a href="index.php?controller=phim&action=detail&ma_phim=<?php echo $phim['ma_phim']; ?>&ma_rap=<?php echo $selected_rap_id; ?>">
                <div class="card">
                    <img src="<?php echo $phim['anh_trailer']; ?>" alt="Poster <?php echo $phim['ten_phim']; ?>">
                    <span class="movie-tag"><?php echo $phim['gioi_han_do_tuoi']; ?></span>
                </div>
            </a>
            <div class="movie-info">
                <h3><a href="index.php?controller=phim&action=detail&ma_phim=<?php echo $phim['ma_phim']; ?>&ma_rap=<?php echo $selected_rap_id; ?>"><?php echo $phim['ten_phim']; ?></a></h3>
                <div class="movie-info-details">
                    Thể loại: <?php echo $phim['the_loai']; ?><br>
                    Ngày khởi chiếu: <?php echo date('d/m/Y', strtotime($phim['ngay_khoi_chieu'])); ?>
                </div>
                <a href="index.php?controller=phim&action=detail&ma_phim=<?php echo $phim['ma_phim']; ?>&ma_rap=<?php echo $selected_rap_id; ?>" class="btn btn-primary">Mua Vé</a>
            </div>
        </div>
      <?php endforeach; ?>
    </div>

    <div id="suat-chieu-dac-biet" class="movie-grid tab-content" style="display: none;">
       <?php foreach ($suatChieuDacBiet as $phim): ?>
        <div class="movie-item">
            <a href="index.php?controller=phim&action=detail&ma_phim=<?php echo $phim['ma_phim']; ?>&ma_rap=<?php echo $selected_rap_id; ?>">
                <div class="card">
                    <img src="<?php echo $phim['anh_trailer']; ?>" alt="Poster <?php echo $phim['ten_phim']; ?>">
                    <span class="movie-tag"><?php echo $phim['gioi_han_do_tuoi']; ?></span>
                </div>
            </a>
            <div class="movie-info">
                <h3><a href="index.php?controller=phim&action=detail&ma_phim=<?php echo $phim['ma_phim']; ?>&ma_rap=<?php echo $selected_rap_id; ?>"><?php echo $phim['ten_phim']; ?></a></h3>
                <div class="movie-info-details">
                    Thể loại: <?php echo $phim['the_loai']; ?><br>
                    Thời lượng: <?php echo $phim['thoi_luong']; ?> phút
                </div>
                <a href="index.php?controller=phim&action=detail&ma_phim=<?php echo $phim['ma_phim']; ?>&ma_rap=<?php echo $selected_rap_id;?>" class="btn btn-primary">Mua vé</a>
            </div>
        </div>
       <?php endforeach; ?>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chỉ chạy nếu các tab tồn tại trên trang
        const movieTabs = document.querySelectorAll('.movie-tabs a[data-tab]');
        
        if (movieTabs.length > 0) {
            
            // Hàm xử lý khi bấm tab
            function switchMovieTab(e) {
                e.preventDefault();
                
                // Tìm tab và nội dung đang active
                const currentActiveTab = document.querySelector('.movie-tabs a.active');
                
                // Chỉ chạy nếu bấm vào tab chưa active
                if (currentActiveTab && currentActiveTab !== e.currentTarget) {
                    const oldContentId = currentActiveTab.getAttribute('data-tab');
                    
                    // Bỏ active ở tab và nội dung cũ
                    currentActiveTab.classList.remove('active');
                    const oldContent = document.getElementById(oldContentId);
                    if (oldContent) {
                        oldContent.style.display = 'none';
                        oldContent.classList.remove('active');
                    }
                
                    // Thêm active cho tab và nội dung mới
                    const newTab = e.currentTarget;
                    newTab.classList.add('active');
                    const newContentId = newTab.getAttribute('data-tab');
                    const newContent = document.getElementById(newContentId);
                    if (newContent) {
                        newContent.style.display = 'grid'; // Dùng 'grid' để tuân thủ layout
                        newContent.classList.add('active');
                    }
                }
            }

            // Gán sự kiện click cho từng tab
            movieTabs.forEach(tab => {
                // Thêm một kiểm tra để tránh gán sự kiện nhiều lần
                if (!tab.dataset.eventAttached) {
                    tab.addEventListener('click', switchMovieTab);
                    tab.dataset.eventAttached = 'true';
                }
            });
        }
    });
</script>