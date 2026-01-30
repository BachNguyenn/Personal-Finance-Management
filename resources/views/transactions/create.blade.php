<x-app-layout>
    <x-slot name="header">
        @if($type === 'income')
            {{ __('Add New') }} {{ __('Income') }}
        @elseif($type === 'expense')
            {{ __('Add New') }} {{ __('Expense') }}
        @else
            {{ __('Transfer') }}
        @endif
    </x-slot>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-{{ $type === 'income' ? 'success' : ($type === 'expense' ? 'danger' : 'primary') }}">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Create Transaction') }}</h3>
                </div>
                <form action="{{ route('transactions.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}">
                    
                    <div class="card-body">
                        <!-- Amount -->
                        <div class="form-group">
                            <label>{{ __('Amount') }}</label>
                            <div class="input-group">
                                <input type="number" name="amount" class="form-control form-control-lg font-weight-bold" placeholder="0" min="0" required value="{{ old('amount') }}">
                                <div class="input-group-append">
                                    <span class="input-group-text">VND</span>
                                </div>
                            </div>
                        </div>

                        <!-- Date -->
                        <div class="form-group">
                            <label>{{ __('Date') }}</label>
                            <input type="date" name="transaction_date" class="form-control" required value="{{ old('transaction_date', date('Y-m-d')) }}">
                        </div>

                        <!-- Wallets -->
                        <div class="row">
                            <div class="col-md-{{ $type === 'transfer' ? '6' : '12' }}">
                                <div class="form-group">
                                    <label>{{ $type === 'transfer' ? __('From Wallet') : __('Wallet') }}</label>
                                    <select name="wallet_id" class="form-control custom-select" required>
                                        <option value="">{{ __('Select Wallet') }}</option>
                                        @foreach($wallets as $wallet)
                                            <option value="{{ $wallet->id }}" {{ old('wallet_id') == $wallet->id ? 'selected' : '' }}>
                                                {{ $wallet->name }} ({{ number_format($wallet->balance) }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @if($type === 'transfer')
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('To Wallet') }}</label>
                                        <select name="to_wallet_id" class="form-control custom-select" required>
                                            <option value="">{{ __('Select Wallet') }}</option>
                                            @foreach($wallets as $wallet)
                                                <option value="{{ $wallet->id }}" {{ old('to_wallet_id') == $wallet->id ? 'selected' : '' }}>
                                                    {{ $wallet->name }} ({{ number_format($wallet->balance) }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Categories (Not for transfer) -->
                        @if($type !== 'transfer')
                            <div class="form-group">
                                <label>{{ __('Category') }}</label>
                                <div class="d-flex flex-wrap" style="gap: 10px;">
                                    @foreach($categories as $category)
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="cat_{{ $category->id }}" name="category_id" value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'checked' : '' }}>
                                            <label for="cat_{{ $category->id }}" class="font-weight-normal border rounded p-2 d-flex align-items-center" style="cursor: pointer;">
                                                <i class="{{ $category->icon ?? 'fas fa-tag' }} mr-2" style="color: {{ $category->color }};"></i>
                                                {{ $category->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                @if($categories->isEmpty())
                                    <p class="text-danger small">Chưa có danh mục nào. <a href="{{ route('categories.create') }}">Tạo ngay</a></p>
                                @endif
                            </div>
                        @endif

                        <!-- Description -->
                        <div class="form-group">
                            <label>{{ __('Description') }}</label>
                            <input type="text" name="description" class="form-control" placeholder="VD: Ăn trưa, Tiền xăng..." value="{{ old('description') }}">
                        </div>

                        <!-- Note -->
                        <div class="form-group">
                            <label>{{ __('Note') }}</label>
                            <textarea name="note" class="form-control" rows="2" placeholder="Chi tiết thêm...">{{ old('note') }}</textarea>
                        </div>

                        <!-- Attachment -->
                        <div class="form-group">
                            <label for="attachment">{{ __('Attachment') }}</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="attachment" name="attachment">
                                <label class="custom-file-label" for="attachment">Chọn file...</label>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-{{ $type === 'income' ? 'success' : ($type === 'expense' ? 'danger' : 'primary') }}">{{ __('Save') }}</button>
                        <a href="{{ route('transactions.index') }}" class="btn btn-default float-right">{{ __('Cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categories = @json($categories);
            const descriptionInput = document.querySelector('input[name="description"]');

            if (descriptionInput) {
                descriptionInput.addEventListener('input', function(e) {
                    const desc = e.target.value.toLowerCase();
                    if (!desc) return;

                    let matchedCategoryId = null;

                    for (const cat of categories) {
                        if (cat.keywords && Array.isArray(cat.keywords)) {
                            for (const keyword of cat.keywords) {
                                if (keyword && desc.includes(keyword.toLowerCase())) {
                                    matchedCategoryId = cat.id;
                                    break;
                                }
                            }
                        }
                        if (matchedCategoryId) break;
                    }

                    if (matchedCategoryId) {
                        const radio = document.getElementById('cat_' + matchedCategoryId);
                        if (radio) {
                            if (!radio.checked) {
                                radio.click(); // Use click to trigger any other listeners
                                
                                // Visual feedback
                                const label = document.querySelector(`label[for="cat_${matchedCategoryId}"]`);
                                if (label) {
                                    label.classList.add('border-primary', 'bg-light');
                                    setTimeout(() => {
                                        label.classList.remove('border-primary', 'bg-light');
                                    }, 1000);
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>