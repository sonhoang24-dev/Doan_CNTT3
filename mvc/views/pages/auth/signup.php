<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$form_data = $_SESSION['form_data'] ?? [];
$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>DHT OnTest - Tạo tài khoản</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<style>
    * { box-sizing: border-box; font-family: 'Poppins', sans-serif; }
    body, html { margin: 0; padding: 0; height: 100%; background: linear-gradient(135deg, #00C9A7, #1E90FF); }
    .bg-image { background: linear-gradient(rgba(255,255,255,0.2), rgba(255,255,255,0.2)), url('https://y7b6t9n6.delivery.rocketcdn.me/wp-content/uploads/2024/01/SVG-3.svg') center/cover no-repeat; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
    .form-card { background: white; width: 100%; max-width: 600px; border-radius: 24px; padding: 40px 30px; box-shadow: 0 8px 24px rgba(0,0,0,0.1); animation: fadeInUp 0.6s; }
    @keyframes fadeInUp { from {opacity:0; transform:translateY(20px);} to {opacity:1; transform:translateY(0);} }
    .form-card h1 { font-size: 28px; font-weight: 700; text-align: center; }
    .form-card h1 span { color: #1E90FF; }
    .form-card p { text-align: center; font-size: 15px; color: #555; margin-bottom: 30px; }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px; }
    .form-grid input, .form-grid select { width: 100%; padding: 14px; border-radius: 12px; border: 1px solid #ddd; font-size: 15px; }
    .form-grid input:focus, .form-grid select:focus { border-color: #00C9A7; outline: none; }
    .checkbox-container { display: flex; justify-content: space-between; align-items: center; font-size: 14px; margin-bottom: 20px; padding: 10px; background: #f9f9f9; border-radius: 12px; }
    .checkbox-container a { color: #1E90FF; text-decoration: none; }
    .checkbox-container a:hover { text-decoration: underline; }
    .btn-primary { width: 100%; padding: 14px; background: linear-gradient(45deg, #00C9A7, #1E90FF); color: white; font-weight: 600; font-size: 16px; border: none; border-radius: 12px; cursor: pointer; margin-bottom: 15px; transition: 0.3s; }
    .btn-primary:hover { opacity: 0.95; }
    .btn-secondary { display: inline-block; background: #f1f1f1; color: #333; text-decoration: none; width: 100%; text-align: center; padding: 12px; font-weight: 500; border-radius: 12px; font-size: 14px; transition: all 0.3s ease; }
    .btn-secondary:hover { background: #e2e2e2; }
    .message { padding: 12px; margin-bottom: 15px; border-radius: 12px; font-weight: 500; }
    .success { background: #e5ffe5; color: #28a745; }
    .error { background: #ffe5e5; color: #ff0000; }
    .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; z-index: 1000; }
    .modal-content { background: white; max-width: 500px; width: 90%; padding: 20px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.2); max-height: 80vh; overflow-y: auto; }
    .modal-content h2 { font-size: 20px; margin-bottom: 15px; }
    .modal-content p { font-size: 14px; color: #333; margin-bottom: 15px; }
    .modal-content button { padding: 10px 20px; background: #1E90FF; color: white; border: none; border-radius: 8px; cursor: pointer; }
    .modal-content button:hover { background: #00C9A7; }
    @media (max-width: 768px) { .form-grid { grid-template-columns: 1fr; } }
</style>
</head>
<body>

<div class="bg-image">
    <div class="form-card">
        <h1>DHT<span>OnTest</span></h1>
        <p>Tạo tài khoản mới</p>

        <?php if ($error): ?>
            <div class="message error"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="message success" style="text-align:center; margin: 20px 0;">
                <?= htmlspecialchars($success); ?><br>
                Chuyển trang sau <span id="countdown">5</span> giây.
            </div>
            <script>
                let seconds = 5;
                setInterval(function(){
                    if (seconds > 1) {
                        seconds--;
                        document.getElementById('countdown').textContent = seconds;
                    } else {
                        window.location.href = "./auth/signin";
                    }
                }, 1000);
            </script>
        <?php endif; ?>

        <form id="signupForm" method="POST" action="/Quanlythitracnghiem/user/add">
            <div class="form-grid">
                <input type="text" name="masinhvien" placeholder="Mã sinh viên" value="<?= htmlspecialchars($form_data['masinhvien'] ?? '') ?>" required>
                <input type="email" name="email" placeholder="Địa chỉ Email" value="<?= htmlspecialchars($form_data['email'] ?? '') ?>" required>
                <input type="text" name="hoten" placeholder="Họ và tên" value="<?= htmlspecialchars($form_data['hoten'] ?? '') ?>" required>
                <input type="date" name="ngaysinh" value="<?= htmlspecialchars($form_data['ngaysinh'] ?? '') ?>" required>
                <select name="gioitinh" required>
                    <option value="" <?= empty($form_data['gioitinh']) ? 'selected' : '' ?> disabled>Giới tính</option>
                    <option value="0" <?= (isset($form_data['gioitinh']) && $form_data['gioitinh'] == '0') ? 'selected' : '' ?>>Nam</option>
                    <option value="1" <?= (isset($form_data['gioitinh']) && $form_data['gioitinh'] == '1') ? 'selected' : '' ?>>Nữ</option>
                </select>
                <input type="text" name="sodienthoai" placeholder="Số điện thoại" value="<?= htmlspecialchars($form_data['sodienthoai'] ?? '') ?>" required>
                <input type="password" name="password" placeholder="Mật khẩu" required>
                <input type="password" name="confirm_password" placeholder="Nhập lại mật khẩu" required>
            </div>
            <div class="checkbox-container">
                <label><input type="checkbox" required> Tôi đồng ý với <a href="#" onclick="document.getElementById('termsModal').style.display='flex'; return false;">chính sách và điều khoản</a></label>
            </div>
            <button type="submit" class="btn-primary">Tạo tài khoản</button>
            <a href="./auth/signin" class="btn-secondary">Quay lại đăng nhập</a>
        </form>

        <!-- Terms Modal -->
        <div id="termsModal" class="modal">
            <div class="modal-content">
                <h2>Điều khoản và Chính sách</h2>
                <div class="modal-body">
                    <p><strong>1. Chấp nhận Điều khoản</strong><br>
                        Bằng việc sử dụng dịch vụ của DHT OnTest, bạn đồng ý tuân thủ các điều khoản và điều kiện được nêu trong tài liệu này. Nếu bạn không đồng ý, vui lòng không sử dụng dịch vụ.</p>
                    <p><strong>2. Quyền và Nghĩa vụ</strong><br>
                        - Bạn cam kết cung cấp thông tin chính xác và đầy đủ khi đăng ký tài khoản.<br>
                        - Bạn chịu trách nhiệm bảo mật thông tin tài khoản và mật khẩu của mình.<br>
                        - DHT OnTest có quyền tạm ngưng hoặc hủy tài khoản nếu phát hiện hành vi vi phạm điều khoản.</p>
                    <p><strong>3. Quyền riêng tư</strong><br>
                        Thông tin cá nhân của bạn sẽ được bảo vệ theo Chính sách Bảo mật của chúng tôi. Chúng tôi chỉ thu thập và sử dụng thông tin theo mục đích cung cấp dịch vụ và tuân thủ pháp luật.</p>
                    <p><strong>4. Giới hạn trách nhiệm</strong><br>
                        DHT OnTest không chịu trách nhiệm cho bất kỳ thiệt hại nào phát sinh từ việc sử dụng dịch vụ, bao gồm nhưng không giới hạn ở mất dữ liệu hoặc gián đoạn dịch vụ.</p>
                    <p><strong>5. Thay đổi Điều khoản</strong><br>
                        DHT OnTest có quyền cập nhật hoặc thay đổi điều khoản này bất kỳ lúc nào. Các thay đổi sẽ được thông báo qua email hoặc trên nền tảng.</p>
                </div>
                <div class="modal-footer">
                    <button class="close-btn" onclick="document.getElementById('termsModal').style.display='none'">Đóng</button>
                </div>
            </div>
        </div>

        <!-- Error Modal -->
        <div id="errorModal" class="modal">
            <div class="modal-content">
                <h2>Lỗi Đăng Ký</h2>
                <div class="modal-body">
                    <p id="errorMessage" class="error"></p>
                </div>
                <div class="modal-footer">
                    <button class="close-btn" onclick="document.getElementById('errorModal').style.display='none'">Đóng</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('signupForm').addEventListener('submit', async function(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        const email = form.email.value;
        const ngaysinh = form.ngaysinh.value;
        const password = form.password.value;
        const confirmPassword = form.confirm_password.value;

        // Client-side validation
        const today = new Date();
        const dob = new Date(ngaysinh);
        const age = today.getFullYear() - dob.getFullYear();
        const monthDiff = today.getMonth() - dob.getMonth();
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
            age--;
        }

        if (!email.endsWith('@gmail.com')) {
            document.getElementById('errorMessage').textContent = 'Email phải là địa chỉ @gmail.com';
            document.getElementById('errorModal').style.display = 'flex';
            return;
        }

        if (dob > today) {
            document.getElementById('errorMessage').textContent = 'Ngày sinh không được là ngày trong tương lai';
            document.getElementById('errorModal').style.display = 'flex';
            return;
        }

        if (age < 16) {
            document.getElementById('errorMessage').textContent = 'Bạn phải ít nhất 16 tuổi để đăng ký';
            document.getElementById('errorModal').style.display = 'flex';
            return;
        }

        if (password !== confirmPassword) {
            document.getElementById('errorMessage').textContent = 'Mật khẩu và xác nhận mật khẩu không khớp';
            document.getElementById('errorModal').style.display = 'flex';
            return;
        }

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json'
                }
            });
            const result = await response.json();
            
            if (result.status === 'success') {
                const successDiv = document.createElement('div');
                successDiv.className = 'message success';
                successDiv.style.textAlign = 'center';
                successDiv.style.margin = '20px 0';
                successDiv.innerHTML = `${result.message}<br>Chuyển trang sau <span id="countdown">5</span> giây.`;
                form.parentElement.insertBefore(successDiv, form);
                form.style.display = 'none';
                
                let seconds = 5;
                const countdown = setInterval(function() {
                    if (seconds > 1) {
                        seconds--;
                        document.getElementById('countdown').textContent = seconds;
                    } else {
                        window.location.href = './auth/signin';
                        clearInterval(countdown);
                    }
                }, 1000);
            } else {
                document.getElementById('errorMessage').textContent = result.message;
                document.getElementById('errorModal').style.display = 'flex';
            }
        } catch (error) {
            document.getElementById('errorMessage').textContent = 'Đã xảy ra lỗi khi gửi yêu cầu. Vui lòng thử lại.';
            document.getElementById('errorModal').style.display = 'flex';
        }
    });
</script>

</body>
</html>

<?php unset($_SESSION['error'], $_SESSION['success'], $_SESSION['form_data']); ?>