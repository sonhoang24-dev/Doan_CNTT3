<?php
class NhomModel extends DB
{
    public function create($tennhom, $ghichu, $namhoc, $hocky, $giangvien, $mamonhoc)
    {
        $valid = true;
        $mamoi = substr(md5(mt_rand()), 0, 7);
        $sql = "INSERT INTO `nhom`(`tennhom`, `ghichu`, `mamoi`,`namhoc`, `hocky`, `giangvien`, `mamonhoc`) VALUES ('$tennhom','$ghichu','$mamoi','$namhoc','$hocky','$giangvien','$mamonhoc')";
        $result = mysqli_query($this->con, $sql);
        if (!$result) {
            $valid = false;
        }
        return $valid;
    }

    public function update($manhom, $tennhom, $ghichu, $namhoc, $hocky, $mamonhoc)
    {
        $valid = true;
        $sql = "UPDATE `nhom` SET `tennhom`='$tennhom',`ghichu`='$ghichu',`namhoc`='$namhoc',`hocky`='$hocky',`mamonhoc`='$mamonhoc' WHERE `manhom`='$manhom'";
        $result = mysqli_query($this->con, $sql);
        if (!$result) {
            $valid = false;
        }
        return $valid;
    }

    public function delete($manhom)
    {
        $valid = true;
        $sql = "UPDATE `nhom` SET `trangthai`='0' WHERE `manhom`='$manhom'";
        $result = mysqli_query($this->con, $sql);
        if (!$result) {
            $valid = false;
        }
        return $valid;
    }

    // Ẩn || Hiện nhóm
    public function hide($manhom, $giatri)
    {
        $valid = true;
        $sql = "UPDATE `nhom` SET `hienthi`=' $giatri' WHERE `manhom`='$manhom'";
        $result = mysqli_query($this->con, $sql);
        if (!$result) {
            $valid = false;
        }
        return $valid;
    }

    public function sv_hide($manhom, $masv, $giatri)
    {
        $valid = true;
        $sql = "UPDATE `chitietnhom` SET `hienthi`= '$giatri' WHERE `manhom`='$manhom' AND `manguoidung`='$masv'";
        $result = mysqli_query($this->con, $sql);
        if (!$result) $valid = false;
        return $valid;
    }

    public function getById($manhom)
    {
        $sql = "SELECT * FROM `nhom` WHERE `manhom` = $manhom";
        $result = mysqli_query($this->con, $sql);
        return mysqli_fetch_assoc($result);
    }

    // Lấy tất cả nhóm của người tạo và gom lại theo mã môn học, năm học, học kỳ
    public function getBySubject($nguoitao, $hienthi)
    {
        $sht = $hienthi == 2 ? "" : "AND nhom.hienthi = $hienthi";
        $sql = "SELECT monhoc.mamonhoc, monhoc.tenmonhoc, nhom.namhoc, nhom.hocky, nhom.manhom, nhom.tennhom, nhom.ghichu, nhom.siso, nhom.hienthi
        FROM nhom, monhoc
        WHERE nhom.mamonhoc = monhoc.mamonhoc AND nhom.giangvien = '$nguoitao' AND nhom.trangthai = 1 $sht";
        $result = mysqli_query($this->con, $sql);
        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        $newArray = [];
        foreach ($rows as $item) {
            $foundIndex = -1;
            foreach ($newArray as $key => $newItem) {
                if ($newItem["mamonhoc"] == $item["mamonhoc"] && $newItem["namhoc"] == $item["namhoc"] && $newItem["hocky"] == $item["hocky"]) {
                    $foundIndex = $key;
                    break;
                }
            }
            $detail_group = [
                "manhom" => $item["manhom"],
                "tennhom" => $item["tennhom"],
                "ghichu" => $item["ghichu"],
                "siso" => $item["siso"],
                "hienthi" => $item["hienthi"]
            ];
            if ($foundIndex == -1) {
                $newArray[] = [
                    "mamonhoc" => $item["mamonhoc"],
                    "tenmonhoc" => $item["tenmonhoc"],
                    "namhoc" => $item["namhoc"],
                    "hocky" => $item["hocky"],
                    "nhom" => [$detail_group],
                ];
            } else {
                $newArray[$foundIndex]['nhom'][] = $detail_group;
            }
        }
        return $newArray;
    }

    // Cập nhật mã mời
    public function updateInvitedCode($manhom)
    {
        $valid = true;
        do {
            $mamoi = substr(md5(mt_rand()), 0, 7);
            $check = $this->getIdFromInvitedCode($mamoi);
        } while ($check != null);
        $sql = "UPDATE `nhom` SET `mamoi`='$mamoi' WHERE `manhom` = '$manhom'";
        $result = mysqli_query($this->con, $sql);
        if (!$result) $valid = false;
        return $valid;
    }

    // Lấy mã mời 
    public function getInvitedCode($manhom)
    {
        $sql = "SELECT mamoi FROM nhom WHERE manhom = '$manhom'";
        $result = mysqli_query($this->con, $sql);
        return mysqli_fetch_assoc($result);
    }

    // Lấy mã nhóm từ mã mời
    public function getIdFromInvitedCode($mamoi)
    {
        $sql = "SELECT `manhom` FROM `nhom` WHERE `mamoi` = '$mamoi'";
        $result = mysqli_query($this->con, $sql);
        return mysqli_fetch_assoc($result);
    }

    // Thêm sinh viên vào nhóm
public function join($manhom, $manguoidung)
{
    $valid = true;
    $checkSql = "SELECT * FROM `chitietnhom` WHERE `manhom` = ? AND `manguoidung` = ?";
    $stmt = $this->con->prepare($checkSql);
    $stmt->bind_param("ss", $manhom, $manguoidung);
    $stmt->execute();
    $checkResult = $stmt->get_result();

    if (mysqli_num_rows($checkResult) == 0) {
        $insertSql = "INSERT INTO `chitietnhom`(`manhom`, `manguoidung`, `hienthi`) VALUES (?, ?, 1)";
        $stmt = $this->con->prepare($insertSql);
        $stmt->bind_param("ss", $manhom, $manguoidung);
        if (!$stmt->execute()) {
            error_log("Failed to insert into chitietnhom: " . $stmt->error);
            $valid = false;
        }
    } else {
        $updateSql = "UPDATE `chitietnhom` SET `hienthi` = 1 WHERE `manhom` = ? AND `manguoidung` = ?";
        $stmt = $this->con->prepare($updateSql);
        $stmt->bind_param("ss", $manhom, $manguoidung);
        if (!$stmt->execute()) {
            error_log("Failed to update chitietnhom: " . $stmt->error);
            $valid = false;
        }
    }
    return $valid;
}


    // Thoát khỏi nhóm 
    public function SVDelete($manhom, $manguoidung)
    {
        $valid = true;
        $sql = "DELETE FROM `chitietnhom` WHERE `manhom` = '$manhom' AND `manguoidung` = '$manguoidung'";
        $result = mysqli_query($this->con, $sql);
        // $this->updateSiso($manhom);
        if (!$result) $valid = false;
        return $valid;
    }

    // Lấy các nhóm mà sinh viên tham gia
public function getAllGroup_User($user_id, $hienthi)
{
    error_log("getAllGroup_User called with user_id=$user_id, hienthi=$hienthi");
    $sql = "SELECT monhoc.mamonhoc,monhoc.tenmonhoc,nhom.manhom, nhom.tennhom, namhoc, hocky ,nguoidung.hoten, nguoidung.avatar,chitietnhom.hienthi
    FROM chitietnhom, nhom, nguoidung, monhoc
    WHERE chitietnhom.manhom = nhom.manhom AND nguoidung.id = nhom.giangvien AND monhoc.mamonhoc = nhom.mamonhoc AND chitietnhom.manguoidung = $user_id
    AND chitietnhom.hienthi = $hienthi AND nhom.trangthai != 0";
    $result = mysqli_query($this->con, $sql);
    if (!$result) {
        error_log("SQL Error: " . mysqli_error($this->con));
    }
    $rows = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    error_log("Result: " . json_encode($rows));
    return $rows;
}
    // Lấy chi tiết một nhóm mà sinh viên tham gia
    public function getDetailGroup($manhom)
    {
        $sql = "SELECT monhoc.mamonhoc,monhoc.tenmonhoc,nhom.manhom, nhom.tennhom, namhoc, hocky, nhom.giangvien, nguoidung.hoten, nguoidung.avatar
        FROM nhom, nguoidung, monhoc
        WHERE nguoidung.id = nhom.giangvien AND monhoc.mamonhoc = nhom.mamonhoc AND nhom.manhom = $manhom";
        $result = mysqli_query($this->con, $sql);
        return mysqli_fetch_assoc($result);
    }

    // Lấy danh sách bạn học chung nhóm
    public function getSvList($manhom)
    {
        $sql = "SELECT id, avatar, hoten, email, gioitinh, ngaysinh FROM chitietnhom, nguoidung WHERE manguoidung = id AND chitietnhom.manhom = $manhom";
        $result = mysqli_query($this->con, $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    // hàm update sỉ số sinh viên trong nhóm
    public function updateSiso($manhom)
    {
        $valid = true;
        $sql = "UPDATE `nhom` SET `siso`= (SELECT count(*) FROM `chitietnhom` where manhom = $manhom ) WHERE `manhom` = $manhom";
        $result = mysqli_query($this->con, $sql);
        if (!$result) {
            $valid = false;
        }
        return $valid;
    }

    // Hàm cập nhật sỉ số khi sv tham gia bằng mã mời
    public function updateSiso1($mamoi)
    {
        $result = $this->getIdFromInvitedCode($mamoi);
        $manhom = $result['manhom'];
        $valid = $this->updateSiso($manhom);
        return $valid;
    }

    // Hàm lấy sinh viên ra từ nhóm
    public function getStudentByGroup($group)
    {
        $sql = "SELECT ng.id,ng.hoten,ng.email,ng.ngaythamgia,ng.ngaysinh,ng.gioitinh FROM chitietnhom ctn JOIN nguoidung ng ON ctn.manguoidung=ng.id WHERE ctn.manhom = $group";
        $result = mysqli_query($this->con, $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function kickUser($manhom, $mssv)
    {
        $valid = true;
        $sql = "DELETE FROM `chitietnhom` WHERE `manguoidung` = $mssv AND `manhom` = $manhom";
        $result = mysqli_query($this->con, $sql);
        if (!$result) $valid = false;
        return $valid;
    }

   public function addSV($mssv, $hoten, $password)
{
    // Kiểm tra xem tài khoản đã tồn tại
    $checkSql = "SELECT * FROM `nguoidung` WHERE `id` = ?";
    $stmt = $this->con->prepare($checkSql);
    $stmt->bind_param("s", $mssv);
    $stmt->execute();
    $result = $stmt->get_result();
    if (mysqli_num_rows($result) == 0) {
        // Nếu tài khoản chưa tồn tại, tạo mới (trường hợp dự phòng)
        $password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO `nguoidung`(`id`,`hoten`,`matkhau`,`trangthai`, `manhomquyen`) VALUES (?, ?, ?, '1', '11')";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("sss", $mssv, $hoten, $password);
        if (!$stmt->execute()) {
            error_log("Failed to insert user: " . $stmt->error);
            return false;
        }
    }
    return true;
}

public function checkUserExists($mssv)
{
    $sql = "SELECT * FROM `nguoidung` WHERE `id` = ?";
    $stmt = $this->con->prepare($sql);
    $stmt->bind_param("s", $mssv);
    $stmt->execute();
    $result = $stmt->get_result();
    return mysqli_num_rows($result) > 0;
}
    public function checkAcc($mssv, $manhom)
    {
        $sql_checkGroup = "SELECT * FROM chitietnhom where manhom='$manhom' AND manguoidung='$mssv'";
        $result_checkGroup = mysqli_query($this->con, $sql_checkGroup);
        if ($result_checkGroup->num_rows > 0) {
            return "0";
        }

        $sql_checkNguoiDung = "SELECT * FROM nguoidung where id='$mssv'";
        $result_checkNguoiDung = mysqli_query($this->con, $sql_checkNguoiDung);
        if ($result_checkNguoiDung->num_rows > 0) {
            return "-1";
        }
        return "1";
    }

    public function getGroupSize($id)
    {
        // $sql = "SELECT count(*) FROM chitietnhom WHERE manhom = $id";
        $sql = "SELECT siso from nhom where manhom = $id";
        $result = mysqli_query($this->con, $sql);
        $row = mysqli_fetch_assoc($result);
        // return $row['count(*)'];
        return $row['siso'];
    }

    public function getQuerySortByName($filter, $input, $args, $order)
    {
        $query = "SELECT ND.id, avatar, hoten, email, gioitinh, ngaysinh, SUBSTRING_INDEX(hoten, ' ', -1) AS firstname FROM chitietnhom CTN, nguoidung ND WHERE CTN.manguoidung = ND.id AND CTN.manhom = " . $args['manhom'];
        if ($input) {
            $query .= " AND (ND.hoten LIKE N'%${input}%' OR CTN.manguoidung LIKE N'%${input}%')";
        }
        $query .= " ORDER BY firstname $order";
        return $query;
    }

    public function getQuery($filter, $input, $args)
    {
        $query = "SELECT ND.id, avatar, hoten, email, gioitinh, ngaysinh FROM chitietnhom CTN, nguoidung ND WHERE CTN.manguoidung = ND.id AND CTN.manhom = " . $args['manhom'];
        if ($input) {
            $query .= " AND (ND.hoten LIKE N'%${input}%' OR CTN.manguoidung LIKE N'%${input}%')";
        }
        if (isset($args["custom"]["function"])) {
            $function = $args["custom"]["function"];
            switch ($function) {
                case "sort":
                    $column = $args["custom"]["column"];
                    $order = $args["custom"]["order"];
                    switch ($column) {
                        case "id":
                            $query .= " ORDER BY $column $order";
                            break;
                        case "hoten":
                            $query = $this->getQuerySortByName($filter, $input, $args, $order);
                            break;
                        default:
                    }
                    break;
                default:
            }
        }
        return $query;
    }
}
