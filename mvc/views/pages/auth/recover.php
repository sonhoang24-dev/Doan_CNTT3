<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OnTest DHT - Quên mật khẩu</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            background: linear-gradient(135deg, #00C9A7, #1E90FF);
        }
        .bg-image {
            background: linear-gradient(rgba(255,255,255,0.2), rgba(255,255,255,0.2)), 
                        url('https://y7b6t9n6.delivery.rocketcdn.me/wp-content/uploads/2024/01/SVG-3.svg') center/cover no-repeat;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .form-card {
            background: white;
            width: 100%;
            max-width: 420px;
            border-radius: 24px;
            padding: 40px 30px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            text-align: center;
        }
        .form-card h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .form-card h1 span {
            color: #1E90FF;
        }
        .form-card p {
            font-size: 15px;
            color: #555;
            margin-bottom: 30px;
        }
        .form-card input[type="email"] {
            width: 100%;
            padding: 14px;
            margin-bottom: 20px;
            border-radius: 12px;
            border: 1px solid #ddd;
            font-size: 15px;
        }
        .form-card input:focus {
            border-color: #00C9A7;
            outline: none;
        }
        .btn-primary {
            width: 100%;
            padding: 14px;
            background: linear-gradient(45deg, #00C9A7, #1E90FF);
            border: none;
            border-radius: 12px;
            font-weight: 600;
            color: white;
            font-size: 16px;
            cursor: pointer;
            margin-bottom: 15px;
            transition: 0.3s;
        }
        .btn-primary:hover {
            opacity: 0.95;
        }
        .btn-secondary {
            display: inline-block;
            background: #f1f1f1;
            color: #333;
            text-decoration: none;
            padding: 12px 20px;
            font-weight: 500;
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        .btn-secondary:hover {
            background: #e2e2e2;
        }
    </style>
</head>
<body>

<div class="bg-image">
    <div class="form-card">
        <h1>OnTest<span>DHT</span></h1>
        <p>Quên mật khẩu? Nhập email để khôi phục</p>

        <form method="POST">
            <input type="email" name="reminder-credential" placeholder="Nhập email của bạn" required>
            <button type="submit" class="btn-primary">Khôi phục mật khẩu</button>
        </form>

        <a href="auth/signin" class="btn-secondary">← Quay lại đăng nhập</a>
    </div>
</div>

</body>
</html>
