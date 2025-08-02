<?php

class User extends Controller
{
    public $NguoiDungModel;

    public function __construct()
    {
        $this->NguoiDungModel = $this->model("NguoiDungModel");
        parent::__construct();
        require_once "./mvc/core/Pagination.php";
    }

    public function default()
    {
        if (AuthCore::checkPermission("nguoidung", "view")) {
            $this->view("main_layout", [
                "Page" => "user",
                "Title" => "Quản lý người dùng",
                "Script" => "user",
                "Plugin" => [
                    "sweetalert2" => 1,
                    "datepicker" => 1,
                    "flatpickr" => 1,
                    "notify" => 1,
                    "jquery-validate" => 1,
                    "select" => 1,
                    "pagination" => [],
                ],
                "Roles" => $this->NguoiDungModel->getAllRoles(),
            ]);
        } else {
            $this->view("single_layout", ["Page" => "error/page_403", "Title" => "Lỗi !"]);
        }
    }

    public function add()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id = $_POST['masinhvien'] ?? '';
            $email = $_POST['email'] ?? '';
            $hoten = $_POST['hoten'] ?? '';
            $ngaysinh = $_POST['ngaysinh'] ?? '1990-01-01';
            $gioitinh = $_POST['gioitinh'] ?? null;
            $sodienthoai = $_POST['sodienthoai'] ?? null;
            $password = $_POST['password'] ?? '';
            $nhomquyen = $_POST['role'] ?? 1; // Mặc định là 1
            $trangthai = $_POST['status'] ?? 1; // Mặc định là 1

            // Kiểm tra dữ liệu đầu vào
            if (empty($id) || empty($email) || empty($hoten) || empty($password)) {
                echo json_encode(['status' => 'error', 'message' => 'Vui lòng điền đầy đủ thông tin']);
                return;
            }

            // Kiểm tra định dạng email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['status' => 'error', 'message' => 'Email không hợp lệ']);
                return;
            }

            // Kiểm tra độ dài mật khẩu
            if (strlen($password) < 6) {
                echo json_encode(['status' => 'error', 'message' => 'Mật khẩu phải có ít nhất 6 ký tự']);
                return;
            }

            // Kiểm tra email hoặc mã sinh viên đã tồn tại
            if ($this->NguoiDungModel->getByEmail($email)) {
                echo json_encode(['status' => 'error', 'message' => 'Email đã được sử dụng']);
                return;
            }
            if ($this->NguoiDungModel->getById($id)) {
                echo json_encode(['status' => 'error', 'message' => 'Mã sinh viên đã được sử dụng']);
                return;
            }

            $result = $this->NguoiDungModel->create($id, $email, $hoten, $gioitinh, $ngaysinh, $sodienthoai, $password);
            echo json_encode([
                'status' => $result ? 'success' : 'error',
                'message' => $result ? 'Thêm người dùng thành công' : 'Thêm người dùng thất bại'
            ]);
        }
    }

    public function checkUser()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id = $_POST['mssv'] ?? '';
            $email = $_POST['email'] ?? '';
            $result = $this->NguoiDungModel->checkUser($id, $email);
            echo json_encode($result);
        }
    }

    public function getData()
    {
        $data = $this->NguoiDungModel->getAll();
        echo json_encode($data);
    }

    public function deleteData()
    {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $result = $this->NguoiDungModel->delete($id);
            echo json_encode([
                'status' => $result ? 'success' : 'error',
                'message' => $result ? 'Xóa người dùng thành công' : 'Xóa người dùng thất bại'
            ]);
        }
    }

    public function update()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id = $_POST['id'] ?? '';
            $email = $_POST['email'] ?? '';
            $hoten = $_POST['hoten'] ?? '';
            $ngaysinh = $_POST['ngaysinh'] ?? '1990-01-01';
            $gioitinh = $_POST['gioitinh'] ?? null;
            $sodienthoai = $_POST['sodienthoai'] ?? null;
            $password = $_POST['password'] ?? '';
            $nhomquyen = $_POST['role'] ?? 1;
            $trangthai = $_POST['status'] ?? 1;

            // Kiểm tra dữ liệu đầu vào
            if (empty($id) || empty($email) || empty($hoten)) {
                echo json_encode(['status' => 'error', 'message' => 'Vui lòng điền đầy đủ thông tin']);
                return;
            }

            // Kiểm tra định dạng email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['status' => 'error', 'message' => 'Email không hợp lệ']);
                return;
            }

            // Kiểm tra email đã tồn tại (trừ người dùng hiện tại)
            $existingUser = $this->NguoiDungModel->getByEmail($email);
            if ($existingUser && $existingUser['id'] !== $id) {
                echo json_encode(['status' => 'error', 'message' => 'Email đã được sử dụng']);
                return;
            }

            $result = $this->NguoiDungModel->update($id, $email, $hoten, $gioitinh, $ngaysinh, $sodienthoai, $password, $trangthai, $nhomquyen);
            echo json_encode([
                'status' => $result ? 'success' : 'error',
                'message' => $result ? 'Cập nhật người dùng thành công' : 'Cập nhật người dùng thất bại'
            ]);
        }
    }

    public function getDetail()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $result = $this->NguoiDungModel->getById($_POST['id']);
            echo json_encode($result);
        }
    }

    public function addExcel()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            require_once 'vendor/autoload.php';
            $inputFileName = $_FILES["fileToUpload"]["tmp_name"];
            try {
                $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFileName);
            } catch (Exception $e) {
                die('Lỗi không thể đọc file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
            }
            $sheet = $objPHPExcel->setActiveSheetIndex(0);
            $Totalrow = $sheet->getHighestRow();
            $LastColumn = $sheet->getHighestColumn();
            $TotalCol = PHPExcel_Cell::columnIndexFromString($LastColumn);
            $data = [];
            for ($i = 3; $i <= $Totalrow; $i++) {
                $fullname = "";
                $email = "";
                $mssv = "";
                for ($j = 0; $j < $TotalCol; $j++) {
                    if ($j == 1) {
                        $mssv = $sheet->getCellByColumnAndRow($j, $i)->getValue();
                    }
                    if ($j == 2) {
                        $fullname .= $sheet->getCellByColumnAndRow($j, $i)->getValue();
                    }
                    if ($j == 3) {
                        $fullname .= $sheet->getCellByColumnAndRow($j, $i)->getValue();
                    }
                    if ($j == 7) {
                        $email = $sheet->getCellByColumnAndRow($j, $i)->getValue();
                    }
                }
                $data[$i]['fullname'] = trim($fullname);
                $data[$i]['email'] = trim($email);
                $data[$i]['mssv'] = trim($mssv);
                $data[$i]['nhomquyen'] = 11;
                $data[$i]['trangthai'] = 1;
            }
            echo json_encode($data);
        }
    }

    public function addFileExcel()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $listUser = json_decode($_POST['listuser'], true);
            $password = $_POST['password'] ?? '';
            if (empty($listUser) || empty($password)) {
                echo json_encode(['status' => 'error', 'message' => 'Dữ liệu hoặc mật khẩu không hợp lệ']);
                return;
            }
            $result = $this->NguoiDungModel->addFile($listUser, $password);
            echo json_encode([
                'status' => $result ? 'success' : 'error',
                'message' => $result ? 'Thêm người dùng từ file thành công' : 'Thêm người dùng từ file thất bại'
            ]);
        }
    }

    public function addFileExcelGroup()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $listUser = json_decode($_POST['listuser'], true);
            $password = $_POST['password'] ?? '';
            $manhom = $_POST['group'] ?? '';
            if (empty($listUser) || empty($password) || empty($manhom)) {
                echo json_encode(['status' => 'error', 'message' => 'Dữ liệu, mật khẩu hoặc nhóm không hợp lệ']);
                return;
            }
            $result = $this->NguoiDungModel->addFileGroup($listUser, $password, $manhom);
            echo json_encode([
                'status' => $result ? 'success' : 'error',
                'message' => $result ? 'Thêm người dùng và nhóm từ file thành công' : 'Thêm người dùng và nhóm từ file thất bại'
            ]);
        }
    }

    public function getQuery($filter, $input, $args)
    {
        $query = $this->NguoiDungModel->getQuery($filter, $input, $args);
        return $query;
    }
}
