<x-guest-layout>
    <div class="auth-header">
        <a href="/" class="auth-logo">
            <i class="fas fa-wallet"></i>
            <span>{{ config('app.name') }}</span>
        </a>
        <p class="auth-subtitle">Tạo tài khoản mới để bắt đầu hành trình tài chính của bạn.</p>
    </div>

    <div class="auth-card">
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div class="form-group">
                <div class="input-group">
                    <input type="text" name="name" class="form-control" placeholder="Họ và tên"
                        value="{{ old('name') }}" required autofocus>
                    <i class="fas fa-user input-icon"></i>
                </div>
                @error('name')
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Email -->
            <div class="form-group">
                <div class="input-group">
                    <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}"
                        required>
                    <i class="fas fa-envelope input-icon"></i>
                </div>
                @error('email')
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group">
                <div class="input-group">
                    <input type="password" name="password" class="form-control"
                        placeholder="Mật khẩu (tối thiểu 8 ký tự)" required autocomplete="new-password">
                    <i class="fas fa-lock input-icon"></i>
                </div>
                @error('password')
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <div class="input-group">
                    <input type="password" name="password_confirmation" class="form-control"
                        placeholder="Nhập lại mật khẩu" required autocomplete="new-password">
                    <i class="fas fa-check-circle input-icon"></i>
                </div>
            </div>

            <div class="form-group">
                <label class="checkbox-container">
                    <input type="checkbox" name="terms" id="agreeTerms" value="agree" required>
                    <span class="checkbox-label">
                        Tôi đồng ý với <a href="#" class="auth-link">Điều khoản dịch vụ</a>
                    </span>
                </label>
            </div>

            <button type="submit" class="btn btn-primary">
                Đăng ký tài khoản
            </button>
        </form>

        <div class="auth-footer">
            Đã có tài khoản?
            <a href="{{ route('login') }}" class="auth-link">Đăng nhập</a>
        </div>
    </div>
</x-guest-layout>