<x-guest-layout>
    <p class="login-box-msg">Đăng nhập để bắt đầu phiên làm việc</p>

    <!-- Session Status -->
    <x-auth-session-status class="mb-3" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="input-group mb-3">
            <input id="email" type="text" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Email hoặc Tài khoản" required autofocus>
            <div class="input-group-text">
                <span class="fas fa-envelope"></span>
            </div>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="input-group mb-3">
            <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Mật khẩu" required>
            <div class="input-group-text">
                <span class="fas fa-lock"></span>
            </div>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="row">
            <div class="col-8">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">
                        Ghi nhớ mật khẩu
                    </label>
                </div>
            </div>
            <div class="col-4">
                <button type="submit" class="btn btn-primary btn-block w-100">Đăng nhập</button>
            </div>
        </div>
    </form>

    <div class="mt-3">
        @if (Route::has('password.request'))
            <p class="mb-1">
                <a href="{{ route('password.request') }}">Tôi quên mật khẩu</a>
            </p>
        @endif
        <p class="mb-0">
            <a href="{{ route('register') }}" class="text-center">Đăng ký thành viên mới</a>
        </p>
    </div>
</x-guest-layout>
