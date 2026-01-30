<x-guest-layout>
    <div class="register-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="/" class="h1"><b>{{ config('app.name') }}</b></a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Tạo tài khoản mới</p>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name -->
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                        </div>
                        <input type="text" name="name" class="form-control" placeholder="Họ và tên"
                            value="{{ old('name') }}" required autofocus>
                    </div>
                    @error('name')
                        <small class="text-danger d-block mb-3 ml-2" style="margin-top:-15px;">{{ $message }}</small>
                    @enderror

                    <!-- Email -->
                    <div class="input-group mt-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        </div>
                        <input type="email" name="email" class="form-control" placeholder="Email"
                            value="{{ old('email') }}" required>
                    </div>
                    @error('email')
                        <small class="text-danger d-block mb-3 ml-2" style="margin-top:-15px;">{{ $message }}</small>
                    @enderror

                    <!-- Password -->
                    <div class="input-group mt-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        </div>
                        <input type="password" name="password" class="form-control"
                            placeholder="Mật khẩu (tối thiểu 8 ký tự)" required autocomplete="new-password">
                    </div>
                    @error('password')
                        <small class="text-danger d-block mb-3 ml-2" style="margin-top:-15px;">{{ $message }}</small>
                    @enderror

                    <!-- Confirm Password -->
                    <div class="input-group mt-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                        </div>
                        <input type="password" name="password_confirmation" class="form-control"
                            placeholder="Nhập lại mật khẩu" required autocomplete="new-password">
                    </div>

                    <div class="mt-4 mb-3">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="agreeTerms" name="terms"
                                value="agree" required>
                            <label class="custom-control-label text-muted small" for="agreeTerms">
                                Tôi đồng ý với <a href="#" class="text-primary">Điều khoản dịch vụ</a>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        ĐĂNG KÝ
                    </button>
                </form>

                <p class="mt-4 mb-0 text-center text-muted">
                    Đã có tài khoản? <a href="{{ route('login') }}" class="text-primary font-weight-bold">Đăng nhập</a>
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>