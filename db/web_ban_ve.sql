-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 08, 2025 lúc 03:38 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `web_ban_ve`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `combo`
--

CREATE TABLE `combo` (
  `ma_combo` int(11) NOT NULL,
  `ten_combo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `mo_ta` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `anh_minh_hoa` varchar(255) DEFAULT NULL,
  `gia_tien` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danh_gia_rap`
--

CREATE TABLE `danh_gia_rap` (
  `ma_danh_gia` int(11) NOT NULL,
  `ma_rap` int(11) NOT NULL,
  `ma_kh` int(11) NOT NULL,
  `noi_dung` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ngay_danh_gia` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `danh_gia_rap`
--

INSERT INTO `danh_gia_rap` (`ma_danh_gia`, `ma_rap`, `ma_kh`, `noi_dung`, `ngay_danh_gia`) VALUES
(1, 1, 3, 'Rạp này xem thích, gần trường mình, cuối tuần hay rủ bạn qua đây.', '2025-11-07 10:30:00'),
(2, 1, 2, 'Rạp hơi nhỏ nhưng nhân viên nhiệt tình, bắp rang ngon!', '2025-11-06 14:00:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ghe`
--

CREATE TABLE `ghe` (
  `ma_ghe` int(11) NOT NULL,
  `ma_phong` int(11) NOT NULL,
  `loai_ghe` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ma_phong_ghe` varchar(10) DEFAULT NULL,
  `vi_tri` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `ghe`
--

INSERT INTO `ghe` (`ma_ghe`, `ma_phong`, `loai_ghe`, `ma_phong_ghe`, `vi_tri`) VALUES
(1, 1, 'Thường', 'A1', 'A1'),
(2, 1, 'Thường', 'A2', 'A2'),
(3, 1, 'VIP', 'B1', 'B1'),
(4, 1, 'VIP', 'B2', 'B2'),
(5, 1, 'Đôi', 'C1', 'C1'),
(6, 2, 'Thường', 'A1', 'A1'),
(7, 2, 'Thường', 'A2', 'A2'),
(8, 2, 'Thường', 'A3', 'A3'),
(9, 2, 'Thường', 'A4', 'A4');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ghe_suat_chieu`
--

CREATE TABLE `ghe_suat_chieu` (
  `ma_ghe` int(11) NOT NULL,
  `ma_suat_chieu` int(11) NOT NULL,
  `trang_thai` bit(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `ghe_suat_chieu`
--

INSERT INTO `ghe_suat_chieu` (`ma_ghe`, `ma_suat_chieu`, `trang_thai`) VALUES
(1, 1, b'0'),
(1, 2, b'1'),
(1, 5, b'0'),
(2, 1, b'0'),
(2, 2, b'1'),
(2, 5, b'1'),
(3, 1, b'0'),
(3, 2, b'0'),
(3, 5, b'0'),
(4, 1, b'0'),
(4, 2, b'0'),
(4, 5, b'0'),
(5, 1, b'0'),
(5, 2, b'0'),
(5, 5, b'0'),
(6, 3, b'0'),
(6, 4, b'1'),
(7, 3, b'0'),
(7, 4, b'1'),
(8, 3, b'0'),
(8, 4, b'0'),
(9, 3, b'0'),
(9, 4, b'0');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hoa_don`
--

CREATE TABLE `hoa_don` (
  `ma_hoa_don` int(11) NOT NULL,
  `ma_kh` int(11) NOT NULL,
  `ngay_tao` datetime DEFAULT NULL,
  `tong_tien` decimal(10,2) NOT NULL,
  `phuong_thuc_thanh_toan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `QR` varchar(100) DEFAULT NULL,
  `trang_thai` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hoa_don_combo`
--

CREATE TABLE `hoa_don_combo` (
  `ma_hoa_don` int(11) NOT NULL,
  `ma_combo` int(11) NOT NULL,
  `so_luong` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khach_hang`
--

CREATE TABLE `khach_hang` (
  `ma_kh` int(11) NOT NULL,
  `ho_ten` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `SDT` varchar(15) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `tai_khoan` varchar(50) DEFAULT NULL,
  `mat_khau` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `khach_hang`
--

INSERT INTO `khach_hang` (`ma_kh`, `ho_ten`, `email`, `SDT`, `avatar`, `tai_khoan`, `mat_khau`) VALUES
(1, 'Ngọc Anh', 'ngocanh@gmail.com', '0901234567', '../../publics/img/avata1.jpg', 'ngocanh', '123456'),
(2, 'Trần Hùng', 'tranhung@gmail.com', '0907654321', 'publics/img/avata1.jpg', 'tranhung', '123456'),
(3, 'Minh Tuấn', 'minhtuan@gmail.com', '0912345678', NULL, 'minhtuan', '123456');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phim`
--

CREATE TABLE `phim` (
  `ma_phim` int(11) NOT NULL,
  `ten_phim` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `the_loai` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `thoi_luong` int(11) NOT NULL,
  `dao_dien` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `dien_vien` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mo_ta` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `gioi_han_do_tuoi` int(11) NOT NULL DEFAULT 0,
  `ngay_khoi_chieu` date DEFAULT NULL,
  `anh_trailer` varchar(200) DEFAULT NULL,
  `ma_quan_ly_cap_nhat` int(11) DEFAULT NULL,
  `hot` bit(1) DEFAULT b'0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `phim`
--

INSERT INTO `phim` (`ma_phim`, `ten_phim`, `the_loai`, `thoi_luong`, `dao_dien`, `dien_vien`, `mo_ta`, `gioi_han_do_tuoi`, `ngay_khoi_chieu`, `anh_trailer`, `ma_quan_ly_cap_nhat`, `hot`) VALUES
(1, 'Cục Vàng Của Ngoại', 'Tâm lý, Gia đình', 119, NULL, NULL, NULL, 13, '2025-10-25', 'publics/img/cuc_vang_cua_ngoai.jpg', NULL, b'1'),
(2, 'Nhà Ma Xó', 'Kinh dị', 108, NULL, NULL, NULL, 16, '2025-10-25', 'publics/img/Nha_ma_xo.png', NULL, b'1'),
(3, 'Kinh Dị Nhật Vị', 'Kinh dị', 80, NULL, NULL, NULL, 16, '2025-10-25', 'publics/img/kinh_di_nhat_vi.jpg', NULL, b'1'),
(4, 'Bịt Mắt Bắt Nai', 'Tâm lý, Gia đình', 119, NULL, NULL, NULL, 13, '2025-10-26', 'publics/img/bit_mat_bat_nai.png', NULL, b'1');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phong`
--

CREATE TABLE `phong` (
  `ma_phong` int(11) NOT NULL,
  `ma_rap` int(11) NOT NULL,
  `ten_phong` varchar(50) NOT NULL,
  `so_luong_ghe` int(11) NOT NULL,
  `loai_man_hinh` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `phong`
--

INSERT INTO `phong` (`ma_phong`, `ma_rap`, `ten_phong`, `so_luong_ghe`, `loai_man_hinh`) VALUES
(1, 1, 'Phòng 1', 5, 'IMAX'),
(2, 1, 'Phòng 2', 4, '2D');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `quan_ly`
--

CREATE TABLE `quan_ly` (
  `ma_quan_ly` int(11) NOT NULL,
  `ten` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `SDT` varchar(15) DEFAULT NULL,
  `tai_khoan` varchar(50) DEFAULT NULL,
  `mat_khau` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `rap`
--

CREATE TABLE `rap` (
  `ma_rap` int(11) NOT NULL,
  `ten_rap` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `dia_chi` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `thanh_pho` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `SDT` varchar(15) DEFAULT NULL,
  `anh_rap` varchar(255) DEFAULT NULL,
  `mo_ta_rap` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `rap`
--

INSERT INTO `rap` (`ma_rap`, `ten_rap`, `dia_chi`, `thanh_pho`, `SDT`, `anh_rap`, `mo_ta_rap`) VALUES
(1, 'Galaxy Nguyễn Du', '116 Nguyễn Du, P. Bến Thành, Q.1', 'Hồ Chí Minh', '02838227899', 'publics/img/beta_thai_nguyen.jpeg', 'Rạp có vị trí thuận lợi, rất gần những trường đại học, cao đẳng và cấp 3 lớn tại Hà Nội (Trường Đại học Khoa học Tự nhiên, Trường Đại học Khoa học Xã hội và Nhân văn, Trường Hà Nội – Amsterdam...).\r\n\r\nBeta Cinemas Thanh Xuân sở hữu hệ thống tổng cộng 6 phòng chiếu tương đương 838 ghế ngồi...'),
(2, 'Beta Thái Nguyên', 'ô 1000 m2, Tầng 1, Tòa nhà Hoàng Gia Plaza, số 259 đường Quang Trung, Phường Phan Đình Phùng, Tỉnh Thái Nguyên.', 'Thái Nguyên', '0867 460 053', 'publics/img/beta_thai_nguyen.jepg', 'Beta Cinemas Thái Nguyên có vị trí trung tâm, tọa lạc tại Hoàng Gia Plaza. Rạp tự hào là rạp phim tư nhân duy nhất và đầu tiên sở hữu hệ thống phòng chiếu phim đạt chuẩn Hollywood tại TP. Thái Nguyên.\r\n\r\nRạp được trang bị hệ thống máy chiếu, phòng chiếu hiện đại với 100% nhập khẩu từ nước ngoài, với 4 phòng chiếu tương được 535 ghế ngồi. Hệ thống âm thanh Dolby 7.1 và hệ thống cách âm chuẩn quốc tế đảm bảo chất lượng âm thanh sống động nhất cho từng thước phim bom tấn.\r\n\r\nMức giá xem phim tại Be');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `suat_chieu`
--

CREATE TABLE `suat_chieu` (
  `ma_suat_chieu` int(11) NOT NULL,
  `ma_phim` int(11) NOT NULL,
  `ma_phong` int(11) NOT NULL,
  `ma_quan_ly` int(11) DEFAULT NULL,
  `ngay_chieu` date NOT NULL,
  `gio_bat_dau` time NOT NULL,
  `gio_ket_thuc` time NOT NULL,
  `gia_ve_co_ban` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `suat_chieu`
--

INSERT INTO `suat_chieu` (`ma_suat_chieu`, `ma_phim`, `ma_phong`, `ma_quan_ly`, `ngay_chieu`, `gio_bat_dau`, `gio_ket_thuc`, `gia_ve_co_ban`) VALUES
(1, 1, 1, NULL, '2025-10-25', '09:00:00', '11:00:00', 80000.00),
(2, 1, 1, NULL, '2025-10-25', '11:30:00', '13:30:00', 80000.00),
(3, 2, 2, NULL, '2025-10-25', '10:00:00', '12:00:00', 75000.00),
(4, 3, 2, NULL, '2025-10-25', '14:00:00', '15:30:00', 75000.00),
(5, 1, 1, NULL, '2025-10-26', '14:00:00', '16:00:00', 90000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ve`
--

CREATE TABLE `ve` (
  `ma_ve` int(11) NOT NULL,
  `ma_hoa_don` int(11) NOT NULL,
  `ma_suat_chieu` int(11) NOT NULL,
  `ma_ghe` int(11) NOT NULL,
  `gia` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `combo`
--
ALTER TABLE `combo`
  ADD PRIMARY KEY (`ma_combo`);

--
-- Chỉ mục cho bảng `danh_gia_rap`
--
ALTER TABLE `danh_gia_rap`
  ADD PRIMARY KEY (`ma_danh_gia`);

--
-- Chỉ mục cho bảng `ghe`
--
ALTER TABLE `ghe`
  ADD PRIMARY KEY (`ma_ghe`);

--
-- Chỉ mục cho bảng `ghe_suat_chieu`
--
ALTER TABLE `ghe_suat_chieu`
  ADD PRIMARY KEY (`ma_ghe`,`ma_suat_chieu`);

--
-- Chỉ mục cho bảng `hoa_don`
--
ALTER TABLE `hoa_don`
  ADD PRIMARY KEY (`ma_hoa_don`);

--
-- Chỉ mục cho bảng `hoa_don_combo`
--
ALTER TABLE `hoa_don_combo`
  ADD PRIMARY KEY (`ma_hoa_don`,`ma_combo`);

--
-- Chỉ mục cho bảng `khach_hang`
--
ALTER TABLE `khach_hang`
  ADD PRIMARY KEY (`ma_kh`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `tai_khoan` (`tai_khoan`);

--
-- Chỉ mục cho bảng `phim`
--
ALTER TABLE `phim`
  ADD PRIMARY KEY (`ma_phim`);

--
-- Chỉ mục cho bảng `phong`
--
ALTER TABLE `phong`
  ADD PRIMARY KEY (`ma_phong`);

--
-- Chỉ mục cho bảng `quan_ly`
--
ALTER TABLE `quan_ly`
  ADD PRIMARY KEY (`ma_quan_ly`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `tai_khoan` (`tai_khoan`);

--
-- Chỉ mục cho bảng `rap`
--
ALTER TABLE `rap`
  ADD PRIMARY KEY (`ma_rap`);

--
-- Chỉ mục cho bảng `suat_chieu`
--
ALTER TABLE `suat_chieu`
  ADD PRIMARY KEY (`ma_suat_chieu`);

--
-- Chỉ mục cho bảng `ve`
--
ALTER TABLE `ve`
  ADD PRIMARY KEY (`ma_ve`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `combo`
--
ALTER TABLE `combo`
  MODIFY `ma_combo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `danh_gia_rap`
--
ALTER TABLE `danh_gia_rap`
  MODIFY `ma_danh_gia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `ghe`
--
ALTER TABLE `ghe`
  MODIFY `ma_ghe` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `hoa_don`
--
ALTER TABLE `hoa_don`
  MODIFY `ma_hoa_don` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `khach_hang`
--
ALTER TABLE `khach_hang`
  MODIFY `ma_kh` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `phim`
--
ALTER TABLE `phim`
  MODIFY `ma_phim` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `phong`
--
ALTER TABLE `phong`
  MODIFY `ma_phong` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `quan_ly`
--
ALTER TABLE `quan_ly`
  MODIFY `ma_quan_ly` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `rap`
--
ALTER TABLE `rap`
  MODIFY `ma_rap` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `suat_chieu`
--
ALTER TABLE `suat_chieu`
  MODIFY `ma_suat_chieu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `ve`
--
ALTER TABLE `ve`
  MODIFY `ma_ve` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
