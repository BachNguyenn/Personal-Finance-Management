<x-guest-layout>
    <div class="auth-header">
        <a href="/" class="auth-logo">
            <i class="fas fa-wallet"></i>
            <span>{{ config('app.name') }}</span>
        </a>
        <p class="auth-subtitle">Chào mừng trở lại! Vui lòng đăng nhập để tiếp tục.</p>
    </div>

    <div class="auth-card">
        <!-- Session Status -->
        @if (session('status'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="form-group">
                <div class="input-group">
                    <input type="email" name="email" class="form-control" placeholder="Email đăng nhập"
                        value="{{ old('email') }}" required autofocus>
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
                    <input type="password" name="password" class="form-control" placeholder="Mật khẩu" required
                        autocomplete="current-password">
                    <i class="fas fa-lock input-icon"></i>
                </div>
                @error('password')
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="flex-between">
                <label class="checkbox-container">
                    <input type="checkbox" name="remember" id="remember">
                    <span class="checkbox-label">Ghi nhớ đăng nhập</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="auth-link" style="font-size: 0.9rem;">
                        Quên mật khẩu?
                    </a>
                @endif
            </div>

            <button type="submit" class="btn btn-primary">
                Đăng nhập
            </button>
        </form>

        <div class="auth-footer">
            Chưa có tài khoản?
            <a href="{{ route('register') }}" class="auth-link">Đăng ký ngay</a>
        </div>
    </div>
</x-guest-layout>