<x-app-layout>
    <x-slot name="header">{{ __('Edit Wallet') }}</x-slot>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Edit Wallet') }}: {{ $wallet->name }}</h3>
                </div>
                <form action="{{ route('wallets.update', $wallet) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label>{{ __('Wallet Name') }}</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $wallet->name) }}"
                                required>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Wallet Type') }}</label>
                            <select name="type" class="form-control">
                                <option value="cash" {{ $wallet->type == 'cash' ? 'selected' : '' }}>{{ __('Cash') }}
                                </option>
                                <option value="bank" {{ $wallet->type == 'bank' ? 'selected' : '' }}>
                                    {{ __('Bank Account') }}</option>
                                <option value="e-wallet" {{ $wallet->type == 'e-wallet' ? 'selected' : '' }}>
                                    {{ __('E-Wallet') }}
                                </option>
                                <option value="other" {{ $wallet->type == 'other' ? 'selected' : '' }}>{{ __('Other') }}
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Số dư hiện tại (Chỉ đọc)</label>
                            <input type="text" class="form-control" value="{{ number_format($wallet->balance) }} VND"
                                disabled>
                            <small class="text-muted">Để thay đổi số dư, hãy thêm giao dịch.</small>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Color') }}</label>
                            <input type="color" name="color" class="form-control"
                                value="{{ old('color', $wallet->color) }}" style="height: 40px">
                        </div>
                        <div class="form-group mb-0">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="is_default" class="custom-control-input" id="is_default"
                                    value="1" {{ $wallet->is_default ? 'checked' : '' }}>
                                <label class="custom-control-label"
                                    for="is_default">{{ __('Set as Default Wallet') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning">{{ __('Save') }}</button>
                        <a href="{{ route('wallets.index') }}"
                            class="btn btn-default float-right">{{ __('Cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>