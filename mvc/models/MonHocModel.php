<?php
class MonHocModel extends DB
{
public function create($mamon, $tenmon, $sotinchi, $sotietlythuyet, $sotietthuchanh)
{
    // Kiểm tra người dùng đã đăng nhập
    if (!isset($_SESSION['user_id'])) {
        return false; // Hoặc throw new Exception('Người dùng chưa đăng nhập');
    }
    $manguoidung = $_SESSION['user_id'];

    // Bắt đầu giao dịch
    mysqli_begin_transaction($this->con);

    try {
        // Chuẩn bị truy vấn cho bảng monhoc
        $sql_monhoc = "INSERT INTO `monhoc` (`mamonhoc`, `tenmonhoc`, `sotinchi`, `sotietlythuyet`, `sotietthuchanh`, `trangthai`) VALUES (?, ?, ?, ?, ?, 1)";
        $stmt_monhoc = mysqli_prepare($this->con, $sql_monhoc);
        if (!$stmt_monhoc) {
            throw new Exception('Lỗi chuẩn bị truy vấn môn học: ' . mysqli_error($this->con));
        }

        // Gán tham số cho bảng monhoc
        mysqli_stmt_bind_param($stmt_monhoc, "ssiii", $mamon, $tenmon, $sotinchi, $sotietlythuyet, $sotietthuchanh);
        $result_monhoc = mysqli_stmt_execute($stmt_monhoc);
        mysqli_stmt_close($stmt_monhoc);

        if (!$result_monhoc) {
            throw new Exception('Lỗi chèn môn học: ' . mysqli_error($this->con));
        }

        // Chuẩn bị truy vấn cho bảng phancong
        $sql_phancong = "INSERT INTO `phancong` (`mamonhoc`, `manguoidung`) VALUES (?, ?)";
        $stmt_phancong = mysqli_prepare($this->con, $sql_phancong);
        if (!$stmt_phancong) {
            throw new Exception('Lỗi chuẩn bị truy vấn phân công: ' . mysqli_error($this->con));
        }

        // Gán tham số cho bảng phancong
        mysqli_stmt_bind_param($stmt_phancong, "ss", $mamon, $manguoidung);
        $result_phancong = mysqli_stmt_execute($stmt_phancong);
        mysqli_stmt_close($stmt_phancong);

        if (!$result_phancong) {
            throw new Exception('Lỗi chèn phân công: ' . mysqli_error($this->con));
        }

        mysqli_commit($this->con);
        return true;
    } catch (Exception $e) {
        mysqli_rollback($this->con);
        error_log("Lỗi tạo môn học: " . $e->getMessage());
        return false;
    }
}
    public function update($id, $mamon, $tenmon, $sotinchi, $sotietlythuyet, $sotietthuchanh)
    {
        $valid = true;
        $sql = "UPDATE `monhoc` SET `mamonhoc`='$mamon',`tenmonhoc`='$tenmon',`sotinchi`='$sotinchi',`sotietlythuyet`='$sotietlythuyet',`sotietthuchanh`='$sotietthuchanh' WHERE `mamonhoc`='$id'";
        $result = mysqli_query($this->con, $sql);
        if (!$result) $valid = false;
        return $valid;
    }

    public function delete($mamon)
    {
        $valid = true;
        $sql = "UPDATE `monhoc` SET `trangthai`= 0 WHERE `mamonhoc`='$mamon'";
        $result = mysqli_query($this->con, $sql);
        if (!$result) $valid = false;
        return $valid;
    }

    public function getAll()
    {
        $sql = "SELECT * FROM `monhoc` WHERE `trangthai` = 1";
        $result = mysqli_query($this->con, $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM `monhoc` WHERE `mamonhoc` = '$id'";
        $result = mysqli_query($this->con, $sql);
        return mysqli_fetch_assoc($result);
    }

    public function search($input)
    {
        $sql = "SELECT * FROM `monhoc` WHERE `mamonhoc` LIKE '%$input%' OR `tenmonhoc` LIKE N'%$input%';";
        $result = mysqli_query($this->con, $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function getAllSubjectAssignment($userid)
    {
        $sql = "SELECT monhoc.* FROM phancong, monhoc WHERE manguoidung = '$userid' AND monhoc.mamonhoc = phancong.mamonhoc AND monhoc.trangthai = 1";
        $result = mysqli_query($this->con, $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function getQuery($filter, $input, $args)
    {
        $query = "SELECT * FROM `monhoc` WHERE `trangthai` = 1";
        if ($input) {
            $query = $query . " AND (`monhoc`.`tenmonhoc` LIKE N'%${input}%' OR `monhoc`.`mamonhoc` LIKE '%${input}%')";
        }
        return $query;
    }

    public function checkSubject($mamon)
    {
        $sql = "SELECT * FROM `monhoc` WHERE `mamonhoc` = $mamon";
        $result = mysqli_query($this->con, $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }
}
