<x-guest-layout>
    <p class="login-box-msg">Đăng ký thành viên mới</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="input-group mb-3">
            <input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Họ và tên" required autofocus>
            <div class="input-group-text">
                <span class="fas fa-user"></span>
            </div>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="input-group mb-3">
            <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Email" required>
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

        <!-- Confirm Password -->
        <div class="input-group mb-3">
            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" placeholder="Nhập lại mật khẩu" required>
            <div class="input-group-text">
                <span class="fas fa-lock"></span>
            </div>
        </div>

        <div class="row">
            <div class="col-8">
            </div>
            <div class="col-4">
                <button type="submit" class="btn btn-primary btn-block w-100">Đăng ký</button>
            </div>
        </div>
    </form>

    <a href="{{ route('login') }}" class="text-center mt-3 d-block">Tôi đã có tài khoản</a>
</x-guest-layout>
