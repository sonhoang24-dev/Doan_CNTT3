<?php

class AnnouncementModel extends DB
{
    public function create($mamonhoc, $thoigiantao, $nguoitao, $nhom, $content)
    {
        $sql = "INSERT INTO `thongbao`(`noidung`,`thoigiantao`,`nguoitao`) VALUES ('$content','$thoigiantao','$nguoitao')";
        $result = mysqli_query($this->con, $sql);
        if ($result) {
            $matb = mysqli_insert_id($this->con);
            // Một thông báo gửi cho nhiều nhóm
            $result = $this->sendAnnouncement($matb, $nhom);
            return $matb;
        } else {
            return false;
        }
    }

    public function getById($matb)
    {
        $sql = "SELECT * FROM thongbao WHERE matb = '$matb'";
        $result = mysqli_query($this->con, $sql);
        return mysqli_fetch_assoc($result);
    }
    public function sendAnnouncement($matb, $nhom)
    {
        $valid = true;

        foreach ($nhom as $manhom) {
            $sql = "INSERT INTO `chitietthongbao`(`matb`, `manhom`) VALUES ('$matb','$manhom')";
            $result = mysqli_query($this->con, $sql);
            if (!$result) {
                $valid = false;
                continue;
            }

            $queryMembers = "SELECT manguoidung FROM chitietnhom WHERE manhom = $manhom";
            $resMembers = mysqli_query($this->con, $queryMembers);

            while ($row = mysqli_fetch_assoc($resMembers)) {
                $user = $row['manguoidung'];
                $insertStatus = "INSERT INTO trangthaithongbao (matb, manguoidung, trangthai)
                             VALUES ('$matb', '$user', 'chưa xem')";
                mysqli_query($this->con, $insertStatus);
            }
        }

        return $valid;
    }
    public function markAllAsRead($user_id)
    {
        $sql = "UPDATE trangthaithongbao 
            SET trangthai = 'đã xem' 
            WHERE manguoidung = '$user_id' AND trangthai = 'chưa xem'";
        return mysqli_query($this->con, $sql);
    }
    public function countUnread($user_id)
    {
        $sql = "SELECT COUNT(*) AS count FROM trangthaithongbao 
            WHERE manguoidung = '$user_id' AND trangthai = 'chưa xem'";
        $result = mysqli_query($this->con, $sql);
        $row = mysqli_fetch_assoc($result);
        return $row['count'] ?? 0;
    }






    public function getAnnounce($manhom)
    {
        $sql = "SELECT DISTINCT `thongbao`.`matb`, `noidung`, `avatar` ,`thoigiantao`
        FROM `thongbao`,`chitietthongbao`,`chitietnhom`,`nguoidung` 
        WHERE `thongbao`.`matb` = `chitietthongbao`.`matb` AND `chitietthongbao`.`manhom` = `chitietnhom`.`manhom` AND `nguoitao` = `id`
        AND `chitietthongbao`.`manhom` = $manhom ORDER BY thoigiantao DESC";
        $result = mysqli_query($this->con, $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }



    public function getAll($user_id)
    {
        $sql = "SELECT `chitietthongbao`.`matb`,`tennhom`,`noidung`, `tenmonhoc` ,`namhoc`, `hocky`, `thoigiantao`
        FROM `thongbao`, `chitietthongbao`,`nhom`,`monhoc` 
        WHERE `thongbao`.`matb` = `chitietthongbao`.`matb` AND `chitietthongbao`.`manhom` = `nhom`.`manhom` AND `nhom`.`mamonhoc` = `monhoc`.`mamonhoc`
        AND `thongbao`.`nguoitao` = $user_id ORDER BY thoigiantao DESC";
        $result = mysqli_query($this->con, $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $matb = $row['matb'];
            $index = array_search($matb, array_column($rows, 'matb'));
            if ($index === false) {
                $item = [
                    "matb" => $matb,
                    "noidung" => $row['noidung'],
                    "tenmonhoc" => $row['tenmonhoc'],
                    "namhoc" => $row['namhoc'],
                    "hocky" => $row['hocky'],
                    "thoigiantao" => $row['thoigiantao'],
                    "nhom" => [$row['tennhom']]
                ];
                array_push($rows, $item);
            } else {
                array_push($rows[$index]["nhom"], $row['tennhom']);
            }
        }
        return $rows;
    }

    public function deleteAnnounce($matb)
    {
        $result = $this->deleteDetailAnnounce($matb);
        if ($result) {
            $sql = "DELETE FROM `thongbao` WHERE `matb` = $matb";
            $result = mysqli_query($this->con, $sql);
            return true;
        } else {
            return false;
        }
    }


    // Xóa thông báo trong bảng thongbao
    public function deleteDetailAnnounce($matb)
    {
        $valid = true;
        $sql = "DELETE FROM `chitietthongbao` WHERE `matb` = $matb";
        $result = mysqli_query($this->con, $sql);
        if (!$result) {
            $valid = false;
        }
        return $valid;
    }
    public function autoAnnounceNewExam($made, $tende, $monthi, $thoigiantao, $nguoitao)
    {
        $content = "Đề thi mới: <strong>$tende</strong> – Môn <em>$monthi</em>";

        $sql = "SELECT DISTINCT manhom FROM giaodethi WHERE made = '$made'";
        $result = mysqli_query($this->con, $sql);
        $nhom = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $nhom[] = $row['manhom'];
        }

        if (empty($nhom)) {
            return false;
        }

        $sql = "INSERT INTO thongbao(noidung, thoigiantao, nguoitao) 
            VALUES ('$content', '$thoigiantao', '$nguoitao')";
        $res = mysqli_query($this->con, $sql);
        if (!$res) {
            return false;
        }

        $matb = mysqli_insert_id($this->con);

        $this->sendAnnouncement($matb, $nhom);

        return true;
    }


    public function getDetail($matb)
    {
        $sql_announce = "SELECT `thongbao`.`matb`,`noidung`, `tenmonhoc` ,`namhoc`, `hocky` 
        FROM `thongbao`, `chitietthongbao`,`nhom`,`monhoc` 
        WHERE `thongbao`.`matb` = `chitietthongbao`.`matb` AND `chitietthongbao`.`manhom` = `nhom`.`manhom` AND `nhom`.`mamonhoc` = `monhoc`.`mamonhoc`
        AND `thongbao`.`matb` = $matb";
        $result_announce = mysqli_query($this->con, $sql_announce);
        $thongbao = mysqli_fetch_assoc($result_announce);
        if ($thongbao != null) {
            $sql_sendAnnounce = "SELECT `manhom` FROM `chitietthongbao` WHERE `matb` = $matb";
            $result_sendAnnounce = mysqli_query($this->con, $sql_sendAnnounce);
            $thongbao['nhom'] = array();
            while ($row = mysqli_fetch_assoc($result_sendAnnounce)) {
                $thongbao['nhom'][] = $row['manhom'];
            }
        }
        return $thongbao;
    }

    public function updateAnnounce($matb, $noidung, $nhom)
    {
        $valid = true;
        $sql = "UPDATE `thongbao` SET `noidung`='$noidung' WHERE `matb` = $matb" ;
        $result = mysqli_query($this->con, $sql);
        if ($result) {
            $this->deleteDetailAnnounce($matb);
            $this->sendAnnouncement($matb, $nhom);
        } else {
            $valid = false;
        }
        return $valid;
    }

    public function getNotifications($id)
    {
        $sql = "SELECT `tennhom`,`avatar`,`hoten`,`noidung`, `thoigiantao` ,`chitietnhom`.`manhom` , monhoc.mamonhoc, monhoc.tenmonhoc
        FROM `thongbao`,`chitietthongbao`,`chitietnhom`, `nguoidung`,`nhom` ,`monhoc`
        WHERE `thongbao`.`matb` = `chitietthongbao`.`matb` AND `chitietthongbao`.`manhom` = `chitietnhom`.`manhom` 
        AND `thongbao`.`nguoitao` = `nguoidung`.`id` 
        AND `chitietnhom`.`manhom` = `nhom`.`manhom`
        AND `monhoc`.`mamonhoc` = `nhom`.`mamonhoc`
        AND `chitietnhom`.`manguoidung` = '$id'
        ORDER BY thoigiantao DESC LIMIT 0, 5";
        $result = mysqli_query($this->con, $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }


    public function getQuery($filter, $input, $args)
    {
        $query = "SELECT TB.*, MH.tenmonhoc, N.namhoc, N.hocky, 
                         GROUP_CONCAT(N.tennhom SEPARATOR ', ') AS nhom 
                  FROM thongbao TB
                  JOIN chitietthongbao CTTB ON TB.matb = CTTB.matb
                  JOIN nhom N ON CTTB.manhom = N.manhom
                  JOIN monhoc MH ON N.mamonhoc = MH.mamonhoc
                  WHERE TB.nguoitao = ?";
        $params = ['s', $args['id']];

        if ($input) {
            $query .= " AND TB.noidung LIKE ?";
            $params[0] .= 's';
            $params[] = "%$input%";
        }

        if (isset($filter)) {
            if (isset($filter['mamonhoc'])) {
                $query .= " AND MH.mamonhoc = ?";
                $params[0] .= 's';
                $params[] = $filter['mamonhoc'];
            }
            if (isset($filter['namhoc'])) {
                $query .= " AND N.namhoc = ?";
                $params[0] .= 's';
                $params[] = $filter['namhoc'];
            }
            if (isset($filter['hocky'])) {
                $query .= " AND N.hocky = ?";
                $params[0] .= 's';
                $params[] = $filter['hocky'];
            }
        }

        $query .= " GROUP BY TB.matb ORDER BY TB.thoigiantao DESC";

        return ['query' => $query, 'params' => $params];
    }

}
