<?php
$data = [];
$data["Title"] = " DHT ONTEST";
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <?php require "inc/head.php" ?>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        .team-card:hover .card-img-top {
            transform: scale(1.1);
            transition: all 0.3s ease;
        }
        .team-card {
            overflow: hidden;
        }
        .btn-custom {
            transition: all 0.3s ease;
        }
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        .footer-gradient {
            background: linear-gradient(90deg, #f7f7f7 0%, #e0e0e0 100%);
        }
        .animated {
            animation: fadeInUp 1s ease-out;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div id="page-container" class="main-content-boxed">
        <!-- Header Bar -->
        <header id="page-header" class="gradient-bg text-white">
            <div class="content-header d-flex justify-content-between align-items-center p-4">
                <div>
                    <a class="fw-bold text-white text-decoration-none" href="home" style="transition: all 0.3s ease;">
                        <i class="fa fa-fire me-2"></i>
                        <span class="fs-4"> DHT<strong class="text-warning">ONTEST</strong></span>
                    </a>
                </div>
                <div>
                    <ul class="nav align-items-center">
                        <li class="nav-item me-2">
                            <a class="btn btn-light rounded-pill btn-custom" onclick="Dashmix.layout('dark_mode_toggle');" href="javascript:void(0)">
                                <i class="fa fa-moon"></i>
                            </a>
                        </li>
                        <?php
                        if (!isset($_COOKIE['token'])) {
                            echo '<li class="nav-item">
                                <a class="btn btn-light rounded-pill btn-custom" href="auth/signin">
                                    <i class="fa fa-right-to-bracket me-1"></i>Đăng nhập
                                </a>
                            </li>';
                        } else {
                            echo '<li class="nav-item">
                                <a class="btn btn-white bg-white text-primary rounded-pill btn-custom" href="dashboard">
                                    <i class="fa fa-rocket me-1"></i>Dashboard
                                </a>
                            </li>';
                        }
?>
                    </ul>
                </div>
            </div>
        </header>
        <!-- END Header Bar -->

        <main id="main-container">
            <!-- Hero Section -->
            <div class="bg-light text-center py-6 position-relative" style="background: linear-gradient(135deg, #f0f4f8 0%, #e0e7f7 100%);">
                <div class="container py-5">
                    <h1 class="fw-bold fs-1 mb-4 text-dark">DHT ONTEST - Hệ thống tạo và quản lý bài thi cá nhân hóa</h1>
                    <p class="lead text-muted mb-5">Hỗ trợ tạo và quản lý ngân hàng câu hỏi, đề thi trắc nghiệm, bài giảng, tổ chức thi online và giao bài tập trên mọi nền tảng.</p>
                    <a class="btn btn-primary btn-lg m-2 btn-custom" href="auth/signin">Tham gia ngay</a>
                    <a class="btn btn-outline-primary btn-lg m-2 btn-custom" href="#section--1">Tìm hiểu thêm</a>
                </div>
            </div>
            <!-- END Hero Section -->

            <!-- Features Section -->
            <div id="section--1" class="py-6 bg-white">
                <div class="container">
                    <div class="text-center mb-5">
                        <h2 class="fw-bold fs-3">Tại sao chọn DHT ONTEST?</h2>
                        <p class="text-muted fs-5">Hệ thống thi cá nhân hóa – thông minh – bảo mật</p>
                    </div>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="card h-100 shadow-sm border-0 text-center p-4 rounded-4 card-hover animated">
                                <div class="mx-auto bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                    <i class="fa fa-cubes fa-2x"></i>
                                </div>
                                <h5 class="fw-semibold">Lưu trạng thái khi gặp sự cố</h5>
                                <p class="text-muted">Kết quả bài thi được bảo vệ an toàn ngay cả khi mất kết nối hoặc lỗi trình duyệt.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 shadow-sm border-0 text-center p-4 rounded-4 card-hover animated" style="animation-delay: 0.2s;">
                                <div class="mx-auto bg-success text-white rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                    <i class="fa fa-code fa-2x"></i>
                                </div>
                                <h5 class="fw-semibold">Tạo đề thi tự động</h5>
                                <p class="text-muted"> DHT ONTEST hỗ trợ giáo viên sinh đề nhanh chóng, đa dạng và bám sát mục tiêu học tập.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 shadow-sm border-0 text-center p-4 rounded-4 card-hover animated" style="animation-delay: 0.4s;">
                                <div class="mx-auto bg-danger text-white rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                    <i class="fa fa-rocket fa-2x"></i>
                                </div>
                                <h5 class="fw-semibold">Phân loại câu hỏi</h5>
                                <p class="text-muted">Hệ thống phân nhóm câu hỏi giúp xây dựng bài thi phù hợp với từng đối tượng học sinh.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Features Section -->

            <!-- Team Section -->
            <div class="bg-light py-6">
                <div class="container">
                    <h2 class="fw-bold fs-3 text-center mb-4">Đội ngũ phát triển</h2>
                    <p class="text-muted text-center mb-5">Chúng tôi là những sinh viên đam mê công nghệ, mong muốn cải tiến giải pháp giáo dục số.</p>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="card h-100 text-center p-4 shadow-sm border-0 rounded-4 team-card animated">
                                <img class="card-img-top rounded-circle mx-auto mb-3" src="https://cdn-icons-png.flaticon.com/512/3781/3781986.png?size=460" alt="Phạm Sơn Hoàng" style="width: 180px; height: 180px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title fw-bold">Phạm Sơn Hoàng</h5>
                                    <p class="card-text text-muted">Mã SV: CNTT2211056</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 text-center p-4 shadow-sm border-0 rounded-4 team-card animated" style="animation-delay: 0.2s;">
                                <img class="card-img-top rounded-circle mx-auto mb-3" src="https://img.freepik.com/vetores-premium/ilustracao-vetorial-plana-de-um-administrador_1033579-56472.jpg?size=460" alt="Nguyễn Thái Dương" style="width: 180px; height: 180px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title fw-bold">Nguyễn Thái Dương</h5>
                                    <p class="card-text text-muted">Mã SV: CNTT2211074</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 text-center p-4 shadow-sm border-0 rounded-4 team-card animated" style="animation-delay: 0.4s;">
                                <img class="card-img-top rounded-circle mx-auto mb-3" src="https://img.freepik.com/vetores-premium/ilustracao-vetorial-plana-de-um-administrador_1033579-56444.jpg?size=460" alt="Liên Hòa Thuận" style="width: 180px; height: 180px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title fw-bold">Liên Hòa Thuận</h5>
                                    <p class="card-text text-muted">Mã SV: CNTT2211008</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Team Section -->
        </main>

        <!-- Footer -->
        <footer id="page-footer" class="footer-gradient pt-5 pb-4 mt-5">
            <div class="container">
                <div class="row text-center text-md-start">
                    <div class="col-md-4 mb-4">
                        <h5 class="fw-bold mb-3 text-dark">Thông tin</h5>
                        <ul class="list-unstyled">
                            <li><a class="text-muted text-decoration-none" href="#">Chính sách bảo mật</a></li>
                            <li><a class="text-muted text-decoration-none" href="#">Điều khoản sử dụng</a></li>
                            <li><a class="text-muted text-decoration-none" href="#">Hướng dẫn</a></li>
                        </ul>
                    </div>
                    <div class="col-md-4 mb-4">
                        <h5 class="fw-bold mb-3 text-dark">Địa chỉ</h5>
                        <p class="text-muted mb-0">256 Nguyễn Văn Cừ, An Hòa, Ninh Kiều, Cần Thơ</p>
                    </div>
                    <div class="col-md-4 mb-4">
                        <h5 class="fw-bold mb-3 text-dark">Kết nối</h5>
                        <a class="text-muted me-3 fs-4" href="https://facebook.com/hgbaodev" target="_blank">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a class="text-muted fs-4" href="#">
                            <i class="fab fa-facebook-messenger"></i>
                        </a>
                    </div>
                </div>
                <hr class="bg-light">
                <div class="text-center pt-3">
                    <small class="text-muted">© <?php echo date('Y'); ?> DHT ONTEST. All rights reserved.</small>
                </div>
            </div>
        </footer>
        <!-- END Footer -->

        <?php require "inc/script.php" ?>
    </div>
</body>
</html>