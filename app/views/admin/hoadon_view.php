<?php
// app/views/admin/hoadon_view.php
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CINETIX - Quản lý hoá đơn</title>
    <link rel="stylesheet" href="publics/css/admin-layout1.css">
    <link rel="stylesheet" href="publics/css/admin-rap1.css" />
    <style>
        /* Chỉ giữ lại CSS cho màu trạng thái và bảng chi tiết nhỏ bên trong */
        .status-badge { padding: 5px 10px; border-radius: 4px; font-weight: bold; font-size: 0.85rem;}
        .status-success { color: #28a745; background: rgba(40, 167, 69, 0.1); border: 1px solid #28a745; }
        .status-warning { color: #ffc107; background: rgba(255, 193, 7, 0.1); border: 1px solid #ffc107; }
        .status-danger { color: #dc3545; background: rgba(220, 53, 69, 0.1); border: 1px solid #dc3545; }
        
        /* Style tối giản cho bảng con bên trong form-container */
        .detail-table { width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 20px; }
        .detail-table th { background-color: #333; color: #ffc107; padding: 10px; text-align: left; border-bottom: 1px solid #555; }
        .detail-table td { padding: 10px; border-bottom: 1px solid #444; color: #eee; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 20px; border-bottom: 1px solid #444; padding-bottom: 15px; }
        .info-col p { margin: 5px 0; color: #ccc; }
        .info-col h5 { color: #ffc107; margin-bottom: 10px; }
    </style>
</head>
<body>
<?php include __DIR__ . '/../chung/header_sidebar.php'; ?>

    <main class="main-content">

        <div class="page-header">
            <h3>QUẢN LÝ HOÁ ĐƠN</h3>
            <div></div>
        </div>

        <div class="filter-section">
            <h4>BỘ LỌC & TÌM KIẾM</h4>
            <form method="GET" action="" class="filter-form">
                <input type="hidden" name="controller" value="adminHoaDon">
                <input type="hidden" name="action" value="index">
                
                <div class="filter-row">
                    <div class="filter-group">
                        <label>Trạng thái:</label>
                        <select name="trang_thai">
                            <option value="all">Tất cả</option>
                            <option value="Đã thanh toán" <?= (($_GET['trang_thai'] ?? '') == 'Đã thanh toán') ? 'selected' : ''; ?>>Đã thanh toán</option>
                            <option value="Chưa thanh toán" <?= (($_GET['trang_thai'] ?? '') == 'Chưa thanh toán') ? 'selected' : ''; ?>>Chưa thanh toán</option>
                            <option value="Hủy" <?= (($_GET['trang_thai'] ?? '') == 'Hủy') ? 'selected' : ''; ?>>Hủy</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label>Thanh toán:</label>
                        <select name="pttt">
                            <option value="all">Tất cả</option>
                            <option value="Tiền mặt" <?= (($_GET['pttt'] ?? '') == 'Tiền mặt') ? 'selected' : ''; ?>>Tiền mặt</option>
                            <option value="Chuyển khoản" <?= (($_GET['pttt'] ?? '') == 'Chuyển khoản') ? 'selected' : ''; ?>>Chuyển khoản</option>
                            <option value="Thẻ" <?= (($_GET['pttt'] ?? '') == 'Thẻ') ? 'selected' : ''; ?>>Thẻ</option>
                            <option value="Ví điện tử" <?= (($_GET['pttt'] ?? '') == 'Ví điện tử') ? 'selected' : ''; ?>>Ví điện tử</option>
                        </select>
                    </div>

                    <div class="filter-group" style="flex-grow: 1;">
                        <label for="search">Tìm kiếm:</label>
                        <input type="text" name="search" id="search" 
                               placeholder="Tên KH hoặc Mã HĐ" 
                               value="<?= htmlspecialchars($_GET['search'] ?? ''); ?>">
                    </div>

                    <div class="filter-group">
                        <label>Từ ngày:</label>
                        <input type="date" name="tu_ngay" value="<?= htmlspecialchars($_GET['tu_ngay'] ?? ''); ?>">
                    </div>
                    <div class="filter-group">
                        <label>Đến ngày:</label>
                        <input type="date" name="den_ngay" value="<?= htmlspecialchars($_GET['den_ngay'] ?? ''); ?>">
                    </div>

                    <div class="filter-actions">
                        <button type="submit" class="btn-filter">Lọc</button>
                        <a href="index.php?controller=adminHoaDon&action=index" class="btn-reset">Xóa lọc</a>
                    </div>
                </div>
            </form>
        </div>

        <?php 
            $has_filter = !empty($_GET['search']) || 
                          (!empty($_GET['trang_thai']) && $_GET['trang_thai'] !== 'all') || 
                          (!empty($_GET['pttt']) && $_GET['pttt'] !== 'all') || 
                          !empty($_GET['tu_ngay']) || !empty($_GET['den_ngay']);
        ?>
        <?php if ($has_filter): ?>
            <div class="active-filters">
                <small>
                    <strong>Đang lọc:</strong>
                    <?php 
                    $filters = [];
                    if (!empty($_GET['search'])) $filters[] = "Tìm kiếm: " . htmlspecialchars($_GET['search']);
                    if (!empty($_GET['trang_thai']) && $_GET['trang_thai'] !== 'all') $filters[] = "Trạng thái: " . htmlspecialchars($_GET['trang_thai']);
                    if (!empty($_GET['pttt']) && $_GET['pttt'] !== 'all') $filters[] = "PTTT: " . htmlspecialchars($_GET['pttt']);
                    if (!empty($_GET['tu_ngay'])) $filters[] = "Từ: " . htmlspecialchars($_GET['tu_ngay']);
                    if (!empty($_GET['den_ngay'])) $filters[] = "Đến: " . htmlspecialchars($_GET['den_ngay']);
                    echo implode(', ', $filters);
                    ?>
                    <a href="index.php?controller=adminHoaDon&action=index" style="margin-left: 10px; color: #e74c3c;">[Xóa tất cả]</a>
                </small>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($hd_detail)): ?>
        
        <div class="form-container">
            <h4>CHI TIẾT HOÁ ĐƠN #<?= htmlspecialchars($hd_detail['ma_hoa_don']) ?></h4>

            <div class="info-row">
                <div class="info-col">
                    <h5>KHÁCH HÀNG</h5>
                    <p>Họ tên: <strong><?= htmlspecialchars($hd_detail['ho_ten'] ?? 'Khách vãng lai') ?></strong></p>
                    <p>Email: <?= htmlspecialchars($hd_detail['email'] ?? '---') ?></p>
                    <p>SĐT: <?= htmlspecialchars($hd_detail['SDT'] ?? '---') ?></p>
                </div>
                <div class="info-col">
                    <h5>THANH TOÁN</h5>
                    <p>Ngày tạo: <?= htmlspecialchars($hd_detail['ngay_tao']) ?></p>
                    <p>Phương thức: <?= htmlspecialchars($hd_detail['phuong_thuc_thanh_toan']) ?></p>
                    <p>Trạng thái: 
                        <span style="color: <?= ($hd_detail['trang_thai'] == 'Đã thanh toán') ? '#28a745' : '#ffc107' ?>">
                            <?= htmlspecialchars($hd_detail['trang_thai']) ?>
                        </span>
                    </p>
                </div>
                <div class="info-col" style="text-align: right;">
                    <h5>TỔNG CỘNG</h5>
                    <h2 style="color: #ffc107; margin: 0;"><?= number_format($hd_detail['tong_tien'], 0, ',', '.') ?> VND</h2>
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <h5 style="color: #fff; border-left: 4px solid #ffc107; padding-left: 10px;">VÉ ĐÃ MUA</h5>
                <?php if (!empty($listVe)): ?>
                <table class="detail-table">
                    <thead>
                        <tr>
                            <th>Mã vé</th>
                            <th>Phim</th>
                            <th>Phòng / Ghế</th>
                            <th>Thời gian</th>
                            <th>Giá vé</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($listVe as $ve): ?>
                        <tr>
                            <td>#<?= htmlspecialchars($ve['ma_ve']) ?></td>
                            <td><?= htmlspecialchars($ve['ten_phim']) ?></td>
                            <td><?= htmlspecialchars($ve['ten_phong']) ?> / <strong><?= htmlspecialchars($ve['vi_tri']) ?></strong> (<?= htmlspecialchars($ve['loai_ghe']) ?>)</td>
                            <td><?= htmlspecialchars($ve['gio_bat_dau']) ?></td>
                            <td><?= number_format($ve['gia_ve'], 0, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <p style="color: #888; font-style: italic; padding: 10px;">Không có vé.</p>
                <?php endif; ?>
            </div>

            <div>
                <h5 style="color: #fff; border-left: 4px solid #ffc107; padding-left: 10px;">COMBO ĐÃ MUA</h5>
                <?php if (!empty($listCombo)): ?>
                <table class="detail-table">
                    <thead>
                        <tr>
                            <th>Tên Combo</th>
                            <th>Số lượng</th>
                            <th>Đơn giá</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($listCombo as $combo): ?>
                        <tr>
                            <td><?= htmlspecialchars($combo['ten_combo']) ?></td>
                            <td><?= htmlspecialchars($combo['so_luong']) ?></td>
                            <td><?= number_format($combo['gia_combo'], 0, ',', '.') ?></td>
                            <td><?= number_format($combo['thanh_tien'], 0, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <p style="color: #888; font-style: italic; padding: 10px;">Không có combo.</p>
                <?php endif; ?>
            </div>

            <div class="form-actions" style="border-top: 1px solid #444; padding-top: 20px; margin-top: 20px; text-align: right;">
                <a href="index.php?controller=adminHoaDon&action=index" class="btn-cancel" style="text-decoration:none;">Quay lại danh sách</a>
            </div>
        </div>
        <?php endif; ?>

        <div class="result-count">
            <span>Hiển thị <?= !empty($listHoaDon) ? count($listHoaDon) : 0; ?> hoá đơn</span>
        </div>

        <section class="data-section">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Mã HĐ</th>
                            <th>Tên khách hàng</th>
                            <th>Ngày tạo</th>
                            <th>Tổng tiền (VND)</th>
                            <th>PT Thanh toán</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($listHoaDon)): ?>
                            <?php foreach ($listHoaDon as $hd): ?>
                                <?php 
                                    $statusClass = '';
                                    if($hd['trang_thai'] == 'Đã thanh toán') $statusClass = 'status-success';
                                    elseif($hd['trang_thai'] == 'Chưa thanh toán') $statusClass = 'status-warning';
                                    elseif($hd['trang_thai'] == 'Hủy') $statusClass = 'status-danger';
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($hd['ma_hoa_don']) ?></td>
                                    <td><strong><?= htmlspecialchars($hd['ho_ten'] ?? 'Khách vãng lai') ?></strong></td>
                                    <td><?= htmlspecialchars($hd['ngay_tao']) ?></td>
                                    <td><?= number_format($hd['tong_tien'], 0, ',', '.') ?></td>
                                    <td><?= htmlspecialchars($hd['phuong_thuc_thanh_toan']) ?></td>
                                    <td>
                                        <span class="status-badge <?= $statusClass ?>">
                                            <?= htmlspecialchars($hd['trang_thai']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="action-btn edit-btn" 
                                            onclick="location.href='index.php?controller=adminHoaDon&action=edit&id=<?= $hd['ma_hoa_don']; ?>'">
                                            Xem
                                        </button>
                                        <button class="action-btn delete-btn"
                                            onclick="if(confirm('Bạn có chắc chắn muốn xóa hoá đơn này?')) location.href='index.php?controller=adminHoaDon&action=delete&id=<?= $hd['ma_hoa_don']; ?>'">
                                            Xóa
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" style="text-align:center; padding:30px;">
                                    Không tìm thấy dữ liệu.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

    </main>

    <?php if (isset($_GET['status'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var status = '<?= $_GET["status"] ?>';
                if(status === 'success') alert('Thao tác thành công!');
                if(status === 'error') alert('Có lỗi xảy ra!');
            });
        </script>
    <?php endif; ?>
</body>
</html>