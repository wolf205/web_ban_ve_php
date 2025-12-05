<?php
// sau này bạn có thể lấy thông tin ghế + giá vé từ session/DB
// ví dụ: $ticketSubtotal = $_SESSION['ticket_subtotal'] ?? 50000;
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
    <link rel="stylesheet" href="publics/css/styles.css">
    <link rel="stylesheet" href="publics/css/container.css">
    <link rel="stylesheet" href="publics/css/card.css">
    <link rel="stylesheet" href="publics/css/button.css">

    <!-- CSS riêng cho COMBO & THANH TOÁN -->
    <link rel="stylesheet" href="publics/css/combo_thanh_toan.css">

</head>
<body>
    <section class="checkout container">
        <div class="checkout__grid">
            <!-- Cột trái: COMBO, ƯU ĐÃI, THANH TOÁN -->
            <div class="checkout__main">
                <!-- THÔNG TIN THANH TOÁN (tóm tắt nhanh: loại ghế + số lượng) -->
                <div class="card checkout__section">
                    <div class="checkout__section-title">
                        <span class="checkout__icon">👤</span> THÔNG TIN THANH TOÁN
                    </div>
                    <div class="checkout__kv">
                        <div><span class="checkout__label">Họ tên:</span> <strong>Hách Minh</strong></div>
                        <div><span class="checkout__label">Số điện thoại:</span> <strong>—</strong></div>
                        <div><span class="checkout__label">Email:</span> <strong>hachminh456@gmail.com</strong></div>
                    </div>
                    <div class="checkout__split"></div>
                <?php
                // Các biến đã được controller truyền sang (nhưng phòng khi chưa có thì fallback)
                $ticketSubtotal     = $ticketSubtotal     ?? 0;
                $selectedSeatsLabel = $selectedSeatsLabel ?? '—';
                $thongTinSuat       = $thongTinSuat       ?? null;
                ?>
               <div class="checkout__ticketline">
                    <span class="checkout__label">Ghế</span>
                    <span><?= htmlspecialchars($selectedSeatsLabel) ?> —
                        <strong><?= number_format($ticketSubtotal, 0, ',', '.') ?> đ</strong>
                    </span>
                </div>

                </div>
                            <!-- COMBO ƯU ĐÃI -->
                <div class="card checkout__section">
                    <div class="checkout__section-title">
                        <span class="checkout__icon">🧺</span> COMBO ƯU ĐÃI
                    </div>

                    <?php if (!empty($dsCombo)) : ?>
                        <?php foreach ($dsCombo as $combo) : ?>
                            <div class="checkout__combo">
                                <img class="checkout__combo-img"
                                    src="<?= htmlspecialchars($combo['anh_minh_hoa']) ?>"
                                    alt="<?= htmlspecialchars($combo['ten_combo']) ?>">

                                <div class="checkout__combo-info">
                                    <div class="checkout__combo-head">
                                        <div class="checkout__combo-name">
                                            <?= htmlspecialchars($combo['ten_combo']) ?>
                                        </div>
                                        <div class="checkout__combo-price"
                                            data-price="<?= (float)$combo['gia_tien'] ?>">
                                            <?= number_format($combo['gia_tien'], 0, ',', '.') ?> đ
                                        </div>
                                    </div>

                                    <?php if (!empty($combo['mo_ta'])) : ?>
                                        <div class="checkout__combo-desc">
                                            <?= htmlspecialchars($combo['mo_ta']) ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="checkout__qty">
                                        <button class="btn checkout__btn-qty" data-qty="-1" aria-label="Giảm">–</button>
                                        <input class="checkout__qty-input" type="text" value="0" readonly>
                                        <button class="btn checkout__btn-qty" data-qty="+1" aria-label="Tăng">+</button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p>Hiện chưa có combo nào. Hãy thêm dữ liệu vào bảng <strong>combo</strong> trong DB.</p>
                    <?php endif; ?>
                </div>

                <!-- GIẢM GIÁ -->
                <div class="card checkout__section">
                    <div class="checkout__section-title">
                        <span class="checkout__icon">🏷️</span> GIẢM GIÁ
                    </div>
                    <div class="checkout__discount-line">
                        <div class="checkout__label">Beta Voucher</div>
                        <button class="btn" type="button">Chọn voucher</button>
                    </div>
                    <div class="checkout__discount-line">
                        <div class="checkout__label">Điểm Beta</div>
                        <button class="btn" type="button">Dùng điểm</button>
                    </div>
                </div>

                <!-- PHƯƠNG THỨC THANH TOÁN -->
                <div class="card checkout__section">
                    <div class="checkout__section-title">
                        <span class="checkout__icon">💳</span> PHƯƠNG THỨC THANH TOÁN
                    </div>

                    <div class="checkout__paylist">
                        <label class="checkout__payitem">
                            <input type="radio" name="pay">
                            <span>Ví ZaloPay</span>
                        </label>
                        <label class="checkout__payitem">
                            <input type="radio" name="pay">
                            <span>Ví ShopeePay</span>
                        </label>
                        <label class="checkout__payitem">
                            <input type="radio" name="pay">
                            <span>Ví MoMo</span>
                        </label>
                    </div>

                    <div class="checkout__totalbox">
                        <div class="checkout__total-row">
                            <span>Tổng tiền:</span> <strong id="sumTotal">50.000 đ</strong>
                        </div>
                        <div class="checkout__total-row">
                            <span>Số tiền được giảm:</span> <strong id="discountTotal">0 đ</strong>
                        </div>
                        <div class="checkout__total-row checkout__total-row--final">
                            <span>Số tiền cần thanh toán:</span> <strong id="needPay">50.000 đ</strong>
                        </div>
                    </div>

                    <div class="checkout__actions">
                        <!-- ĐÃ ĐỔI sang .php -->
                        <a href="javascript:history.back()" class="btn" type="button">QUAY LẠI</a>
                        <button class="btn btn--primary" type="button" id="btnOpenPayment">TIẾP TỤC</button>
                    </div>
                </div>
            </div>

            <!-- Cột phải: THÔNG TIN PHIM -->
            <aside class="checkout__aside">
                <div class="card checkout__movie">
                    <?php
                    $selectedSeatsLabel = $_SESSION['selected_seat_names'] ?? '—';
                    ?>
                    <img class="checkout__poster" 
                        src="<?= htmlspecialchars($poster) ?>" 
                        alt="<?= htmlspecialchars($ten_phim) ?>">

                    <div class="checkout__movie-meta">
                        <h3 class="checkout__movie-title">
                            <?= htmlspecialchars($ten_phim)?>
                        </h3>

                        <ul class="checkout__movie-facts">
                            <li><strong>Thể loại:</strong> <?= htmlspecialchars($the_loai) ?></li>
                            <li><strong>Thời lượng:</strong> <?= ($thoi_luong) ?> phút</li>
                        </ul>

                        <div class="checkout__kv">
                            <div><span class="checkout__label">Rạp Chiếu</span> 
                                <?= htmlspecialchars($ten_rap) ?>
                            </div>
                            <div><span class="checkout__label">Ngày Chiếu</span> 
                                <?= htmlspecialchars($ngay_chieu) ?>
                            </div>
                            <div><span class="checkout__label">Giờ Chiếu</span> 
                                <?= htmlspecialchars($gio_chieu) ?>
                            </div>
                            <div><span class="checkout__label">Phòng Chiếu</span> 
                                <?= htmlspecialchars($phong_chieu) ?>
                            </div>
                            <div><span class="checkout__label">Ghế Ngồi</span> 
                                <?= htmlspecialchars($selectedSeatsLabel) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </section>

    <!-- POPUP QR THANH TOÁN: ĐẶT TRONG <body>, TRƯỚC </body> -->
    <div class="checkout__modal" id="paymentModal" aria-hidden="true">
        <div class="checkout__modal-dialog" role="dialog" aria-modal="true" aria-labelledby="paymentModalTitle">
            <button type="button" class="checkout__modal-close" id="btnCloseModal" aria-label="Đóng">
                &times;
            </button>

            <h2 class="checkout__modal-title" id="paymentModalTitle">
                QR THANH TOÁN
            </h2>

            <p class="checkout__modal-subtitle">
                Vui lòng quét mã QR để hoàn tất thanh toán.
            </p>

            <div class="checkout__modal-qrbox">
                <img id="qrImage" alt="Mã QR thanh toán" />
            </div>

            <div class="checkout__modal-info">
                <div>
                    <span class="checkout__label">Số tiền:</span>
                    <strong id="modalAmount">0 đ</strong>
                </div>
                <div>
                    <span class="checkout__label">Thời gian còn lại:</span>
                    <strong id="paymentCountdown">--:--</strong>
                </div>
            </div>

            <div class="checkout__modal-actions">
                <button type="button" class="btn" id="btnCancelPayment">HỦY</button>
                <button type="button" class="btn btn--primary" id="btnConfirmPayment">
                    TÔI ĐÃ THANH TOÁN
                </button>
            </div>
        </div>
    </div>

    <script>
        // ====== LOGIC TÍNH TIỀN COMBO + CỘNG VỚI TIỀN VÉ ======
        const TICKET_SUBTOTAL = <?= (int)$ticketSubtotal ?>;
        const money = n => n.toLocaleString('vi-VN') + ' đ';

        const sumEl = document.getElementById('sumTotal');
        const discEl = document.getElementById('discountTotal');
        const needEl = document.getElementById('needPay');

        let currentNeedPay = 0;

        function recalc() {
            const combos = [...document.querySelectorAll('.checkout__combo')];
            let comboSum = 0;
            combos.forEach(row => {
                const price = Number(row.querySelector('.checkout__combo-price').dataset.price || 0);
                const qty = Number(row.querySelector('.checkout__qty-input').value || 0);
                comboSum += price * qty;
            });
            const subtotal = TICKET_SUBTOTAL + comboSum;
            const discount = 0;
            const needPay = subtotal - discount;

            currentNeedPay = needPay;

            sumEl.textContent = money(subtotal);
            discEl.textContent = money(discount);
            needEl.textContent = money(needPay);
        }

        // ====== POPUP QR THANH TOÁN ======
        const btnOpenPayment = document.getElementById('btnOpenPayment');
        const modal          = document.getElementById('paymentModal');
        const btnCloseModal  = document.getElementById('btnCloseModal');
        const btnCancel      = document.getElementById('btnCancelPayment');
        const btnConfirm     = document.getElementById('btnConfirmPayment');
        const modalAmountEl  = document.getElementById('modalAmount');
        const countdownEl    = document.getElementById('paymentCountdown');
        const qrImg          = document.getElementById('qrImage');

        let countdownTimer = null;

        function openPaymentModal() {
            recalc();

            const amount = currentNeedPay || TICKET_SUBTOTAL;
            modalAmountEl.textContent = money(amount);

            const qrData = `CinemaPlus|AMOUNT=${amount}|TIME=${Date.now()}`;
            qrImg.src = 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&data='
                        + encodeURIComponent(qrData);

            let remaining = 15 * 60;
            function updateCountdown() {
                const m = String(Math.floor(remaining / 60)).padStart(2, '0');
                const s = String(remaining % 60).padStart(2, '0');
                countdownEl.textContent = `${m}:${s}`;
                if (remaining <= 0) {
                    clearInterval(countdownTimer);
                    countdownTimer = null;
                    countdownEl.textContent = 'Hết thời gian thanh toán';
                }
                remaining--;
            }
            if (countdownTimer) clearInterval(countdownTimer);
            updateCountdown();
            countdownTimer = setInterval(updateCountdown, 1000);

            modal.classList.add('is-open');   // 👉 hiện modal
        }

        function closePaymentModal() {
            modal.classList.remove('is-open'); // 👉 ẩn modal
            if (countdownTimer) {
                clearInterval(countdownTimer);
                countdownTimer = null;
            }
        }

        // GẮN SỰ KIỆN
        if (btnOpenPayment) btnOpenPayment.addEventListener('click', openPaymentModal);
        if (btnCloseModal)  btnCloseModal.addEventListener('click', closePaymentModal);
        if (btnCancel)      btnCancel.addEventListener('click', closePaymentModal);

        if (modal) {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) closePaymentModal();
            });
        }

        if (btnConfirm) {
            btnConfirm.addEventListener('click', () => {
                alert('Xử lý xác nhận thanh toán (redirect trang cảm ơn, lưu hóa đơn, v.v.)');
            });
        }

        document.querySelectorAll('.checkout__btn-qty').forEach(btn => {
            btn.addEventListener('click', () => {
                const box = btn.closest('.checkout__qty');
                const input = box.querySelector('.checkout__qty-input');
                const step = btn.dataset.qty === '+1' ? 1 : -1;
                const next = Math.max(0, Number(input.value) + step);
                input.value = next;
                recalc();
            });
        });

        recalc();
    </script>
</body>
</html>
<?php
// footer.php của bạn khả năng cao đã đóng </body></html>
require_once __DIR__ . '/../chung/footer.php';
?>
