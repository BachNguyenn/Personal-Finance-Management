<x-app-layout>
   <x-slot name="header">
      Quản lý Giao dịch
   </x-slot>

   <!-- Filters -->
   <div class="card collapsed-card">
      <div class="card-header">
         <h3 class="card-title"><i class="fas fa-filter mr-1"></i> {{ __('Search Filter') }}</h3>
         <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
               <i class="fas fa-plus"></i>
            </button>
         </div>
      </div>
      <div class="card-body">
         <form action="{{ route('transactions.index') }}" method="GET">
            <div class="row">
               <div class="col-md-3 mb-2">
                  <select name="type" class="form-control">
                     <option value="">{{ __('All Types') }}</option>
                     <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>{{ __('Income') }}
                     </option>
                     <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>{{ __('Expense') }}
                     </option>
                     <option value="transfer" {{ request('type') == 'transfer' ? 'selected' : '' }}>{{ __('Transfer') }}
                     </option>
                  </select>
               </div>
               <div class="col-md-3 mb-2">
                  <select name="category_id" class="form-control">
                     <option value="">{{ __('All Categories') }}</option>
                     @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                           {{ $category->name }}
                        </option>
                     @endforeach
                  </select>
               </div>
               <div class="col-md-3 mb-2">
                  <select name="wallet_id" class="form-control">
                     <option value="">{{ __('All Wallets') }}</option>
                     @foreach($wallets as $wallet)
                        <option value="{{ $wallet->id }}" {{ request('wallet_id') == $wallet->id ? 'selected' : '' }}>
                           {{ $wallet->name }}
                        </option>
                     @endforeach
                  </select>
               </div>
               <div class="col-md-3 mb-2">
                  <input type="text" name="search" class="form-control" placeholder="{{ __('Search description...') }}"
                     value="{{ request('search') }}">
               </div>
               <div class="col-md-3 mb-2">
                  <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
               </div>
               <div class="col-md-3 mb-2">
                  <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
               </div>
               <div class="col-md-3 mb-2">
                  <button type="submit" class="btn btn-primary btn-block">
                     <i class="fas fa-search mr-1"></i> {{ __('Filter') }}
                  </button>
               </div>
               <div class="col-md-3 mb-2">
                  <a href="{{ route('transactions.index') }}" class="btn btn-default btn-block">
                     <i class="fas fa-times mr-1"></i> {{ __('Clear Filter') }}
                  </a>
               </div>
            </div>
         </form>
      </div>
   </div>

   <!-- Toolbar -->
   <div class="mb-3 d-flex justify-content-between align-items-center">
      <div>
         <span class="text-muted">{{ __('Showing :count transactions', ['count' => $transactions->count()]) }}</span>
      </div>
      <div>
         <a href="{{ route('transactions.create', ['type' => 'income']) }}" class="btn btn-success btn-sm">
            <i class="fas fa-plus mr-1"></i> {{ __('Income') }}
         </a>
         <a href="{{ route('transactions.create', ['type' => 'expense']) }}" class="btn btn-danger btn-sm">
            <i class="fas fa-minus mr-1"></i> {{ __('Expense') }}
         </a>
         <a href="{{ route('transactions.create', ['type' => 'transfer']) }}" class="btn btn-info btn-sm">
            <i class="fas fa-exchange-alt mr-1"></i> {{ __('Transfer') }}
         </a>
      </div>
   </div>

   <!-- Transactions List -->
   <div class="card">
      <div class="card-body p-0 table-responsive">
         <table class="table table-hover text-nowrap">
            <thead>
               <tr>
                  <th style="width: 15%">{{ __('Date') }}</th>
                  <th style="width: 20%">{{ __('Category') }}</th>
                  <th>{{ __('Description') }}</th>
                  <th>{{ __('Wallet') }}</th>
                  <th class="text-right">{{ __('Amount') }}</th>
                  <th class="text-center" style="width: 10%">{{ __('Action') }}</th>
               </tr>
            </thead>
            <tbody>
               @forelse($transactions as $transaction)
                  <tr>
                     <td>{{ $transaction->transaction_date->format('d/m/Y') }}</td>
                     <td>
                        <div class="d-flex align-items-center">
                           <div class="rounded-circle d-flex align-items-center justify-content-center mr-2"
                              style="width: 30px; height: 30px; background-color: {{ $transaction->category->color ?? '#ccc' }}20;">
                              <i class="{{ $transaction->category->icon ?? 'fas fa-question' }}"
                                 style="color: {{ $transaction->category->color ?? '#666' }}; font-size: 0.8rem;"></i>
                           </div>
                           <span>{{ $transaction->category->name ?? 'None' }}</span>
                        </div>
                     </td>
                     <td>
                        {{ $transaction->description }}
                        @if($transaction->attachment)
                           <i class="fas fa-paperclip text-muted ml-1"></i>
                        @endif
                     </td>
                     <td>
                        {{ $transaction->wallet->name }}
                        @if($transaction->type === 'transfer' && $transaction->toWallet)
                           <i class="fas fa-arrow-right mx-1 text-muted small"></i> {{ $transaction->toWallet->name }}
                        @endif
                     </td>
                     <td class="text-right">
                        <span
                           class="font-weight-bold {{ $transaction->type === 'income' ? 'text-success' : ($transaction->type === 'expense' ? 'text-danger' : 'text-primary') }}">
                           {{ $transaction->type === 'income' ? '+' : ($transaction->type === 'expense' ? '-' : '') }}
                           {{ number_format($transaction->amount, 0, ',', '.') }} ₫
                        </span>
                     </td>
                     <td class="text-center">
                        <a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-tool text-primary">
                           <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-tool text-danger"
                           onclick="if(confirm('{{ __('Delete Transaction?') }}')) document.getElementById('del-trans-{{ $transaction->id }}').submit()">
                           <i class="fas fa-trash"></i>
                        </button>
                        <form id="del-trans-{{ $transaction->id }}"
                           action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="d-none">
                           @csrf @method('DELETE')
                        </form>
                     </td>
                  </tr>
               @empty
                  <tr>
                     <td colspan="6" class="text-center py-5">
                        <i class="fas fa-receipt fa-2x text-muted mb-2"></i>
                        <p class="text-muted">{{ __('No transactions found') }}</p>
                     </td>
                  </tr>
               @endforelse
            </tbody>
         </table>
      </div>
      @if($transactions->hasPages())
         <div class="card-footer clearfix">
            {{ $transactions->links('pagination::bootstrap-4') }}
         </div>
      @endif
   </div>
</x-app-layout>