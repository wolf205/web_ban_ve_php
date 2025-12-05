<?php
// app/views/admin/dashboard_view.php
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>CINETIX - Dashboard (PHP)</title>
    <link rel="stylesheet" href="publics/css/admin-layout1.css" />
    <link rel="stylesheet" href="publics/css/dashboard.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>  
</head>
<body>
    <?php include __DIR__ . '/../chung/header_sidebar.php'; ?>

    <main class="main-content">
        <div class="tieu_de"><h3>Tổng quan Doanh thu & Hiệu suất</h3></div>

        <!-- BỘ LỌC CHÍNH -->
        <section class="top-filter-section">
            <form method="GET" action="" id="main-filter-form">
                <input type="hidden" name="controller" value="Dashboard">
                <input type="hidden" name="action" value="index">
                
                <div class="filter-bar">
                    <div class="filter-group">
                        <label for="filter-time">Thời gian:</label>
                        <select id="filter-time" name="filter-time" onchange="handleTimeFilterChange()">
                            <option value="today" <?= ($filter_time ?? 'today') == 'today' ? 'selected' : '' ?>>Hôm nay</option>
                            <option value="week" <?= ($filter_time ?? '') == 'week' ? 'selected' : '' ?>>7 ngày qua</option>
                            <option value="month" <?= ($filter_time ?? '') == 'month' ? 'selected' : '' ?>>Tháng này</option>
                            <option value="custom_date" <?= ($filter_time ?? '') == 'custom_date' ? 'selected' : '' ?>>Tùy chọn ngày</option>
                            <option value="custom_month" <?= ($filter_time ?? '') == 'custom_month' ? 'selected' : '' ?>>Tùy chọn tháng</option>
                        </select>
                    </div>

                    <div class="filter-group" id="custom-date-fields" style="display: <?= ($filter_time ?? '') == 'custom_date' ? 'block' : 'none' ?>;">
                        <label for="custom_start_date">Từ:</label>
                        <input type="date" id="custom_start_date" name="custom_start_date" 
                               value="<?= $_GET['custom_start_date'] ?? date('Y-m-d') ?>">
                        <label for="custom_end_date">Đến:</label>
                        <input type="date" id="custom_end_date" name="custom_end_date" 
                               value="<?= $_GET['custom_end_date'] ?? date('Y-m-d') ?>">
                    </div>

                    <div class="filter-group" id="custom-month-field" style="display: <?= ($filter_time ?? '') == 'custom_month' ? 'block' : 'none' ?>;">
                        <label for="custom_month">Tháng:</label>
                        <input type="month" id="custom_month" name="custom_month" 
                               value="<?= $_GET['custom_month'] ?? date('Y-m') ?>">
                    </div>

                    <div class="filter-group">
                        <label for="filter-cinema">Rạp:</label>
                        <select id="filter-cinema" name="filter-cinema">
                            <option value="all" <?= ($filter_cinema ?? 'all') == 'all' ? 'selected' : '' ?>>Tất cả Rạp</option>
                            <?php foreach ($cinemas_filter as $cinema): ?>
                                <option value="<?= $cinema['ma_rap'] ?>" <?= ($filter_cinema ?? '') == $cinema['ma_rap'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cinema['ten_rap']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="filter-btn">
                            Xem báo cáo
                        </button>
                    </div>
                </div>
            </form>
        </section>

        <!-- THỐNG KÊ CHÍNH -->
        <section class="stats-cards" id="stats-cards-container">
            <div class="stat-card">
                <div class="stat-icon">🎫</div>
                <div class="stat-info">
                    <h4><?= number_format($stats['total_tickets'] ?? 0) ?></h4>
                    <p>Tổng vé bán</p>
                    <small><?= $time_range_display ?></small>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">💰</div>
                <div class="stat-info">
                    <h4><?= number_format($stats['total_revenue'] ?? 0) ?> đ</h4>
                    <p>Doanh thu</p>
                    <small><?= $time_range_display ?></small>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">📈</div>
                <div class="stat-info">
                    <h4><?= number_format($stats['fill_rate'] ?? 0, 1) ?>%</h4>
                    <p>Tỷ lệ lấp đầy ghế</p>
                    <small><?= $time_range_display ?></small>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">🎬</div>
                <div class="stat-info">
                    <h4><?= $stats['movies_count'] ?? 0 ?></h4>
                    <p>Phim đang chiếu</p>
                </div>
            </div>
        </section>

        <!-- BIỂU ĐỒ -->
        <section class="charts-section">
            <div class="chart-container">
                <h3>📊 Doanh thu 7 ngày gần nhất</h3>
                <canvas id="revenueChart"></canvas>
            </div>
            <div class="chart-container">
                <h3>🎬 Phân bố theo thể loại</h3>
                <canvas id="genreChart"></canvas>
            </div>
        </section>

        <!-- TOP PHIM -->
        <section class="top-movies-section">
            <div class="top-movies-container">
                <h3>🏆 Top 5 Phim Bán Chạy Nhất</h3>
                <div class="time-range-label"><?= $time_range_display ?></div>
                <div id="top-movies-list">
                    <?php if (empty($top_movies)): ?>
                        <p class="no-data">Chưa có dữ liệu</p>
                    <?php else: ?>
                        <?php foreach ($top_movies as $index => $movie): ?>
                            <div class="movie-item">
                                <div class="movie-rank">#<?= $index + 1 ?></div>
                                <div class="movie-info">
                                    <h4><?= htmlspecialchars($movie['ten_phim']) ?></h4>
                                    <div class="movie-stats">
                                        <span>📊 <?= number_format($movie['sold_tickets'] ?? 0) ?> vé</span>
                                        <span>💰 <?= number_format($movie['revenue'] ?? 0) ?> đ</span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- LỊCH CHIẾU -->
        <section class="data-section">
            <div class="table-container">
                <div class="table-header-with-filter">
                    <h3>📋 Danh sách lịch chiếu</h3>
                    <form method="GET" action="" id="showtime-filter-form">
                        <input type="hidden" name="controller" value="Dashboard">
                        <input type="hidden" name="action" value="index">
                        <input type="hidden" name="filter-time" value="<?= $filter_time ?? 'today' ?>">
                        <input type="hidden" name="filter-cinema" value="<?= $filter_cinema ?? 'all' ?>">
                        
                        <div class="filter-bar">
                            <div class="filter-group">
                                <label for="date-picker">📅 Ngày:</label>
                                <input type="date" id="date-picker" name="date-picker" 
                                       value="<?= $date_picker ?? date('Y-m-d') ?>">
                            </div>
                            <div class="filter-group">
                                <label for="movie-select">🎬 Phim:</label>
                                <select id="movie-select" name="movie-select">
                                    <option value="all">Tất cả</option>
                                    <?php foreach ($movies_filter as $movie): ?>
                                        <option value="<?= $movie['ma_phim'] ?>" <?= ($movie_select ?? '') == $movie['ma_phim'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($movie['ten_phim']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="filter-group">
                                <label for="room-select">🏢 Phòng:</label>
                                <select id="room-select" name="room-select">
                                    <option value="all">Tất cả</option>
                                    <?php foreach ($rooms_filter as $room): ?>
                                        <option value="<?= $room['ma_phong'] ?>" <?= ($room_select ?? '') == $room['ma_phong'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($room['ten_phong']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" class="filter-btn">
                                    Lọc lịch chiếu
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Tên phim</th>
                            <th>Rạp</th>
                            <th>Phòng chiếu</th>
                            <th>Giờ chiếu</th>
                            <th>Số vé đã bán</th>
                            <th>Tổng doanh thu</th>
                            <th>Tỷ lệ lấp đầy</th>
                        </tr>
                    </thead>
                    <tbody id="showtimes-table-body">
                        <?php if (empty($showtimes)): ?>
                            <tr>
                                <td colspan="7" class="no-data">Không có lịch chiếu nào</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($showtimes as $showtime): ?>
                                <tr>
                                    <td><?= htmlspecialchars($showtime['ten_phim']) ?></td>
                                    <td><?= htmlspecialchars($showtime['ten_rap']) ?></td>
                                    <td><?= htmlspecialchars($showtime['ten_phong']) ?></td>
                                    <td><?= date('H:i', strtotime($showtime['gio_bat_dau'])) ?></td>
                                    <td><?= $showtime['sold_tickets'] ?? 0 ?></td>
                                    <td><?= number_format($showtime['revenue'] ?? 0) ?> đ</td>
                                    <td>
                                        <div class="fill-rate-bar">
                                            <div class="fill-rate-fill" style="width: <?= min($showtime['fill_rate'] ?? 0, 100) ?>%"></div>
                                            <span><?= number_format($showtime['fill_rate'] ?? 0, 1) ?>%</span>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <script>
        // Khởi tạo biểu đồ doanh thu
        <?php if (!empty($revenue_chart)): ?>
        const revenueData = <?= json_encode($revenue_chart) ?>;
        const revenueLabels = revenueData.map(item => {
            const date = new Date(item.date);
            return date.toLocaleDateString('vi-VN', { weekday: 'short', day: '2-digit' });
        });
        const revenueValues = revenueData.map(item => item.revenue);
        
        const revenueCtx = document.getElementById('revenueChart');
        if (revenueCtx) {
            new Chart(revenueCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: revenueLabels,
                    datasets: [{
                        label: 'Doanh thu (VNĐ)',
                        data: revenueValues,
                        borderColor: '#36a2eb',
                        backgroundColor: 'rgba(54, 162, 235, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString('vi-VN') + ' đ';
                                }
                            }
                        }
                    }
                }
            });
        }
        <?php endif; ?>

        // Khởi tạo biểu đồ thể loại
        <?php if (!empty($genre_chart)): ?>
        const genreData = <?= json_encode($genre_chart) ?>;
        const genreLabels = genreData.map(item => item.the_loai);
        const genreValues = genreData.map(item => item.count);
        const genreColors = [
            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', 
            '#9966FF', '#FF9F40', '#8AC926', '#1982C4'
        ];
        
        const genreCtx = document.getElementById('genreChart');
        if (genreCtx) {
            new Chart(genreCtx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: genreLabels,
                    datasets: [{
                        data: genreValues,
                        backgroundColor: genreColors,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
        <?php endif; ?>

        // Xử lý thay đổi filter thời gian
        function handleTimeFilterChange() {
            const timeFilter = document.getElementById('filter-time').value;
            const dateFields = document.getElementById('custom-date-fields');
            const monthField = document.getElementById('custom-month-field');
            
            if (dateFields) {
                dateFields.style.display = timeFilter === 'custom_date' ? 'block' : 'none';
            }
            if (monthField) {
                monthField.style.display = timeFilter === 'custom_month' ? 'block' : 'none';
            }
        }

        // Hàm gọi API AJAX để cập nhật dữ liệu động
        function updateDashboardData() {
            const timeFilter = document.getElementById('filter-time').value;
            const cinemaFilter = document.getElementById('filter-cinema').value;
            
            // Cập nhật thống kê
            fetch(`index.php?controller=Dashboard&action=api&action=stats&time_range=${timeFilter}&cinema_id=${cinemaFilter}`)
                .then(response => response.json())
                .then(data => {
                    updateStats(data);
                })
                .catch(error => console.error('Lỗi khi tải thống kê:', error));
            
            // Cập nhật biểu đồ doanh thu
            fetch(`index.php?controller=Dashboard&action=api&action=revenue_chart&cinema_id=${cinemaFilter}`)
                .then(response => response.json())
                .then(data => {
                    updateRevenueChart(data);
                });
            
            // Cập nhật biểu đồ thể loại
            fetch(`index.php?controller=Dashboard&action=api&action=genre_chart&time_range=${timeFilter}&cinema_id=${cinemaFilter}`)
                .then(response => response.json())
                .then(data => {
                    updateGenreChart(data);
                });
            
            // Cập nhật top phim
            fetch(`index.php?controller=Dashboard&action=api&action=top_movies&time_range=${timeFilter}&cinema_id=${cinemaFilter}`)
                .then(response => response.json())
                .then(data => {
                    updateTopMovies(data);
                });
        }

        function updateStats(data) {
            const statsContainer = document.getElementById('stats-cards-container');
            if (statsContainer) {
                statsContainer.innerHTML = `
                    <div class="stat-card">
                        <div class="stat-icon">🎫</div>
                        <div class="stat-info">
                            <h4>${(data.total_tickets || 0).toLocaleString('vi-VN')}</h4>
                            <p>Tổng vé bán</p>
                            <small><?= $time_range_display ?></small>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon">💰</div>
                        <div class="stat-info">
                            <h4>${(data.total_revenue || 0).toLocaleString('vi-VN')} đ</h4>
                            <p>Doanh thu</p>
                            <small><?= $time_range_display ?></small>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon">📈</div>
                        <div class="stat-info">
                            <h4>${parseFloat(data.fill_rate || 0).toFixed(1)}%</h4>
                            <p>Tỷ lệ lấp đầy ghế</p>
                            <small><?= $time_range_display ?></small>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon">🎬</div>
                        <div class="stat-info">
                            <h4>${data.movies_count || 0}</h4>
                            <p>Phim đang chiếu</p>
                        </div>
                    </div>
                `;
            }
        }

        function updateTopMovies(movies) {
            const topMoviesContainer = document.getElementById('top-movies-list');
            if (topMoviesContainer) {
                if (!movies || movies.length === 0) {
                    topMoviesContainer.innerHTML = '<p class="no-data">Chưa có dữ liệu</p>';
                } else {
                    let html = '';
                    movies.forEach((movie, index) => {
                        html += `
                            <div class="movie-item">
                                <div class="movie-rank">#${index + 1}</div>
                                <div class="movie-info">
                                    <h4>${movie.ten_phim || 'N/A'}</h4>
                                    <div class="movie-stats">
                                        <span>📊 ${(movie.sold_tickets || 0).toLocaleString('vi-VN')} vé</span>
                                        <span>💰 ${(movie.revenue || 0).toLocaleString('vi-VN')} đ</span>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    topMoviesContainer.innerHTML = html;
                }
            }
        }

        // Khởi tạo khi trang load
        document.addEventListener('DOMContentLoaded', function() {
            handleTimeFilterChange();
            
            // Lắng nghe sự kiện thay đổi filter
            document.getElementById('filter-time')?.addEventListener('change', function() {
                handleTimeFilterChange();
            });
            
            document.getElementById('filter-cinema')?.addEventListener('change', function() {
                document.getElementById('main-filter-form').submit();
            });
            
            // Tự động submit form khi thay đổi ngày
            document.getElementById('date-picker')?.addEventListener('change', function() {
                document.getElementById('showtime-filter-form').submit();
            });
            
            document.getElementById('movie-select')?.addEventListener('change', function() {
                document.getElementById('showtime-filter-form').submit();
            });
            
            document.getElementById('room-select')?.addEventListener('change', function() {
                document.getElementById('showtime-filter-form').submit();
            });
        });
    </script>
</body>
</html>