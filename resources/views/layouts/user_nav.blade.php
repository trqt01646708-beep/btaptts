<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">
        <a class="navbar-brand" href="/">Cửa Hàng Của Tôi</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="/">Trang Chủ</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('posts.index') }}">Bài Viết</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('products.index') }}">Sản Phẩm</a></li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('cart.index') }}">
                        <i class="fas fa-shopping-cart"></i> Giỏ Hàng 
                        <span class="badge bg-danger">
                            {{ Auth::check() ? \App\Models\CartItem::where('user_id', Auth::id())->count() : (session('cart') ? count(session('cart')) : 0) }}
                        </span>
                    </a>
                </li>
                @guest
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Đăng Nhập</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Đăng Ký</a></li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            <li><a class="dropdown-item" href="{{ route('orders.history') }}">Lịch Sử Đơn Hàng</a></li>
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Hồ Sơ Cá Nhân</a></li>
                            @if(Auth::user()->hasRole('admin'))
                                <li><a class="dropdown-item fw-bold text-primary" href="{{ route('admin.dashboard') }}">Bảng Quản Trị</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item" type="submit">Đăng Xuất</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
