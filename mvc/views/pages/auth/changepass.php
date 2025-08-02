<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Thay đổi mật khẩu</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; font-family: 'Poppins', sans-serif; }
    body, html { margin: 0; padding: 0; height: 100%; background: linear-gradient(135deg, #00C9A7, #1E90FF); }
    .bg-image { background: linear-gradient(rgba(255,255,255,0.4), rgba(255,255,255,0.4)), url('https://y7b6t9n6.delivery.rocketcdn.me/wp-content/uploads/2024/01/SVG-3.svg') center/cover no-repeat; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
    .form-card { background: white; width: 100%; max-width: 400px; border-radius: 24px; padding: 30px; box-shadow: 0 8px 24px rgba(0,0,0,0.1); animation: fadeInUp 0.6s; text-align: center; }
    @keyframes fadeInUp { from {opacity:0; transform:translateY(20px);} to {opacity:1; transform:translateY(0);} }
    .form-card .logo { font-size: 28px; font-weight: 700; margin-bottom: 10px; }
    .form-card .logo span:first-child { background: #000; color: #fff; padding: 8px 16px; border-radius: 12px; }
    .form-card .logo span:last-child { color: #007bff; margin-left: 8px; }
    .form-card h2 { font-size: 24px; font-weight: 600; margin-bottom: 10px; }
    .form-card p { text-align: center; font-size: 14px; color: #555; margin-bottom: 20px; }
    .form-group { margin-bottom: 15px; position: relative; }
    .form-group input { width: 100%; padding: 12px; border-radius: 12px; border: 1px solid #ddd; font-size: 14px; }
    .form-group input:focus { border-color: #00C9A7; outline: none; }
    .input-icon { position: relative; }
    .input-icon i { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #aaa; }
    .btn-primary { width: 100%; padding: 12px; background: linear-gradient(45deg, #00C9A7, #1E90FF); color: white; font-weight: 600; font-size: 16px; border: none; border-radius: 12px; cursor: pointer; margin-bottom: 15px; transition: 0.3s; }
    .btn-primary:hover { opacity: 0.95; }
    .btn-secondary { display: inline-block; background: #f1f1f1; color: #333; text-decoration: none; width: 100%; text-align: center; padding: 10px; font-weight: 500; border-radius: 12px; font-size: 14px; transition: all 0.3s ease; }
    .btn-secondary:hover { background: #e2e2e2; }
  </style>
</head>
<body>
  <div class="bg-image">
    <div class="form-card">
      <p class="fw-bold fs-2 text-decoration-none">
            <span class="bg-dark text-white px-3 py-1 rounded-3 shadow-sm">DHT</span>
            <span class="text-primary fs-2">OnTest</span>
      </p>
      <h2>Thay đổi mật khẩu</h2>
      <p>Nhập mật khẩu mới và xác nhận</p>
      <form id="changepass" action="" method="">
        <div class="form-group input-icon">
          <input type="password" id="passwordNew" name="passwordNew" placeholder="Mật khẩu mới" required>
        </div>
        <div class="form-group input-icon">
          <input type="password" id="comfirm" name="comfirm" placeholder="Xác nhận mật khẩu" required>
        </div>
        <button id="btnChange"type="submit" class="btn-primary">Cập nhật</button>
        <a href="/Quanlythitracnghiem/auth/signin" class="btn-secondary">← Quay lại đăng nhập</a>
      </form>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>
</body>
</html>