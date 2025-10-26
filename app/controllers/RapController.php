<?php
// app/controllers/RapController.php

class RapController {
    public function index() {
        // ==============================
        // DỮ LIỆU MẪU (khi chưa có model)
        // ==============================
        $rap = [
            'ten_rap' => 'Beta Thanh Xuân, TP Hà Nội',
            'hinh_anh' => '/Project1/publics/img/beta_thai_nguyen.jpeg',
            'mo_ta' => [
                'Rạp Beta Cinemas Thanh Xuân tọa lạc tại Tầng hầm B1, tòa nhà Golden West, Số 2, Lê Văn Thiêm, Phường Thanh Xuân, Hà Nội.',
                'Rạp có vị trí thuận lợi, rất gần những trường đại học, cao đẳng và cấp 3 lớn tại Hà Nội (Trường Đại học Khoa học Tự nhiên, Trường Đại học Khoa học Xã hội và Nhân văn, Trường Hà Nội – Amsterdam...).',
                'Beta Cinemas Thanh Xuân sở hữu hệ thống tổng cộng 6 phòng chiếu tương đương 838 ghế ngồi v.v'
            ]
        ];

        $binhluan = [
            [
                'ten' => 'Ngọc Anh',
                'anh_dai_dien' => '/Project1/publics/img/avata1.jpg',
                'noi_dung' => 'Rạp này xem thích, gần trường mình, cuối tuần hay rủ bạn qua đây.',
                'thoi_gian' => '1 ngày trước'
            ],
            [
                'ten' => 'Trần Hùng',
                'anh_dai_dien' => '', // chưa có ảnh
                'noi_dung' => 'Rạp hơi nhỏ nhưng nhân viên nhiệt tình, bắp rang ngon!',
                'thoi_gian' => '2 ngày trước'
            ]
        ];

        $phim_hot = [
            ['ten' => 'Bịt Mắt Bắt Nai', 'tag' => 'T18', 'poster' => '/Project1/publics/img/bit_mat_bat_nai.png'],
            ['ten' => 'Nhà Ma Xó', 'tag' => 'T16', 'poster' => '/Project1/publics/img/Nha_ma_xo.png'],
            ['ten' => 'Kinh Dị Nhật Vị', 'tag' => 'T16', 'poster' => '/Project1/publics/img/kinh_di_nhat_vi.jpg'],
            ['ten' => 'Cục Vàng Của Ngoại', 'tag' => 'T13', 'poster' => '/Project1/publics/img/cuc_vang_cua_ngoai.jpg']
        ];

        // ==============================
        // TRUYỀN DỮ LIỆU SANG VIEW
        // ==============================
        include_once __DIR__ . '/../views/rap/Rap.php';
    }
}
