<?php
// app/models/GheSuatChieuModel.php

class GheSuatChieuModel {
    private $conn;
    private $table = "ghe_suat_chieu";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Đếm số ghế trống (trang_thai = 0)
    public function countGheTrong($ma_suat_chieu) {
        $sql = "SELECT COUNT(*) AS so_ghe_trong
                FROM ghe_suat_chieu
                WHERE ma_suat_chieu = :ma_suat_chieu
                AND (trang_thai = 0 OR trang_thai IS NULL)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':ma_suat_chieu', $ma_suat_chieu);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['so_ghe_trong'] ?? 0;
    }

    // Lấy danh sách ghế của suất chiếu
    public function getGheBySuatChieu($ma_suat_chieu) {
        $sql = "SELECT ma_ghe, ma_suat_chieu, trang_thai
                FROM ghe_suat_chieu
                WHERE ma_suat_chieu = :ma_suat_chieu
                ORDER BY ma_ghe ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':ma_suat_chieu', $ma_suat_chieu);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * Lấy chi tiết ghế + trạng thái cho 1 suất chiếu
     * JOIN ghe để có loai_ghe, ma_phong_ghe,... – rất tiện cho view chon_ghe.php
     */
    public function getChiTietGheBySuatChieu($ma_suat_chieu)
    {
        $sql = "SELECT 
                    gsc.ma_ghe,
                    gsc.ma_suat_chieu,
                    gsc.trang_thai,
                    g.ma_phong,
                    g.loai_ghe,
                    g.ma_phong_ghe,
                    g.vi_tri
                FROM {$this->table} AS gsc
                INNER JOIN ghe AS g ON gsc.ma_ghe = g.ma_ghe
                WHERE gsc.ma_suat_chieu = :ma_suat_chieu
                ORDER BY g.ma_phong, g.ma_phong_ghe ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':ma_suat_chieu', $ma_suat_chieu, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Ép trang_thai về int cho dễ xử lý trong PHP/JS
        foreach ($rows as &$row) {
            $row['trang_thai'] = (int)$row['trang_thai']; // BIT(1) -> 0/1
        }

        return $rows;
    }

    /**
     * Cập nhật trạng thái 1 ghế trong 1 suất chiếu
     *  - $trang_thai: 0 = trống, 1 = đã đặt/bán
     */
    public function updateTrangThaiGhe($ma_suat_chieu, $ma_ghe, $trang_thai)
    {
        $sql = "UPDATE {$this->table}
                SET trang_thai = :trang_thai
                WHERE ma_suat_chieu = :ma_suat_chieu
                  AND ma_ghe = :ma_ghe";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':trang_thai', $trang_thai, PDO::PARAM_INT);
        $stmt->bindParam(':ma_suat_chieu', $ma_suat_chieu, PDO::PARAM_INT);
        $stmt->bindParam(':ma_ghe', $ma_ghe, PDO::PARAM_INT);

        return $stmt->execute();
    }

/**
 * Cập nhật trạng thái nhiều ghế trong 1 suất chiếu (transaction)
 * Đã sửa: Sử dụng bindValue để đảm bảo đúng kiểu dữ liệu INT
 */
public function updateTrangThaiNhieuGhe($ma_suat_chieu, array $dsMaGhe, $trang_thai)
{
    if (empty($dsMaGhe)) {
        return false;
    }

    $sql = "UPDATE {$this->table}
            SET trang_thai = :trang_thai
            WHERE ma_suat_chieu = :ma_suat_chieu
              AND ma_ghe = :ma_ghe";

    try {
        $stmt = $this->conn->prepare($sql);

        foreach ($dsMaGhe as $ma_ghe) {
            // SỬA: Dùng bindValue để ép kiểu INT rõ ràng
            $stmt->bindValue(':trang_thai', (int)$trang_thai, PDO::PARAM_INT);
            $stmt->bindValue(':ma_suat_chieu', (int)$ma_suat_chieu, PDO::PARAM_INT);
            $stmt->bindValue(':ma_ghe', (int)$ma_ghe, PDO::PARAM_INT);
            
            $stmt->execute();
        }

        return true;
    } catch (\Exception $e) {
        error_log("Lỗi updateTrangThaiNhieuGhe: " . $e->getMessage());
        return false;
    }
}

    /**
     * Kiểm tra xem danh sách ghế có còn trống không cho 1 suất chiếu
     *  - Trả về true nếu TẤT CẢ ghế đều trang_thai = 0 (chưa bán)
     */
    public function areGheConTrong($ma_suat_chieu, array $dsMaGhe)
    {
        if (empty($dsMaGhe)) {
            return false;
        }

        $placeholders = implode(',', array_fill(0, count($dsMaGhe), '?'));

        $sql = "SELECT COUNT(*) AS so_da_dat
                FROM {$this->table}
                WHERE ma_suat_chieu = ?
                  AND ma_ghe IN ($placeholders)
                  AND trang_thai = 1"; // 1 = đã đặt/bán

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(1, $ma_suat_chieu, PDO::PARAM_INT);

        foreach ($dsMaGhe as $index => $ma_ghe) {
            $stmt->bindValue($index + 2, $ma_ghe, PDO::PARAM_INT);
        }

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return ((int)$row['so_da_dat'] === 0);
    }
    /**
     * Gom danh sách ghế theo hàng (A, B, C...) và sort số ghế
     */
    public function groupSeatsByRow(array $dsGheDayDu): array
    {
        $rows = [];

        foreach ($dsGheDayDu as $seat) {
            // Tên ghế hiển thị: ưu tiên ma_phong_ghe (A1, B2,...), fallback sang vi_tri nếu cần
            $seatName = $seat['ma_phong_ghe'] ?? $seat['vi_tri'] ?? ('G' . $seat['ma_ghe']);
            $rowLabel = mb_substr($seatName, 0, 1, 'UTF-8');

            if (!isset($rows[$rowLabel])) {
                $rows[$rowLabel] = [];
            }

            $seat['seat_name'] = $seatName;
            $rows[$rowLabel][] = $seat;
        }

        // Sắp xếp hàng theo alphabet
        ksort($rows);

        // Sắp xếp ghế trong từng hàng theo số
        foreach ($rows as $label => &$list) {
            usort($list, function ($a, $b) {
                $aNum = (int)preg_replace('/\D/', '', $a['seat_name']);
                $bNum = (int)preg_replace('/\D/', '', $b['seat_name']);
                return $aNum <=> $bNum;
            });
        }
        unset($list);

        return $rows;
    }
    /**
 * Lấy đầy đủ thông tin của 1 suất chiếu:
 *  - Thông tin suất: ngay_chieu, gio_bat_dau, gio_ket_thuc, gia_ve_co_ban
 *  - Thông tin phim: ten_phim, the_loai, thoi_luong, gioi_han_do_tuoi, anh_trailer
 *  - Thông tin rạp:  ten_rap
 *  - Thông tin phòng: ten_phong, ma_phong
 */
public function getThongTinSuatChieu($ma_suat_chieu)
{
    $sql = "SELECT 
                s.ma_suat_chieu,
                s.ngay_chieu,
                s.gio_bat_dau,
                s.gio_ket_thuc,
                s.gia_ve_co_ban,
                p.ma_phim,
                p.ten_phim,
                p.the_loai,
                p.thoi_luong,
                p.gioi_han_do_tuoi,
                p.anh_trailer,
                r.ma_rap,
                r.ten_rap,
                ph.ma_phong,
                ph.ten_phong
            FROM suat_chieu AS s
            INNER JOIN phim  AS p  ON s.ma_phim = p.ma_phim
            INNER JOIN phong AS ph ON s.ma_phong = ph.ma_phong
            INNER JOIN rap   AS r  ON ph.ma_rap = r.ma_rap
            WHERE s.ma_suat_chieu = :ma_suat_chieu
            LIMIT 1";

    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':ma_suat_chieu', $ma_suat_chieu, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

}
?>
