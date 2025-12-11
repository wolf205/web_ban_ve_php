-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 05, 2025 lúc 10:07 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `new`
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

--
-- Đang đổ dữ liệu cho bảng `combo`
--

INSERT INTO `combo` (`ma_combo`, `ten_combo`, `mo_ta`, `anh_minh_hoa`, `gia_tien`) VALUES
(1, 'Combo Gia Đình', '2 bắp lớn + 4 nước lớn', 'publics/img/combo_gia_dinh.jpg', 120000.00),
(2, 'Combo Đôi', '1 bắp vừa + 2 nước vừa', 'publics/img/combo_doi.jpg', 65000.00),
(3, 'Combo Cá Nhân', '1 bắp nhỏ + 1 nước nhỏ', 'publics/img/combo_ca_nhan.jpg', 35000.00);

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
(1, 1, 1, 'Rạp rất đẹp, ghế ngồi thoải mái, âm thanh sống động!', '2025-11-19 12:00:00'),
(2, 1, 2, 'Dịch vụ tốt, nhân viên nhiệt tình, sẽ quay lại!', '2025-11-19 13:30:00'),
(3, 2, 3, 'Rạp sạch sẽ, giá cả hợp lý, phù hợp cho gia đình', '2025-11-19 16:45:00'),
(4, 2, 4, 'Rạp mới và sạch sẽ, nhân viên thân thiện', '2025-11-20 11:20:00'),
(5, 3, 5, 'Không gian rộng rãi, phù hợp cho gia đình có trẻ nhỏ', '2025-11-20 17:30:00'),
(6, 1, 6, 'Âm thanh hay nhưng giá vé hơi cao', '2025-11-21 14:15:00'),
(7, 2, 7, 'Đồ ăn ngon, combo giá hợp lý', '2025-11-21 19:45:00'),
(8, 3, 8, 'Vị trí thuận tiện, dễ tìm chỗ đậu xe', '2025-11-22 10:30:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ghe`
--

CREATE TABLE `ghe` (
  `ma_ghe` int(11) NOT NULL,
  `ma_phong` int(11) NOT NULL,
  `loai_ghe` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tinh_trang` varchar(50) DEFAULT 'hoạt động',
  `vi_tri` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `ghe`
--

INSERT INTO `ghe` (`ma_ghe`, `ma_phong`, `loai_ghe`, `tinh_trang`, `vi_tri`) VALUES
(1, 1, 'VIP', 'hoạt động', 'A1'),
(2, 1, 'VIP', 'hoạt động', 'A2'),
(3, 1, 'Thường', 'hoạt động', 'A3'),
(4, 1, 'Thường', 'hoạt động', 'A4'),
(5, 1, 'Thường', 'hoạt động', 'B1'),
(6, 1, 'Thường', 'hoạt động', 'B2'),
(7, 1, 'Thường', 'hoạt động', 'B3'),
(8, 1, 'Thường', 'hoạt động', 'B4'),
(9, 1, 'Đôi', 'hoạt động', 'C1'),
(10, 1, 'Đôi', 'hoạt động', 'C2'),
(11, 2, 'Thường', 'hoạt động', 'A1'),
(12, 2, 'Thường', 'hoạt động', 'A2'),
(13, 2, 'Thường', 'hoạt động', 'A3'),
(14, 2, 'Thường', 'hoạt động', 'A4'),
(15, 2, 'VIP', 'hoạt động', 'B1'),
(16, 2, 'VIP', 'hoạt động', 'B2'),
(17, 4, 'VIP', 'hoạt động', 'A1'),
(18, 4, 'VIP', 'hoạt động', 'A2'),
(19, 4, 'Đôi', 'hoạt động', 'B1'),
(20, 4, 'Đôi', 'hoạt động', 'B2'),
(21, 6, 'Thường', 'Hoạt động', 'A1'),
(22, 3, 'Thường', 'hoạt động', 'A1'),
(23, 3, 'Thường', 'hoạt động', 'A2'),
(24, 3, 'VIP', 'hoạt động', 'B1'),
(25, 3, 'VIP', 'hoạt động', 'B2'),
(26, 5, 'Thường', 'hoạt động', 'A1'),
(27, 5, 'Thường', 'hoạt động', 'A2'),
(28, 5, 'Đôi', 'hoạt động', 'B1'),
(29, 5, 'Đôi', 'hoạt động', 'B2'),
(30, 6, 'Thường', 'hoạt động', 'A2'),
(31, 6, 'Thường', 'hoạt động', 'A3'),
(32, 6, 'Thường', 'hoạt động', 'A4'),
(33, 6, 'VIP', 'hoạt động', 'B1'),
(34, 6, 'VIP', 'hoạt động', 'B2'),
(35, 7, 'VIP', 'hoạt động', 'A1'),
(36, 7, 'VIP', 'hoạt động', 'A2'),
(37, 7, 'Đôi', 'hoạt động', 'B1'),
(38, 7, 'Đôi', 'hoạt động', 'B2'),
(39, 7, 'Thường', 'bảo trì', 'C1'),
(40, 7, 'Thường', 'hoạt động', 'C2');

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
(1, 1, b'1'),
(1, 13, b'0'),
(2, 1, b'1'),
(2, 13, b'0'),
(3, 2, b'1'),
(3, 13, b'0'),
(4, 2, b'1'),
(4, 13, b'0'),
(5, 2, b'1'),
(5, 13, b'0'),
(6, 2, b'1'),
(6, 13, b'0'),
(7, 1, b'0'),
(7, 2, b'0'),
(7, 13, b'0'),
(8, 1, b'0'),
(8, 2, b'0'),
(8, 13, b'0'),
(9, 1, b'0'),
(9, 2, b'0'),
(9, 13, b'0'),
(10, 1, b'0'),
(10, 2, b'0'),
(10, 13, b'0'),
(11, 3, b'1'),
(11, 12, b'0'),
(12, 3, b'0'),
(12, 12, b'0'),
(13, 3, b'0'),
(13, 12, b'0'),
(14, 3, b'0'),
(14, 12, b'0'),
(15, 4, b'0'),
(15, 12, b'0'),
(16, 3, b'0'),
(16, 12, b'0'),
(17, 4, b'1'),
(18, 4, b'0'),
(19, 4, b'0'),
(20, 4, b'0'),
(21, 5, b'0'),
(21, 10, b'0'),
(21, 11, b'1'),
(22, 6, b'1'),
(23, 6, b'1'),
(24, 6, b'0'),
(25, 6, b'0'),
(26, 7, b'1'),
(27, 7, b'1'),
(28, 7, b'1'),
(29, 7, b'0'),
(30, 8, b'0'),
(30, 10, b'0'),
(30, 11, b'1'),
(31, 8, b'1'),
(31, 10, b'0'),
(31, 11, b'0'),
(32, 8, b'1'),
(32, 10, b'0'),
(32, 11, b'0'),
(33, 8, b'0'),
(33, 10, b'0'),
(33, 11, b'0'),
(34, 8, b'0'),
(34, 10, b'0'),
(34, 11, b'0'),
(35, 9, b'0'),
(36, 9, b'0'),
(37, 9, b'0'),
(38, 9, b'0');

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

--
-- Đang đổ dữ liệu cho bảng `hoa_don`
--

INSERT INTO `hoa_don` (`ma_hoa_don`, `ma_kh`, `ngay_tao`, `tong_tien`, `phuong_thuc_thanh_toan`, `QR`, `trang_thai`) VALUES
(1, 1, '2025-11-19 10:00:00', 235000.00, 'Thẻ tín dụng', 'QR001', 'Đã thanh toán'),
(2, 2, '2025-11-19 11:30:00', 160000.00, 'Ví điện tử', 'QR002', 'Đã thanh toán'),
(3, 3, '2025-11-19 15:20:00', 75000.00, 'Tiền mặt', 'QR003', 'Đã thanh toán'),
(4, 4, '2025-11-20 09:15:00', 180000.00, 'Ví điện tử', 'QR004', 'Đã thanh toán'),
(5, 5, '2025-11-20 14:45:00', 245000.00, 'Thẻ tín dụng', 'QR005', 'Đã thanh toán'),
(6, 6, '2025-11-20 19:30:00', 155000.00, 'Tiền mặt', 'QR006', 'Đã thanh toán'),
(7, 7, '2025-11-21 10:20:00', 295000.00, 'Ví điện tử', 'QR007', 'Đã thanh toán'),
(8, 8, '2025-11-21 16:10:00', 120000.00, 'Thẻ ghi nợ', 'QR008', 'Chưa thanh toán'),
(10, 1, '2025-11-15 10:30:00', 185000.00, 'Thẻ tín dụng', 'QR010', 'Đã thanh toán'),
(11, 2, '2025-11-15 14:20:00', 225000.00, 'Ví điện tử', 'QR011', 'Đã thanh toán'),
(12, 3, '2025-11-15 19:45:00', 140000.00, 'Tiền mặt', 'QR012', 'Đã thanh toán'),
(13, 4, '2025-11-16 11:15:00', 320000.00, 'Thẻ tín dụng', 'QR013', 'Đã thanh toán'),
(14, 5, '2025-11-16 15:40:00', 175000.00, 'Ví điện tử', 'QR014', 'Đã thanh toán'),
(15, 6, '2025-11-16 20:10:00', 95000.00, 'Tiền mặt', 'QR015', 'Đã thanh toán'),
(16, 7, '2025-11-17 09:45:00', 265000.00, 'Thẻ tín dụng', 'QR016', 'Đã thanh toán'),
(17, 8, '2025-11-17 13:30:00', 180000.00, 'Ví điện tử', 'QR017', 'Đã thanh toán'),
(18, 9, '2025-11-17 18:20:00', 210000.00, 'Tiền mặt', 'QR018', 'Đã thanh toán'),
(19, 10, '2025-11-17 21:15:00', 125000.00, 'Thẻ ghi nợ', 'QR019', 'Đã thanh toán'),
(20, 1, '2025-11-18 10:00:00', 195000.00, 'Thẻ tín dụng', 'QR020', 'Đã thanh toán'),
(21, 3, '2025-11-18 14:45:00', 285000.00, 'Ví điện tử', 'QR021', 'Đã thanh toán'),
(22, 5, '2025-11-18 19:30:00', 165000.00, 'Tiền mặt', 'QR022', 'Đã thanh toán'),
(23, 2, '2025-11-19 12:15:00', 310000.00, 'Thẻ tín dụng', 'QR023', 'Đã thanh toán'),
(24, 4, '2025-11-19 16:30:00', 190000.00, 'Ví điện tử', 'QR024', 'Đã thanh toán'),
(25, 6, '2025-11-19 20:45:00', 135000.00, 'Tiền mặt', 'QR025', 'Đã thanh toán'),
(26, 7, '2025-11-20 11:30:00', 275000.00, 'Thẻ tín dụng', 'QR026', 'Đã thanh toán'),
(27, 9, '2025-11-20 15:20:00', 220000.00, 'Ví điện tử', 'QR027', 'Đã thanh toán'),
(28, 10, '2025-11-20 18:40:00', 185000.00, 'Tiền mặt', 'QR028', 'Đã thanh toán'),
(29, 1, '2025-11-21 09:20:00', 240000.00, 'Thẻ tín dụng', 'QR029', 'Đã thanh toán'),
(30, 3, '2025-11-21 11:45:00', 195000.00, 'Ví điện tử', 'QR030', 'Đã thanh toán'),
(31, 5, '2025-11-21 14:30:00', 165000.00, 'Tiền mặt', 'QR031', 'Đã thanh toán'),
(32, 1, '2025-11-29 10:30:00', 320000.00, 'Thẻ tín dụng', 'QR027', 'Đã thanh toán'),
(33, 3, '2025-11-29 14:20:00', 285000.00, 'Ví điện tử', 'QR028', 'Đã thanh toán'),
(34, 5, '2025-11-29 19:45:00', 410000.00, 'Tiền mặt', 'QR029', 'Đã thanh toán'),
(35, 7, '2025-11-29 21:15:00', 195000.00, 'Thẻ ghi nợ', 'QR030', 'Đã thanh toán'),
(36, 2, '2025-11-30 11:15:00', 375000.00, 'Thẻ tín dụng', 'QR031', 'Đã thanh toán'),
(37, 4, '2025-11-30 15:40:00', 220000.00, 'Ví điện tử', 'QR032', 'Đã thanh toán'),
(38, 6, '2025-11-30 20:10:00', 295000.00, 'Tiền mặt', 'QR033', 'Đã thanh toán'),
(39, 8, '2025-12-01 09:45:00', 185000.00, 'Thẻ tín dụng', 'QR034', 'Đã thanh toán'),
(40, 10, '2025-12-01 13:30:00', 145000.00, 'Ví điện tử', 'QR035', 'Đã thanh toán'),
(41, 9, '2025-12-01 18:20:00', 230000.00, 'Tiền mặt', 'QR036', 'Đã thanh toán'),
(42, 1, '2025-12-02 10:00:00', 265000.00, 'Thẻ tín dụng', 'QR037', 'Đã thanh toán'),
(43, 3, '2025-12-02 14:45:00', 195000.00, 'Ví điện tử', 'QR038', 'Đã thanh toán'),
(44, 5, '2025-12-02 19:30:00', 315000.00, 'Tiền mặt', 'QR039', 'Đã thanh toán'),
(45, 2, '2025-12-03 12:15:00', 280000.00, 'Thẻ tín dụng', 'QR040', 'Đã thanh toán'),
(46, 4, '2025-12-03 16:30:00', 190000.00, 'Ví điện tử', 'QR041', 'Đã thanh toán'),
(47, 6, '2025-12-03 20:45:00', 175000.00, 'Tiền mặt', 'QR042', 'Đã thanh toán'),
(48, 7, '2025-12-04 11:30:00', 335000.00, 'Thẻ tín dụng', 'QR043', 'Đã thanh toán'),
(49, 9, '2025-12-04 15:20:00', 240000.00, 'Ví điện tử', 'QR044', 'Đã thanh toán'),
(50, 10, '2025-12-04 18:40:00', 195000.00, 'Tiền mặt', 'QR045', 'Đã thanh toán'),
(51, 1, '2025-12-05 09:20:00', 185000.00, 'Thẻ tín dụng', 'QR046', 'Đã thanh toán'),
(52, 3, '2025-12-05 11:45:00', 220000.00, 'Ví điện tử', 'QR047', 'Đã thanh toán'),
(53, 2, '2025-11-28 10:00:00', 185000.00, 'Thẻ tín dụng', 'QR053', 'Đã thanh toán'),
(54, 4, '2025-11-28 14:30:00', 220000.00, 'Ví điện tử', 'QR054', 'Đã thanh toán'),
(55, 6, '2025-11-28 19:45:00', 165000.00, 'Tiền mặt', 'QR055', 'Đã thanh toán'),
(56, 8, '2025-11-29 11:15:00', 195000.00, 'Thẻ ghi nợ', 'QR056', 'Đã thanh toán'),
(57, 10, '2025-11-29 16:40:00', 145000.00, 'Ví điện tử', 'QR057', 'Đã thanh toán'),
(58, 3, '2025-11-29 20:20:00', 285000.00, 'Tiền mặt', 'QR058', 'Đã thanh toán'),
(59, 5, '2025-11-30 09:30:00', 175000.00, 'Thẻ tín dụng', 'QR059', 'Đã thanh toán'),
(60, 7, '2025-11-30 13:45:00', 225000.00, 'Ví điện tử', 'QR060', 'Đã thanh toán'),
(61, 9, '2025-11-30 18:20:00', 195000.00, 'Tiền mặt', 'QR061', 'Đã thanh toán'),
(62, 1, '2025-12-01 10:15:00', 245000.00, 'Thẻ tín dụng', 'QR062', 'Đã thanh toán'),
(63, 3, '2025-12-01 15:30:00', 185000.00, 'Ví điện tử', 'QR063', 'Đã thanh toán'),
(64, 5, '2025-12-01 20:45:00', 165000.00, 'Tiền mặt', 'QR064', 'Đã thanh toán'),
(65, 2, '2025-12-02 11:20:00', 210000.00, 'Thẻ tín dụng', 'QR065', 'Đã thanh toán'),
(66, 4, '2025-12-02 16:40:00', 190000.00, 'Ví điện tử', 'QR066', 'Đã thanh toán'),
(67, 6, '2025-12-02 21:15:00', 135000.00, 'Tiền mặt', 'QR067', 'Đã thanh toán'),
(68, 7, '2025-12-03 09:45:00', 265000.00, 'Thẻ tín dụng', 'QR068', 'Đã thanh toán'),
(69, 9, '2025-12-03 14:20:00', 220000.00, 'Ví điện tử', 'QR069', 'Đã thanh toán'),
(70, 10, '2025-12-03 19:40:00', 185000.00, 'Tiền mặt', 'QR070', 'Đã thanh toán'),
(71, 1, '2025-12-04 08:30:00', 195000.00, 'Thẻ tín dụng', 'QR071', 'Đã thanh toán'),
(72, 3, '2025-12-04 12:45:00', 240000.00, 'Ví điện tử', 'QR072', 'Đã thanh toán'),
(73, 5, '2025-12-04 17:30:00', 175000.00, 'Tiền mặt', 'QR073', 'Đã thanh toán'),
(74, 2, '2025-12-05 10:20:00', 210000.00, 'Thẻ tín dụng', 'QR074', 'Đã thanh toán'),
(75, 4, '2025-12-05 15:45:00', 190000.00, 'Ví điện tử', 'QR075', 'Đã thanh toán');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hoa_don_combo`
--

CREATE TABLE `hoa_don_combo` (
  `ma_hoa_don` int(11) NOT NULL,
  `ma_combo` int(11) NOT NULL,
  `so_luong` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `hoa_don_combo`
--

INSERT INTO `hoa_don_combo` (`ma_hoa_don`, `ma_combo`, `so_luong`) VALUES
(1, 2, 1),
(2, 3, 2),
(3, 3, 1),
(4, 1, 1),
(5, 2, 1),
(6, 3, 2),
(7, 1, 1),
(7, 3, 1),
(8, 2, 1),
(9, 3, 1),
(10, 2, 1),
(11, 1, 1),
(12, 3, 1),
(13, 1, 2),
(14, 2, 1),
(15, 3, 1),
(16, 1, 1),
(17, 2, 1),
(18, 1, 1),
(19, 3, 1),
(20, 2, 1),
(21, 1, 2),
(22, 3, 1),
(23, 1, 1),
(24, 2, 1),
(25, 3, 1),
(26, 1, 1),
(27, 2, 1),
(28, 3, 1),
(32, 1, 2),
(33, 2, 1),
(34, 1, 3),
(35, 3, 1),
(36, 1, 2),
(37, 2, 1),
(38, 1, 1),
(39, 2, 1),
(40, 3, 1),
(41, 3, 2),
(42, 1, 1),
(43, 2, 1),
(44, 1, 2),
(45, 2, 1),
(46, 3, 1),
(47, 3, 1),
(48, 1, 2),
(49, 2, 1),
(50, 3, 2),
(51, 2, 1),
(52, 1, 1),
(53, 2, 1),
(54, 1, 1),
(55, 3, 2),
(56, 2, 1),
(57, 3, 1),
(58, 1, 2),
(59, 2, 1),
(60, 3, 1),
(61, 1, 1),
(62, 2, 1),
(63, 3, 1),
(64, 1, 1),
(65, 2, 1),
(66, 3, 1),
(67, 1, 1),
(68, 2, 1),
(69, 3, 2),
(70, 1, 1),
(71, 2, 1),
(72, 3, 1),
(73, 1, 1),
(74, 2, 1),
(75, 3, 1);

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
  `mat_khau` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `vai_tro` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT 'khách hàng'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `khach_hang`
--

INSERT INTO `khach_hang` (`ma_kh`, `ho_ten`, `email`, `SDT`, `avatar`, `tai_khoan`, `mat_khau`, `vai_tro`) VALUES
(1, 'Nguyễn Văn An', 'nguyenvanan@gmail.com', '0901112223', 'publics/img/avatar1.jpg', 'nguyenvanan', '123456', 'khách hàng'),
(2, 'Trần Thị Bình', 'tranthibinh@gmail.com', '0902223334', 'publics/img/avatar2.jpg', 'tranthibinh', '123456', 'khách hàng'),
(3, 'Lê Văn Cao', 'levancao@gmail.com', '0903334445', 'publics/img/avatar3.jpg', 'levancao', '123456', 'khách hàng'),
(4, 'Phạm Thị Dung', 'phamthidung@gmail.com', '0904445556', 'publics/img/avatar4.jpg', 'phamthidung', '123456', 'khách hàng'),
(5, 'Nguyễn Văn Mạnh', 'nguyenmanh@gmail.com', '0329123421', 'publics/img/avatar/avatar_5_1763706426.jpg', 'manh', '1234', 'khách hàng'),
(6, 'nam17', 'nguyenhoainam@gmail.com', '0329123422', 'publics/img/avatar/avatar_6_1763710120.jpg', 'nam18', '123456', 'khách hàng'),
(7, 'Hoàng Thị Minh', 'hoangminh@gmail.com', '0905556667', 'publics/img/avatar7.jpg', 'hoangminh', '123456', 'khách hàng'),
(8, 'Vũ Đức Hùng', 'vuduchung@gmail.com', '0906667778', 'publics/img/avatar8.jpg', 'vuduchung', '123456', 'khách hàng'),
(9, 'Đặng Thu Hà', 'dangthuha@gmail.com', '0907778889', 'publics/img/avatar9.jpg', 'dangthuha', '123456', 'khách hàng'),
(10, 'Bùi Văn Tài', 'buivantai@gmail.com', '0908889990', 'publics/img/avatar10.jpg', 'buivantai', '123456', 'khách hàng');

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
(1, 'Cục Vàng Của Ngoại', 'Tâm lý, Gia đình', 119, 'Đạo diễn A', 'Diễn viên X, Diễn viên Y', 'Câu chuyện cảm động về tình bà cháu', 13, '2025-10-25', 'publics/img/cuc_vang_cua_ngoai.jpg', 1, b'1'),
(2, 'Nhà Ma Xó', 'Kinh dị', 108, 'Đạo diễn B', 'Diễn viên Z, Diễn viên W', 'Kinh dị tâm lý đầy kịch tính', 16, '2025-10-25', 'publics/img/Nha_ma_xo.png', 1, b'1'),
(3, 'Kinh Dị Nhật Vị', 'Kinh dị', 80, 'Đạo diễn C', 'Diễn viên P, Diễn viên Q', 'Trải nghiệm kinh dị từ Nhật Bản', 18, '2025-10-25', 'publics/img/trailer_kinh_di_nhat.jpg', 1, b'0'),
(4, 'Bịt Mắt Bắt Nai', 'Hài, Gia đình', 105, 'Đạo diễn D', 'Diễn viên R, Diễn viên S', 'Phim hài gia đình vui nhộn', 13, '2025-10-26', 'publics/img/trailer_bit_mat.jpg', 1, b'1'),
(5, 'Siêu Anh Hùng Vũ Trụ', 'Hành động, Khoa học viễn tưởng', 142, 'Đạo diễn E', 'Chris Evans, Robert Downey Jr., Scarlett Johansson', 'Cuộc chiến bảo vệ vũ trụ của các siêu anh hùng', 13, '2025-11-20', 'publics/img/sieu_anh_hung.jpg', 3, b'1'),
(6, 'Tình Yêu Thời Covid', 'Lãng mạn, Chính kịch', 128, 'Đạo diễn F', 'Jennifer Lawrence, Timothée Chalamet', 'Câu chuyện tình yêu trong đại dịch toàn cầu', 16, '2025-11-21', 'publics/img/tinh_yeu_covid.jpg', 3, b'0'),
(7, 'Cuộc Phiêu Lưu Của Cún Con', 'Hoạt hình, Gia đình', 96, 'Đạo diễn G', 'Lồng tiếng: Park Seo-joon, Kim Go-eun', 'Chuyến phiêu lưu đầy màu sắc của chú chó nhỏ', 0, '2025-11-22', 'publics/img/cun_con.jpg', 4, b'1');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phong`
--

CREATE TABLE `phong` (
  `ma_phong` int(11) NOT NULL,
  `ma_rap` int(11) NOT NULL,
  `ten_phong` varchar(50) NOT NULL,
  `loai_man_hinh` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `phong`
--

INSERT INTO `phong` (`ma_phong`, `ma_rap`, `ten_phong`, `loai_man_hinh`) VALUES
(1, 1, 'Phòng 1 - IMAX', 'IMAX'),
(2, 1, 'Phòng 2 - Standard', '2D'),
(3, 1, 'Phòng 3 - 3D', '3D'),
(4, 2, 'Phòng 1 - Premium', '2D'),
(5, 2, 'Phòng 2 - 3D', '3D'),
(6, 3, 'Phòng 1 - Family', '2D'),
(7, 3, 'Phòng 2 - IMAX', 'IMAX');

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

--
-- Đang đổ dữ liệu cho bảng `quan_ly`
--

INSERT INTO `quan_ly` (`ma_quan_ly`, `ten`, `email`, `SDT`, `tai_khoan`, `mat_khau`) VALUES
(1, 'Admin System', 'admin@betacinema.com', '0900111222', 'admin', '123456'),
(2, 'Quản Lý Chi Nhánh', 'manager@betacinema.com', '0900333444', 'manager', '123456'),
(3, 'Nguyễn Thị Hoa', 'nguyenthihoa@betacinema.com', '0900555666', 'hoanguyen', '123456'),
(4, 'Trần Văn Đạt', 'tranvandat@betacinema.com', '0900666777', 'dattran', '123456');

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
(1, 'Beta Nguyễn Du', '116 Nguyễn Du, P. Bến Thành, Q.1', 'Hồ Chí Minh', '02838227899', 'publics/img/rap/1_1763705987_logo_wolf.jpg', 'Rạp cao cấp tại trung tâm Quận 1, trang bị hệ thống âm thanh Dolby Atmos và màn hình IMAX'),
(2, 'Beta Giải Phóng', 'Tòa nhà Hoàng Gia Plaza, Giải Phóng, Hà Nội', 'Hà Nội', '0867460053', 'publics/img/rap/beta_giai_phong.jpg', 'Rạp hiện đại với 8 phòng chiếu, phục vụ đa dạng thể loại phim'),
(3, 'Beta Linh Đàm', 'HH4A Linh Đàm, Hoàng Mai', 'Hà Nội', '0366635837', 'publics/img/rap/beta_linh_dam.jpg', 'Rạp phim gia đình với không gian rộng rãi và dịch vụ tiện ích đa dạng');

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
(1, 1, 1, 1, '2025-10-25', '09:00:00', '11:00:00', 85000.00),
(2, 1, 1, 1, '2025-10-26', '14:00:00', '16:00:00', 95000.00),
(3, 2, 2, 1, '2025-11-20', '10:00:00', '12:00:00', 75000.00),
(4, 3, 4, 1, '2025-11-20', '20:00:00', '21:30:00', 80000.00),
(5, 4, 6, 1, '2025-10-25', '09:00:00', '11:00:00', 70000.00),
(6, 5, 3, 3, '2025-11-20', '18:00:00', '20:22:00', 110000.00),
(7, 5, 7, 4, '2025-11-21', '20:00:00', '22:22:00', 120000.00),
(8, 6, 2, 3, '2025-11-21', '19:00:00', '21:08:00', 90000.00),
(9, 6, 4, 4, '2025-11-22', '14:30:00', '16:38:00', 85000.00),
(10, 7, 6, 3, '2025-11-22', '10:00:00', '11:36:00', 65000.00),
(11, 7, 6, 3, '2025-11-22', '13:00:00', '14:36:00', 70000.00),
(12, 1, 2, 3, '2025-11-23', '16:00:00', '17:59:00', 80000.00),
(13, 2, 1, 1, '2025-11-23', '21:00:00', '22:48:00', 95000.00),
(14, 5, 1, 3, '2025-11-29', '14:00:00', '16:22:00', 110000.00),
(15, 6, 2, 3, '2025-11-29', '19:00:00', '21:08:00', 90000.00),
(16, 7, 6, 3, '2025-11-30', '10:00:00', '11:36:00', 65000.00),
(17, 1, 3, 3, '2025-11-30', '16:00:00', '17:59:00', 80000.00),
(18, 2, 1, 1, '2025-12-01', '21:00:00', '22:48:00', 95000.00),
(19, 3, 4, 4, '2025-12-01', '20:00:00', '21:30:00', 80000.00),
(20, 4, 6, 3, '2025-12-02', '09:00:00', '10:45:00', 70000.00),
(21, 5, 3, 3, '2025-12-02', '18:00:00', '20:22:00', 110000.00),
(22, 6, 2, 3, '2025-12-03', '19:00:00', '21:08:00', 90000.00),
(23, 7, 6, 3, '2025-12-03', '13:00:00', '14:36:00', 70000.00),
(24, 1, 2, 3, '2025-12-04', '16:00:00', '17:59:00', 80000.00),
(25, 2, 1, 1, '2025-12-04', '21:00:00', '22:48:00', 95000.00),
(26, 3, 4, 4, '2025-12-05', '20:00:00', '21:30:00', 80000.00);

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
-- Đang đổ dữ liệu cho bảng `ve`
--

INSERT INTO `ve` (`ma_ve`, `ma_hoa_don`, `ma_suat_chieu`, `ma_ghe`, `gia`) VALUES
(1, 1, 1, 1, 85000.00),
(2, 1, 1, 2, 85000.00),
(3, 2, 2, 3, 95000.00),
(4, 2, 2, 4, 95000.00),
(5, 3, 3, 11, 75000.00),
(6, 4, 4, 17, 80000.00),
(7, 4, 6, 22, 110000.00),
(8, 5, 7, 26, 120000.00),
(9, 5, 7, 27, 120000.00),
(10, 6, 8, 30, 90000.00),
(11, 7, 9, 35, 85000.00),
(12, 7, 10, 21, 65000.00),
(13, 7, 10, 30, 65000.00),
(14, 8, 11, 31, 70000.00),
(15, 9, 12, 11, 80000.00),
(16, 10, 1, 7, 85000.00),
(17, 10, 1, 8, 85000.00),
(18, 11, 2, 9, 95000.00),
(19, 11, 2, 10, 95000.00),
(20, 12, 3, 12, 75000.00),
(21, 13, 4, 18, 80000.00),
(22, 13, 4, 19, 80000.00),
(23, 13, 4, 20, 80000.00),
(24, 14, 5, 21, 70000.00),
(25, 15, 6, 23, 110000.00),
(26, 16, 7, 26, 120000.00),
(27, 16, 7, 27, 120000.00),
(28, 17, 8, 30, 90000.00),
(29, 17, 8, 31, 90000.00),
(30, 18, 9, 35, 85000.00),
(31, 18, 9, 36, 85000.00),
(32, 19, 10, 21, 65000.00),
(33, 20, 11, 30, 70000.00),
(34, 20, 11, 31, 70000.00),
(35, 21, 12, 12, 80000.00),
(36, 21, 12, 13, 80000.00),
(37, 21, 12, 14, 80000.00),
(38, 22, 13, 3, 95000.00),
(39, 23, 6, 24, 110000.00),
(40, 23, 6, 25, 110000.00),
(41, 24, 7, 28, 120000.00),
(42, 25, 8, 32, 90000.00),
(43, 26, 9, 37, 85000.00),
(44, 26, 9, 38, 85000.00),
(45, 27, 10, 33, 65000.00),
(46, 27, 10, 34, 65000.00),
(47, 28, 11, 32, 70000.00),
(48, 32, 1, 7, 85000.00),
(49, 32, 1, 8, 85000.00),
(50, 32, 1, 9, 85000.00),
(51, 33, 2, 3, 95000.00),
(52, 33, 2, 4, 95000.00),
(53, 34, 3, 11, 75000.00),
(54, 34, 3, 12, 75000.00),
(55, 34, 3, 13, 75000.00),
(56, 34, 3, 14, 75000.00),
(57, 35, 4, 17, 80000.00),
(58, 35, 4, 18, 80000.00),
(59, 36, 5, 21, 70000.00),
(60, 36, 5, 30, 70000.00),
(61, 36, 5, 31, 70000.00),
(62, 36, 5, 32, 70000.00),
(63, 37, 6, 22, 110000.00),
(64, 37, 6, 23, 110000.00),
(65, 38, 7, 26, 120000.00),
(66, 38, 7, 27, 120000.00),
(67, 38, 7, 28, 120000.00),
(68, 39, 8, 30, 90000.00),
(69, 39, 8, 31, 90000.00),
(70, 40, 9, 35, 85000.00),
(71, 41, 10, 21, 65000.00),
(72, 41, 10, 30, 65000.00),
(73, 41, 10, 31, 65000.00),
(74, 42, 11, 32, 70000.00),
(75, 42, 11, 33, 70000.00),
(76, 42, 11, 34, 70000.00),
(77, 43, 12, 11, 80000.00),
(78, 43, 12, 12, 80000.00),
(79, 44, 13, 5, 95000.00),
(80, 44, 13, 6, 95000.00),
(81, 44, 13, 7, 95000.00),
(82, 45, 6, 24, 110000.00),
(83, 45, 6, 25, 110000.00),
(84, 46, 7, 28, 120000.00),
(85, 46, 7, 29, 120000.00),
(86, 47, 8, 32, 90000.00),
(87, 48, 9, 37, 85000.00),
(88, 48, 9, 38, 85000.00),
(89, 48, 9, 35, 85000.00),
(90, 49, 10, 33, 65000.00),
(91, 49, 10, 34, 65000.00),
(92, 49, 10, 21, 65000.00),
(93, 50, 11, 30, 70000.00),
(94, 50, 11, 31, 70000.00),
(95, 51, 12, 15, 80000.00),
(96, 51, 12, 16, 80000.00),
(97, 52, 13, 1, 95000.00),
(98, 52, 13, 2, 95000.00),
(99, 32, 14, 15, 110000.00),
(100, 32, 14, 16, 110000.00),
(101, 33, 15, 12, 90000.00),
(102, 33, 15, 13, 90000.00),
(103, 34, 16, 21, 65000.00),
(104, 34, 16, 30, 65000.00),
(105, 35, 17, 22, 80000.00),
(106, 35, 17, 23, 80000.00),
(107, 36, 18, 1, 95000.00),
(108, 36, 18, 2, 95000.00),
(109, 37, 19, 17, 80000.00),
(110, 37, 19, 18, 80000.00),
(111, 38, 20, 21, 70000.00),
(112, 38, 20, 30, 70000.00),
(113, 39, 21, 22, 110000.00),
(114, 39, 21, 23, 110000.00),
(115, 40, 22, 12, 90000.00),
(116, 40, 22, 13, 90000.00),
(117, 41, 23, 21, 70000.00),
(118, 41, 23, 30, 70000.00),
(119, 42, 24, 11, 80000.00),
(120, 42, 24, 12, 80000.00),
(121, 43, 25, 1, 95000.00),
(122, 43, 25, 2, 95000.00),
(123, 44, 26, 17, 80000.00),
(124, 44, 26, 18, 80000.00);

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
  MODIFY `ma_combo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `danh_gia_rap`
--
ALTER TABLE `danh_gia_rap`
  MODIFY `ma_danh_gia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `ghe`
--
ALTER TABLE `ghe`
  MODIFY `ma_ghe` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT cho bảng `hoa_don`
--
ALTER TABLE `hoa_don`
  MODIFY `ma_hoa_don` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT cho bảng `khach_hang`
--
ALTER TABLE `khach_hang`
  MODIFY `ma_kh` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `phim`
--
ALTER TABLE `phim`
  MODIFY `ma_phim` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `phong`
--
ALTER TABLE `phong`
  MODIFY `ma_phong` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `quan_ly`
--
ALTER TABLE `quan_ly`
  MODIFY `ma_quan_ly` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `rap`
--
ALTER TABLE `rap`
  MODIFY `ma_rap` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `suat_chieu`
--
ALTER TABLE `suat_chieu`
  MODIFY `ma_suat_chieu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT cho bảng `ve`
--
ALTER TABLE `ve`
  MODIFY `ma_ve` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
