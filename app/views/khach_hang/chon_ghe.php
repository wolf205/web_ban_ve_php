<?php
// sau này bạn có thể include config, session, lấy dữ liệu từ DB ở đây
// ví dụ:
// session_start();
// include '../../config.php';
require_once __DIR__ . '/../chung/header.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cinema Plus - Đặt Vé Xem Phim Online</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <link rel="stylesheet" href="publics/css/reset.css">
    <link rel="stylesheet" href="publics/css/variables.css">
    <link rel="stylesheet" href="publics/css/button.css">
    <link rel="stylesheet" href="publics/css/card.css">
    <link rel="stylesheet" href="publics/css/container.css">
    <link rel="stylesheet" href="publics/css/styles.css">

    <!-- CSS riêng cho chức năng đặt vé -->
    <link rel="stylesheet" href="publics/css/chon_ghe.css">
<section class="booking container">
    <div class="booking__header card">
        <div class="booking__poster">
            <img src="<?= htmlspecialchars($poster) ?>" alt="<?= htmlspecialchars($ten_phim) ?>" />
        </div>

        <div class="booking__meta">
            <h2 class="booking__title"><?= htmlspecialchars($ten_phim) ?></h2>
            <ul class="booking__facts">
                <li><strong>Thể loại:</strong> <?= htmlspecialchars($the_loai) ?></li>
                <li><strong>Thời lượng:</strong> <?= $thoi_luong ?> phút</li>
                <li><strong>Phân loại:</strong> <?= htmlspecialchars($phan_loai) ?></li>
            </ul>

            <div class="booking__info-grid">
                <div class="booking__info">
                    <span class="booking__label">Rạp chiếu</span>
                    <span class="booking__value"><?= htmlspecialchars($ten_rap) ?></span>
                </div>
                <div class="booking__info">
                    <span class="booking__label">Ngày chiếu</span>
                    <span class="booking__value"><?= htmlspecialchars($ngay_chieu) ?></span>
                </div>
                <div class="booking__info">
                    <span class="booking__label">Giờ chiếu</span>
                    <span class="booking__value"><?= htmlspecialchars($gio_chieu) ?></span>
                </div>
                <div class="booking__info">
                    <span class="booking__label">Phòng chiếu</span>
                    <span class="booking__value"><?= htmlspecialchars($phong_chieu) ?></span>
                </div>
            </div>

            <div class="booking__legend">
                <div class="booking__legend-item">
                    <span class="booking__seat--sample booking__seat--regular"></span> Ghế thường
                </div>
                <div class="booking__legend-item">
                    <span class="booking__seat--sample booking__seat--vip"></span> Ghế VIP
                </div>
                <div class="booking__legend-item">
                    <span class="booking__seat--sample booking__seat--couple"></span> Ghế đôi
                </div>
                <div class="booking__legend-item">
                    <span class="booking__seat--sample booking__seat--unavailable"></span> Đã chọn/đã bán
                </div>
            </div>
        </div>
    </div>

    <div class="booking__stage card">
        <div class="booking__screen">Màn hình chiếu</div>

        <div class="booking__map" role="grid" aria-label="Sơ đồ ghế">
            <?php if (!empty($rows)) : ?>
                <?php foreach ($rows as $rowLabel => $listSeats) : ?>
                    <div class="booking__row" role="row" aria-label="Hàng <?= htmlspecialchars($rowLabel) ?>">
                        <span class="booking__row-label"><?= htmlspecialchars($rowLabel) ?></span>

                        <?php foreach ($listSeats as $seat) : ?>
                            <?php
                            $seatName = $seat['seat_name'];
                            $loai     = strtolower(trim($seat['loai_ghe'] ?? ''));

                            $typeClass = 'booking__seat--regular';
                            if ($loai === 'vip') {
                                $typeClass = 'booking__seat--vip';
                            } elseif ($loai === 'đôi' || $loai === 'doi' || $loai === 'ghe doi') {
                                $typeClass = 'booking__seat--couple';
                            }

                            $isUnavailable = isset($seat['trang_thai']) && (int)$seat['trang_thai'] === 1;
                            ?>
                            <button
                                class="booking__seat <?= $typeClass ?><?= $isUnavailable ? ' booking__seat--unavailable' : '' ?>"
                                data-seat="<?= htmlspecialchars($seatName) ?>"
                                data-id="<?= (int)$seat['ma_ghe'] ?>"
                                <?php if ($isUnavailable) : ?>disabled<?php endif; ?>
                            >
                                <span class="booking__seat-label"><?= htmlspecialchars($seatName) ?></span>
                            </button>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p>Không có dữ liệu ghế cho suất chiếu này.</p>
            <?php endif; ?>
        </div>

        <div class="booking__footer">
            <div class="booking__bill">
                <div class="booking__bill-item">
                    <span>Ghế đã chọn:</span> <strong id="selectedSeats">—</strong>
                </div>
                <div class="booking__bill-item">
                    <span>Tổng tiền:</span> <strong id="totalPrice">0 đ</strong>
                </div>
            </div>

            <div class="booking__actions">
                <a href="javascript:history.back()" class="btn" type="button">QUAY LẠI</a>

                <form id="goComboForm"
                    method="POST"
                    action="index.php?controller=chonghe&ma_suat_chieu=<?= (int)$thongTinSuat['ma_suat_chieu'] ?>">
                    <input type="hidden" name="seat_ids"   id="seatIdsInput">
                    <input type="hidden" name="seat_names" id="seatNamesInput">
                    <button type="submit" class="btn btn--primary">TIẾP TỤC</button>
                </form>
            </div>
        </div>
    </div>
</section>

<script>

    // Giá vé cơ bản từ DB
    const basePrice = <?= (int)$giaVeCoBan ?>;

    const price = {
        regular: basePrice,
        vip: Math.round(basePrice * 1.2),
        couple: basePrice * 2
    };

    const seats = document.querySelectorAll('.booking__seat:not([disabled])');
    const selectedSeatsEl = document.getElementById('selectedSeats');
    const totalPriceEl = document.getElementById('totalPrice');

    function seatType(el) {
        if (el.classList.contains('booking__seat--couple')) return 'couple';
        if (el.classList.contains('booking__seat--vip')) return 'vip';
        return 'regular';
    }
    const goComboForm   = document.getElementById('goComboForm');
    const seatIdsInput  = document.getElementById('seatIdsInput');
    const seatNamesInput= document.getElementById('seatNamesInput');

    if (goComboForm) {
        goComboForm.addEventListener('submit', function () {
            const selected = [...document.querySelectorAll('.booking__seat--selected')];
            const ids   = selected.map(s => s.dataset.id);
            const names = selected.map(s => s.dataset.seat);

            seatIdsInput.value   = JSON.stringify(ids);      // mảng ID ghế
            seatNamesInput.value = names.join(', ');         // chuỗi tên ghế để hiển thị
        });
    }
    function updateBill() {
        const selected = [...document.querySelectorAll('.booking__seat--selected')];
        const names = selected.map(s => s.dataset.seat).join(', ') || '—';
        const total = selected.reduce((sum, s) => sum + price[seatType(s)], 0);
        selectedSeatsEl.textContent = names;
        totalPriceEl.textContent = total.toLocaleString('vi-VN') + ' đ';
    }

    seats.forEach(btn => {
        btn.addEventListener('click', () => {
            btn.classList.toggle('booking__seat--selected');
            updateBill();
        });
    });

    updateBill();
</script>

<?php
// Footer chung (đóng </body>, </html>)
require_once __DIR__ . '/../chung/footer.php';
?>