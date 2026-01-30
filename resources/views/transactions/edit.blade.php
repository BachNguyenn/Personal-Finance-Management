<x-app-layout>
    <x-slot name="header">
        {{ __('Edit Transaction') }}
    </x-slot>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">{{ __('General Information') }}</h3>
                </div>
                <form action="{{ route('transactions.update', $transaction) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                        <!-- Alert Type -->
                        <div class="alert alert-light border">
                            <i class="fas fa-info-circle mr-1"></i> {{ __('Transaction Type') }}:
                            <strong>
                                @if($transaction->type === 'income') {{ __('Income') }}
                                @elseif($transaction->type === 'expense') {{ __('Expense') }}
                                @else {{ __('Transfer') }} @endif
                            </strong>
                        </div>

                        <!-- Amount -->
                        <div class="form-group">
                            <label>{{ __('Amount') }}</label>
                            <div class="input-group">
                                <input type="number" name="amount" class="form-control font-weight-bold"
                                    value="{{ old('amount', $transaction->amount) }}" min="0" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">VND</span>
                                </div>
                            </div>
                        </div>

                        <!-- Date -->
                        <div class="form-group">
                            <label>{{ __('Date') }}</label>
                            <input type="date" name="transaction_date" class="form-control" required
                                value="{{ old('transaction_date', $transaction->transaction_date->format('Y-m-d')) }}">
                        </div>

                        <!-- Wallet -->
                        <div class="form-group">
                            <label>{{ __('Wallet') }}</label>
                            <select name="wallet_id" class="form-control custom-select" required>
                                @foreach($wallets as $wallet)
                                    <option value="{{ $wallet->id }}" {{ old('wallet_id', $transaction->wallet_id) == $wallet->id ? 'selected' : '' }}>
                                        {{ $wallet->name }} ({{ number_format($wallet->balance) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Categories -->
                        @if($transaction->type !== 'transfer')
                            <div class="form-group">
                                <label>{{ __('Category') }}</label>
                                <select name="category_id" class="form-control custom-select">
                                    <option value="">{{ __('Select Category') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $transaction->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <!-- Description -->
                        <div class="form-group">
                            <label>{{ __('Description') }}</label>
                            <input type="text" name="description" class="form-control"
                                value="{{ old('description', $transaction->description) }}">
                        </div>

                        <!-- Note -->
                        <div class="form-group">
                            <label>{{ __('Note') }}</label>
                            <textarea name="note" class="form-control"
                                rows="2">{{ old('note', $transaction->note) }}</textarea>
                        </div>

                        <!-- Attachment -->
                        @if($transaction->attachment)
                            <div class="form-group">
                                <label>Ảnh hiện tại</label>
                                <div>
                                    <img src="{{ Storage::url($transaction->attachment) }}" class="img-thumbnail"
                                        style="max-height: 150px">
                                </div>
                            </div>
                        @endif
                        <div class="form-group">
                            <label>Thay đổi ảnh</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="attachment" name="attachment">
                                <label class="custom-file-label" for="attachment">Chọn file mới...</label>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                        <a href="{{ route('transactions.index') }}"
                            class="btn btn-default float-right">{{ __('Cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const categories = @json($categories);
                const descriptionInput = document.querySelector('input[name="description"]');

                if (descriptionInput) {
                    descriptionInput.addEventListener('input', function (e) {
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
                                // Only select if user hasn't manually selected another one, or maybe just force it? 
                                // For edit, maybe we shouldn't force change if it already exists?
                                // Let's force change for now as "smart suggestion" implies reacting to input.
                                // But maybe check if the current selection was already manually set? 
                                // Hard to track manual vs initial. Let's just do it.
                                if (!radio.checked) {
                                    radio.click();

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