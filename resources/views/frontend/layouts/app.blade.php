<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #6c757d;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --light-bg: #f8fafc;
            --border-color: #e2e8f0;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-bg);
            color: #1f2937;
        }
        
        /* Navbar */
        .navbar {
            background: linear-gradient(135deg, #fff 0%, #f8fafc 100%);
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border-color);
        }
        
        .navbar-brand {
            font-weight: 800;
            font-size: 1.6rem;
            background: linear-gradient(135deg, var(--primary-color) 0%, #1e40af 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.5px;
        }
        
        .navbar-brand i {
            margin-right: 8px;
        }
        
        .navbar-nav .nav-link {
            font-weight: 600;
            padding: 0.6rem 1.2rem !important;
            color: #4b5563 !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }
        
        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 50%;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--primary-color), #1e40af);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateX(-50%);
        }
        
        .navbar-nav .nav-link:hover::after {
            width: 60%;
        }
        
        .navbar-nav .nav-link:hover {
            color: var(--primary-color) !important;
        }
        
        .cart-icon {
            position: relative;
            transition: transform 0.3s;
        }
        
        .cart-icon:hover {
            transform: scale(1.1);
        }
        
        .cart-badge {
            position: absolute;
            top: -10px;
            right: -10px;
            background: linear-gradient(135deg, var(--danger-color), #dc2626);
            color: #fff;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            font-size: 11px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        /* Footer */
        .footer {
            background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
            color: #fff;
            padding: 60px 0 20px;
            margin-top: 80px;
            border-top: 1px solid var(--border-color);
        }
        
        .footer h5 {
            color: #fff;
            font-weight: 700;
            margin-bottom: 25px;
            font-size: 1.1rem;
            letter-spacing: -0.3px;
        }
        
        .footer a {
            color: #d1d5db;
            text-decoration: none;
            transition: all 0.3s;
            font-weight: 500;
        }
        
        .footer a:hover {
            color: var(--primary-color);
            margin-left: 5px;
        }
        
        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            transition: all 0.3s;
        }
        
        .social-links a:hover {
            background: var(--primary-color);
            transform: translateY(-3px);
        }
        
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 25px;
            margin-top: 40px;
            text-align: center;
            color: #9ca3af;
            font-weight: 500;
        }
        
        /* Product Card */
        .product-card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
            border: 1px solid var(--border-color);
            position: relative;
        }
        
        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 35px rgba(37, 99, 235, 0.2);
            border-color: var(--primary-color);
        }
        
        .product-card .product-image {
            position: relative;
            overflow: hidden;
            background: var(--light-bg);
        }
        
        .product-card .product-image img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .product-card:hover .product-image img {
            transform: scale(1.08);
        }
        
        .product-card .sale-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            background: linear-gradient(135deg, var(--danger-color), #dc2626);
            color: #fff;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }
        
        .product-card .product-info {
            padding: 16px;
            display: flex;
            flex-direction: column;
            height: 140px;
        }
        
        .product-card .product-name {
            font-weight: 700;
            font-size: 0.95rem;
            margin-bottom: 8px;
            color: #1f2937;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.4;
        }
        
        .product-card .product-price {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: auto;
        }
        
        .product-card .current-price {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--danger-color);
        }
        
        .product-card .original-price {
            font-size: 0.85rem;
            color: #9ca3af;
            text-decoration: line-through;
            font-weight: 500;
        }
        
        .product-card .btn {
            margin-top: auto;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 8px 12px;
            transition: all 0.3s;
        }
        
        /* Post Card */
        .post-card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
            border: 1px solid var(--border-color);
        }
        
        .post-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 35px rgba(37, 99, 235, 0.2);
        }
        
        .post-card .post-image img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            transition: transform 0.4s;
        }
        
        .post-card:hover .post-image img {
            transform: scale(1.08);
        }
        
        .post-card .post-info {
            padding: 16px;
        }
        
        .post-card .post-meta {
            font-size: 0.85rem;
            color: #9ca3af;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .post-card .post-title {
            font-weight: 700;
            font-size: 1rem;
            margin-bottom: 10px;
            color: #1f2937;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .post-card .post-excerpt {
            font-size: 0.9rem;
            color: #6b7280;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.5;
        }
        
        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), #1e40af);
            border: none;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
        }
        
        .btn-outline-primary {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            font-weight: 600;
            border-radius: 6px;
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: #fff;
        }
        
        /* Section Title */
        .section-title {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 40px;
            position: relative;
            padding-bottom: 20px;
            color: #1f2937;
            letter-spacing: -0.5px;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), #1e40af);
            border-radius: 2px;
        }
        
        /* Breadcrumb */
        .breadcrumb-section {
            background: linear-gradient(135deg, #fff, var(--light-bg));
            padding: 20px 0;
            margin-bottom: 40px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .breadcrumb {
            background: transparent;
        }
        
        .breadcrumb-item {
            font-weight: 500;
        }
        
        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .breadcrumb-item a:hover {
            color: #1e40af;
        }
        
        /* Alert */
        .alert {
            border-radius: 8px;
            border: none;
            font-weight: 500;
            animation: slideDown 0.3s ease-out;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .alert-success {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #065f46;
            border-left: 4px solid var(--success-color);
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #7f1d1d;
            border-left: 4px solid var(--danger-color);
        }
        
        /* Search Box */
        .search-box {
            position: relative;
        }
        
        .search-box input {
            padding-right: 40px;
            border-radius: 8px;
            border: 2px solid var(--border-color);
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .search-box input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        
        .search-box button {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: none;
            color: var(--primary-color);
            cursor: pointer;
            font-weight: 600;
        }
    
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-store me-2"></i>{{ config('app.name', 'Laravel Shop') }}
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="fas fa-home me-1"></i> Trang chủ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index') }}">
                            <i class="fas fa-box me-1"></i> Sản phẩm
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('posts.index') }}">
                            <i class="fas fa-newspaper me-1"></i> Bài viết
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contact') }}">
                            <i class="fas fa-envelope me-1"></i> Liên hệ
                        </a>
                    </li>
                </ul>
                
                <!-- Search -->
                <form class="d-flex me-3" action="{{ route('products.index') }}" method="GET">
                    <div class="search-box">
                        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm..." value="{{ request('search') }}">
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </form>
                
                <ul class="navbar-nav">
                    <!-- Cart -->
                    <li class="nav-item">
                        <a class="nav-link cart-icon" href="{{ route('cart.index') }}">
                            <i class="fas fa-shopping-cart fa-lg"></i>
                            <span class="cart-badge" id="cart-count">0</span>
                        </a>
                    </li>
                    
                    @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-1"></i> Đăng nhập
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">
                            <i class="fas fa-user-plus me-1"></i> Đăng ký
                        </a>
                    </li>
                    @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('orders.history') }}">
                                    <i class="fas fa-list me-2"></i> Đơn hàng của tôi
                                </a>
                            </li>
                            @if(Auth::user()->isAdmin())
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-cog me-2"></i> Quản trị
                                </a>
                            </li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ url('/dang-xuat') }}">
                                    <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Alerts -->
    <div class="container mt-3">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
    </div>
    
    <!-- Content -->
    @yield('content')
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5><i class="fas fa-store me-2"></i>{{ config('app.name', 'Laravel Shop') }}</h5>
                    <p class="text-muted">Cung cấp sản phẩm chất lượng cao với giá cả hợp lý. Cam kết mang đến trải nghiệm mua sắm tốt nhất cho khách hàng.</p>
                    <div class="social-links">
                        <a href="#" class="me-3"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" class="me-3"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="me-3"><i class="fab fa-youtube fa-lg"></i></a>
                    </div>
                </div>
                <div class="col-md-2 mb-4">
                    <h5>Liên kết</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('home') }}">Trang chủ</a></li>
                        <li class="mb-2"><a href="{{ route('products.index') }}">Sản phẩm</a></li>
                        <li class="mb-2"><a href="{{ route('posts.index') }}">Bài viết</a></li>
                        <li class="mb-2"><a href="{{ route('contact') }}">Liên hệ</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h5>Hỗ trợ</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#">Hướng dẫn mua hàng</a></li>
                        <li class="mb-2"><a href="#">Chính sách đổi trả</a></li>
                        <li class="mb-2"><a href="#">Chính sách bảo mật</a></li>
                        <li class="mb-2"><a href="#">Điều khoản sử dụng</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h5>Liên hệ</h5>
                    <ul class="list-unstyled text-muted">
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> 123 Đường ABC, Quận XYZ, TP.HCM</li>
                        <li class="mb-2"><i class="fas fa-phone me-2"></i> 0123 456 789</li>
                        <li class="mb-2"><i class="fas fa-envelope me-2"></i> support@example.com</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p class="mb-0">&copy; {{ date('Y') }} {{ config('app.name', 'Laravel Shop') }}. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Update cart count
        function updateCartCount() {
            fetch('{{ route("cart.count") }}')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('cart-count').textContent = data.count;
                })
                .catch(error => console.error('Error:', error));
        }
        
        // Load cart count on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateCartCount();
        });
        
        // Add to cart function
        function addToCart(productId, quantity = 1) {
            fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity
                })
            })
            .then(response => {
                // Check if response is JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('Server returned non-JSON response');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    document.getElementById('cart-count').textContent = data.cart_count;
                    alert(data.message);
                } else {
                    alert(data.message || 'Có lỗi xảy ra');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Lỗi: ' + error.message + '. Sản phẩm đã được thêm vào giỏ hàng');
            });
        }
    </script>
    
    @stack('scripts')
</body>
</html>

