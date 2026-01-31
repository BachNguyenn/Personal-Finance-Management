<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Thông tin hồ sơ') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Cập nhật thông tin hồ sơ và địa chỉ email của bạn.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="row">
            <!-- Name -->
            <div class="col-md-6 form-group">
                <x-input-label for="name" :value="__('Họ và tên')" />
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                    </div>
                    <x-text-input id="name" name="name" type="text" class="form-control" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <!-- Email -->
            <div class="col-md-6 form-group">
                <x-input-label for="email" :value="__('Email')" />
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <x-text-input id="email" name="email" type="email" class="form-control" :value="old('email', $user->email)" required autocomplete="username" />
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                    <div class="mt-2 text-danger">
                        {{ __('Địa chỉ email của bạn chưa được xác minh.') }}
                        <button form="send-verification"
                            class="btn btn-link p-0 m-0 align-baseline">{{ __('Nhấn vào đây để gửi lại email xác minh.') }}</button>
                    </div>

                    @if (session('status') === 'verification-link-sent')
                        <div class="mt-2 text-success">
                            {{ __('Một liên kết xác minh mới đã được gửi đến địa chỉ email của bạn.') }}
                        </div>
                    @endif
                @endif
            </div>

            <!-- Phone -->
            <div class="col-md-6 form-group">
                <x-input-label for="phone" :value="__('Số điện thoại')" />
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                    </div>
                    <x-text-input id="phone" name="phone" type="text" class="form-control" :value="old('phone', $user->phone)" autocomplete="tel" />
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            </div>

            <!-- Birthday -->
            <div class="col-md-6 form-group">
                <x-input-label for="birthday" :value="__('Ngày sinh')" />
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-birthday-cake"></i></span>
                    </div>
                    <x-text-input id="birthday" name="birthday" type="date" class="form-control" :value="old('birthday', $user->birthday ? $user->birthday->format('Y-m-d') : '')" />
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('birthday')" />
            </div>

            <!-- Address -->
            <div class="col-md-12 form-group">
                <x-input-label for="address" :value="__('Địa chỉ')" />
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                    </div>
                    <x-text-input id="address" name="address" type="text" class="form-control" :value="old('address', $user->address)" autocomplete="street-address" />
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('address')" />
            </div>

            <!-- Bio -->
            <div class="col-md-12 form-group">
                <x-input-label for="bio" :value="__('Giới thiệu')" />
                <textarea id="bio" name="bio" class="form-control" rows="3"
                    placeholder="{{ __('Giới thiệu một chút về bản thân bạn...') }}">{{ old('bio', $user->bio) }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('bio')" />
            </div>
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="btn btn-primary">{{ __('Lưu') }}</button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400">{{ __('Đã lưu.') }}</p>
            @endif
        </div>
    </form>
</section>