<?php
class LichChieuController {
    public function theoRap() {
        // 1️⃣ Lấy dữ liệu từ URL (nếu có)
        $rap = $_GET['rap'] ?? 'beta-thanh-xuan';
        $ngay = $_GET['ngay'] ?? '26/10';

        // 2️⃣ Giả lập dữ liệu lịch chiếu
        $lich_chieu = [
            [
                'rap' => 'beta-thanh-xuan',
                'ngay' => '26/10',
                'ten' => 'Cục Vàng Của Ngoại',
                'poster' => '/Project1/publics/img/cuc_vang_cua_ngoai.jpg',
                'tag' => 'T13',
                'the_loai' => 'Tâm lý, Gia đình',
                'thoi_luong' => 119,
                'suat_chieu' => [
                    ['gio' => '11:20', 'ghe_trong' => 136],
                    ['gio' => '14:10', 'ghe_trong' => 164],
                ]
            ],
            [
                'rap' => 'beta-thanh-xuan',
                'ngay' => '27/10',
                'ten' => 'Nhà Ma Xó',
                'poster' => '/Project1/publics/img/Nha_ma_xo.png',
                'tag' => 'T16',
                'the_loai' => 'Kinh dị, Gia đình',
                'thoi_luong' => 108,
                'suat_chieu' => [
                    ['gio' => '13:30', 'ghe_trong' => 136],
                    ['gio' => '21:00', 'ghe_trong' => 134],
                ]
            ],
            [
                'rap' => 'beta-thanh-xuan',
                'ngay' => '27/10',
                'ten' => 'Kinh Dị Nhật Vị',
                'poster' => '/Project1/publics/img/kinh_di_nhat_vi.jpg',
                'tag' => 'T16',
                'the_loai' => 'Kinh dị',
                'thoi_luong' => 80,
                'suat_chieu' => [
                    ['gio' => '10:45', 'ghe_trong' => 170],
                ]
            ],
            [
                'rap' => 'beta-thanh-xuan',
                'ngay' => '26/10',
                'ten' => 'Quỷ Ăn Tạng Phần 3',
                'poster' => '/Project1/publics/img/quy_an_tang.jpg',
                'tag' => 'T18',
                'the_loai' => 'Hành động',
                'thoi_luong' => 120,
                'suat_chieu' => [
                    ['gio' => '12:00', 'ghe_trong' => 150],
                    ['gio' => '14:30', 'ghe_trong' => 150],
                ]
            ]
        ];

        // 3️⃣ Lọc theo rạp và ngày
        $lich_chieu_loc = array_filter($lich_chieu, function($item) use ($rap, $ngay) {
            return $item['rap'] === $rap && $item['ngay'] === $ngay;
        });

        // 3️⃣ Gọi view
        include_once __DIR__ . '/../views/lichchieu/LichChieuTheoRap.php';
    }
}
