<x-guest-layout>
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="/" class="h1"><b>{{ config('app.name') }}</b></a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Chào mừng trở lại!</p>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="alert alert-success small mb-3 rounded-pill" role="alert">
                        <i class="fas fa-check-circle mr-1"></i> {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        </div>
                        <input type="email" name="email" class="form-control" placeholder="Email đăng nhập"
                            value="{{ old('email') }}" required autofocus>
                    </div>
                    @error('email')
                        <small class="text-danger d-block mb-3 ml-2" style="margin-top:-15px;">{{ $message }}</small>
                    @enderror

                    <!-- Password -->
                    <div class="input-group mt-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        </div>
                        <input type="password" name="password" class="form-control" placeholder="Mật khẩu" required
                            autocomplete="current-password">
                    </div>
                    @error('password')
                        <small class="text-danger d-block mb-3 ml-2" style="margin-top:-15px;">{{ $message }}</small>
                    @enderror

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="remember" name="remember">
                            <label class="custom-control-label text-muted" for="remember">Ghi nhớ</label>
                        </div>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-muted">Quên mật khẩu?</a>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary btn-block mt-4 mb-3">
                        ĐĂNG NHẬP
                    </button>

                </form>

                <p class="mb-0 text-center text-muted">
                    Chưa có tài khoản? <a href="{{ route('register') }}" class="text-primary font-weight-bold">Đăng ký
                        ngay</a>
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>