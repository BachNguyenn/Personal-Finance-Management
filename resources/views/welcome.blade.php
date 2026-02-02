<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
        content="Quản lý tài chính cá nhân thông minh - Theo dõi thu chi, lập ngân sách và đạt mục tiêu tiết kiệm của bạn">

    <title>{{ config('app.name', 'Personal Finance') }} - Quản lý Tài chính Thông minh</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --primary: #059669;
            --primary-dark: #047857;
            --primary-light: #10b981;
            --secondary: #0d9488;
            --accent: #14b8a6;
            --dark: #0f172a;
            --dark-lighter: #1e293b;
            --light: #f8fafc;
            --gray: #64748b;
            --gradient-start: #059669;
            --gradient-end: #0d9488;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: var(--dark);
            background: var(--light);
            overflow-x: hidden;
        }

        /* Navigation */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 1rem 2rem;
            transition: all 0.3s ease;
            background: transparent;
        }

        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        }

        .navbar-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
            transition: color 0.3s;
        }

        .navbar.scrolled .logo {
            color: var(--primary);
        }

        .logo i {
            font-size: 1.75rem;
        }

        .nav-links {
            display: flex;
            gap: 1rem;
        }

        .nav-link {
            padding: 0.625rem 1.25rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .nav-link-light {
            color: rgba(255, 255, 255, 0.9);
        }

        .nav-link-light:hover {
            background: rgba(255, 255, 255, 0.15);
            color: white;
        }

        .navbar.scrolled .nav-link-light {
            color: var(--gray);
        }

        .navbar.scrolled .nav-link-light:hover {
            color: var(--primary);
            background: rgba(5, 150, 105, 0.1);
        }

        .nav-link-primary {
            background: white;
            color: var(--primary);
        }

        .nav-link-primary:hover {
            background: var(--light);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .navbar.scrolled .nav-link-primary {
            background: var(--primary);
            color: white;
        }

        .navbar.scrolled .nav-link-primary:hover {
            background: var(--primary-dark);
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 50%, #0891b2 100%);
            position: relative;
            overflow: hidden;
            padding: 6rem 2rem 4rem;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .hero-content {
            max-width: 800px;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-size: 0.875rem;
            color: white;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .hero h1 {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 800;
            color: white;
            margin-bottom: 1.5rem;
            line-height: 1.1;
        }

        .hero p {
            font-size: 1.25rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2.5rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem 2rem;
            border-radius: 0.75rem;
            font-weight: 600;
            font-size: 1rem;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-white {
            background: white;
            color: var(--primary);
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
        }

        .btn-white:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .btn-outline {
            background: transparent;
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.5);
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: white;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 14px rgba(5, 150, 105, 0.4);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(5, 150, 105, 0.5);
        }

        /* Floating Shapes */
        .floating-shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 6s ease-in-out infinite;
        }

        .shape-1 {
            width: 300px;
            height: 300px;
            top: -100px;
            right: -100px;
            animation-delay: 0s;
        }

        .shape-2 {
            width: 200px;
            height: 200px;
            bottom: 10%;
            left: -50px;
            animation-delay: 2s;
        }

        .shape-3 {
            width: 150px;
            height: 150px;
            top: 40%;
            right: 10%;
            animation-delay: 4s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(5deg);
            }
        }

        /* Features Section */
        .features {
            padding: 6rem 2rem;
            background: white;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-badge {
            display: inline-block;
            background: linear-gradient(135deg, rgba(5, 150, 105, 0.1), rgba(13, 148, 136, 0.1));
            color: var(--primary);
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .section-title {
            font-size: clamp(2rem, 4vw, 2.75rem);
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 1rem;
        }

        .section-subtitle {
            font-size: 1.125rem;
            color: var(--gray);
            max-width: 600px;
            margin: 0 auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            background: var(--light);
            border-radius: 1.25rem;
            padding: 2rem;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
            opacity: 0;
            transition: opacity 0.3s;
        }

        .feature-card:hover::before {
            opacity: 1;
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            margin-bottom: 1.25rem;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
        }

        .feature-card h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.75rem;
        }

        .feature-card p {
            color: var(--gray);
            font-size: 0.95rem;
        }

        /* Benefits Section */
        .benefits {
            padding: 6rem 2rem;
            background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
        }

        .benefits-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .benefits-text h2 {
            font-size: clamp(2rem, 4vw, 2.5rem);
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 1.5rem;
        }

        .benefits-text p {
            color: var(--gray);
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }

        .benefit-list {
            list-style: none;
        }

        .benefit-list li {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.25rem;
        }

        .benefit-list i {
            color: var(--primary);
            font-size: 1.25rem;
            margin-top: 0.2rem;
        }

        .benefit-list strong {
            display: block;
            color: var(--dark);
            margin-bottom: 0.25rem;
        }

        .benefit-list span {
            color: var(--gray);
            font-size: 0.9rem;
        }

        .benefits-visual {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }

        .stat-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card i {
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: 0.75rem;
        }

        .stat-card h4 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.25rem;
        }

        .stat-card p {
            color: var(--gray);
            font-size: 0.875rem;
        }

        /* CTA Section */
        .cta {
            padding: 6rem 2rem;
            background: linear-gradient(135deg, var(--dark) 0%, var(--dark-lighter) 100%);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .cta::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.02'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .cta-content {
            position: relative;
            z-index: 1;
            max-width: 700px;
            margin: 0 auto;
        }

        .cta h2 {
            font-size: clamp(2rem, 4vw, 2.75rem);
            font-weight: 700;
            color: white;
            margin-bottom: 1.25rem;
        }

        .cta p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 1.125rem;
            margin-bottom: 2.5rem;
        }

        /* Footer */
        .footer {
            padding: 3rem 2rem;
            background: var(--dark);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: white;
            font-weight: 600;
            font-size: 1.125rem;
        }

        .footer-logo i {
            color: var(--primary-light);
            font-size: 1.5rem;
        }

        .footer-copy {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.9rem;
        }

        .footer-links {
            display: flex;
            gap: 1.5rem;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: var(--primary-light);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .benefits-content {
                grid-template-columns: 1fr;
                gap: 3rem;
            }

            .benefits-visual {
                max-width: 400px;
                margin: 0 auto;
            }
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 1rem;
            }

            .nav-links {
                gap: 0.5rem;
            }

            .nav-link {
                padding: 0.5rem 0.75rem;
                font-size: 0.85rem;
            }

            .hero {
                padding: 5rem 1.5rem 3rem;
            }

            .hero h1 {
                font-size: 2.25rem;
            }

            .hero p {
                font-size: 1.1rem;
            }

            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn {
                width: 100%;
                max-width: 280px;
                justify-content: center;
            }

            .features,
            .benefits,
            .cta {
                padding: 4rem 1.5rem;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .footer-content {
                flex-direction: column;
                text-align: center;
            }

            .footer-links {
                flex-wrap: wrap;
                justify-content: center;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease forwards;
        }

        .delay-100 {
            animation-delay: 0.1s;
        }

        .delay-200 {
            animation-delay: 0.2s;
        }

        .delay-300 {
            animation-delay: 0.3s;
        }

        .delay-400 {
            animation-delay: 0.4s;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar" id="navbar">
        <div class="navbar-container">
            <a href="/" class="logo">
                <i class="fas fa-wallet"></i>
                <span>{{ config('app.name') }}</span>
            </a>
            @if (Route::has('login'))
                <div class="nav-links">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="nav-link nav-link-primary">
                            <i class="fas fa-chart-line"></i> Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="nav-link nav-link-light">
                            Đăng nhập
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="nav-link nav-link-primary">
                                Đăng ký miễn phí
                            </a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="floating-shape shape-1"></div>
        <div class="floating-shape shape-2"></div>
        <div class="floating-shape shape-3"></div>

        <div class="hero-content">
            <div class="hero-badge animate-fade-in-up">
                <i class="fas fa-sparkles"></i>
                Quản lý tài chính thông minh
            </div>
            <h1 class="animate-fade-in-up delay-100">
                Kiểm Soát Tài Chính<br>Xây Dựng Tương Lai
            </h1>
            <p class="animate-fade-in-up delay-200">
                Theo dõi thu chi, lập ngân sách và đạt mục tiêu tiết kiệm một cách dễ dàng.
                Bắt đầu hành trình làm chủ tài chính của bạn ngay hôm nay.
            </p>
            <div class="hero-buttons animate-fade-in-up delay-300">
                @guest
                    <a href="{{ route('register') }}" class="btn btn-white">
                        <i class="fas fa-rocket"></i> Bắt đầu miễn phí
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-outline">
                        <i class="fas fa-sign-in-alt"></i> Đã có tài khoản
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="btn btn-white">
                        <i class="fas fa-chart-line"></i> Vào Dashboard
                    </a>
                @endguest
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="container">
            <div class="section-header">
                <div class="section-badge">Tính năng</div>
                <h2 class="section-title">Mọi thứ bạn cần để quản lý tài chính</h2>
                <p class="section-subtitle">
                    Công cụ mạnh mẽ và dễ sử dụng giúp bạn theo dõi và kiểm soát tài chính cá nhân hiệu quả
                </p>
            </div>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <h3>Theo dõi Thu Chi</h3>
                    <p>Ghi nhận mọi khoản thu nhập và chi tiêu. Phân loại tự động và xem lịch sử giao dịch chi tiết.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <h3>Quản lý Ví tiền</h3>
                    <p>Tạo và quản lý nhiều ví khác nhau: tiền mặt, ngân hàng, thẻ tín dụng... Theo dõi số dư real-time.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <h3>Báo cáo & Thống kê</h3>
                    <p>Biểu đồ trực quan về tình hình tài chính. Phân tích chi tiêu theo danh mục và thời gian.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-piggy-bank"></i>
                    </div>
                    <h3>Mục tiêu Tiết kiệm</h3>
                    <p>Đặt mục tiêu tiết kiệm và theo dõi tiến độ. Nhận thông báo khi đạt được mục tiêu đề ra.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h3>Ngân sách Hàng tháng</h3>
                    <p>Lập ngân sách cho từng danh mục. Cảnh báo khi chi tiêu vượt quá hạn mức cho phép.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-tags"></i>
                    </div>
                    <h3>Danh mục Tùy chỉnh</h3>
                    <p>Tạo các danh mục thu chi theo nhu cầu cá nhân với màu sắc và icon tùy chọn.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="benefits" id="benefits">
        <div class="container">
            <div class="benefits-content">
                <div class="benefits-text">
                    <h2>Tại sao chọn {{ config('app.name') }}?</h2>
                    <p>Chúng tôi cung cấp giải pháp quản lý tài chính toàn diện, giúp bạn làm chủ đồng tiền và xây dựng
                        tương lai thịnh vượng.</p>

                    <ul class="benefit-list">
                        <li>
                            <i class="fas fa-check-circle"></i>
                            <div>
                                <strong>Hoàn toàn miễn phí trọn đời</strong>
                                <span>Trải nghiệm không giới hạn mọi tính năng cao cấp mà không tốn một xu</span>
                            </div>
                        </li>
                        <li>
                            <i class="fas fa-check-circle"></i>
                            <div>
                                <strong>Giao diện trực quan, tinh tế</strong>
                                <span>Thiết kế tối giản, dễ dàng thao tác ngay cả với người mới bắt đầu</span>
                            </div>
                        </li>
                        <li>
                            <i class="fas fa-check-circle"></i>
                            <div>
                                <strong>Bảo mật chuẩn ngân hàng</strong>
                                <span>Dữ liệu của bạn được mã hóa cấp cao nhất và bảo vệ 24/7</span>
                            </div>
                        </li>
                        <li>
                            <i class="fas fa-check-circle"></i>
                            <div>
                                <strong>Đồng bộ đa nền tảng</strong>
                                <span>Truy cập dữ liệu mọi lúc, mọi nơi trên mọi thiết bị của bạn</span>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="benefits-visual">
                    <div class="stat-card">
                        <i class="fas fa-users"></i>
                        <h4>1,000+</h4>
                        <p>Người dùng</p>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-receipt"></i>
                        <h4>50K+</h4>
                        <p>Giao dịch được ghi</p>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-star"></i>
                        <h4>4.8/5</h4>
                        <p>Đánh giá</p>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-shield-alt"></i>
                        <h4>100%</h4>
                        <p>Bảo mật</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="cta-content">
            <h2>Sẵn sàng kiểm soát tài chính của bạn?</h2>
            <p>Đăng ký miễn phí ngay hôm nay và bắt đầu hành trình quản lý tài chính thông minh của bạn.</p>
            @guest
                <a href="{{ route('register') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-right"></i> Tạo tài khoản miễn phí
                </a>
            @else
                <a href="{{ route('dashboard') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-right"></i> Vào Dashboard
                </a>
            @endguest
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-logo">
                <i class="fas fa-wallet"></i>
                <span>{{ config('app.name') }}</span>
            </div>
            <p class="footer-copy">© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <div class="footer-links">
                <a href="#">Chính sách bảo mật</a>
                <a href="#">Điều khoản sử dụng</a>
                <a href="#">Liên hệ</a>
            </div>
        </div>
    </footer>

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function () {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>

</html>