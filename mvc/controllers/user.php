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
                echo json_encode(['status' => 'error', 'message' => 'Lỗi không thể đọc file: ' . $e->getMessage()]);
                return;
            }
            $sheet = $objPHPExcel->setActiveSheetIndex(0);
            $Totalrow = $sheet->getHighestRow();
            $LastColumn = $sheet->getHighestColumn();
            $TotalCol = PHPExcel_Cell::columnIndexFromString($LastColumn);
            $data = [];
            for ($i = 3; $i <= $Totalrow; $i++) {
                $mssv = $sheet->getCellByColumnAndRow(1, $i)->getValue();
                $fullname = trim($sheet->getCellByColumnAndRow(2, $i)->getValue() . ' ' . $sheet->getCellByColumnAndRow(3, $i)->getValue());
                $email = $sheet->getCellByColumnAndRow(7, $i)->getValue();
                $gioitinh = $sheet->getCellByColumnAndRow(4, $i)->getValue(); // Giả sử cột 4 là giới tính (0: Nữ, 1: Nam)
                $ngaysinh = $sheet->getCellByColumnAndRow(5, $i)->getValue() ?: '1990-01-01'; // Giả sử cột 5 là ngày sinh
                $sodienthoai = $sheet->getCellByColumnAndRow(6, $i)->getValue(); // Giả sử cột 6 là số điện thoại

                // Chuẩn hóa giới tính
                $gioitinh = ($gioitinh === 'Nam' || $gioitinh == 1) ? 1 : ($gioitinh === 'Nữ' || $gioitinh == 0 ? 0 : null);
                // Chuẩn hóa ngày sinh
                if ($ngaysinh && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $ngaysinh)) {
                    $ngaysinh = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($ngaysinh));
                }

                $data[$i] = [
                    'mssv' => trim($mssv),
                    'fullname' => trim($fullname),
                    'email' => trim($email),
                    'gioitinh' => $gioitinh,
                    'ngaysinh' => $ngaysinh,
                    'sodienthoai' => $sodienthoai ? (int)$sodienthoai : null,
                    'nhomquyen' => 1, // Mặc định là 1
                    'trangthai' => 1
                ];
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