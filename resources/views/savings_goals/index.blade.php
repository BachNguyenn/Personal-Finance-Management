<x-app-layout>
   <x-slot name="header">
      {{ __('Mục tiêu Tiết kiệm') }}
   </x-slot>

   @push('styles')
      <style>
         .progress-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: conic-gradient(#4caf50 var(--progress), #e9ecef 0deg);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
         }

         .progress-circle::before {
            content: "";
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: white;
            position: absolute;
         }

         .progress-value {
            position: relative;
            font-size: 24px;
            font-weight: bold;
            color: #333;
         }
      </style>
   @endpush

   <div class="row mb-3">
      <div class="col-12 text-right">
         <a href="{{ route('savings_goals.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i> {{ __('Tạo Mục tiêu') }}
         </a>
      </div>
   </div>

   <div class="row">
      @forelse($goals as $goal)
         <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 {{ $goal->status === 'completed' ? 'border-success' : '' }}">
               <div class="card-header border-0 bg-transparent">
                  <div class="d-flex justify-content-between align-items-center">
                     <h5 class="card-title font-weight-bold">
                        <i class="{{ $goal->icon ?? 'fas fa-bullseye' }}"
                           style="color: {{ $goal->color ?? '#007bff' }}; margin-right: 5px;"></i>
                        {{ $goal->name }}
                     </h5>
                     <div class="dropdown">
                        <button class="btn btn-link btn-sm text-muted" type="button" data-toggle="dropdown">
                           <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                           <a class="dropdown-item" href="{{ route('savings_goals.edit', $goal) }}">
                              <i class="fas fa-edit mr-2"></i> {{ __('Sửa') }}
                           </a>
                           <button class="dropdown-item text-success" data-toggle="modal"
                              data-target="#addMoneyModal{{ $goal->id }}">
                              <i class="fas fa-plus-circle mr-2"></i> {{ __('Nạp tiền') }}
                           </button>
                           <div class="dropdown-divider"></div>
                           <form action="{{ route('savings_goals.destroy', $goal) }}" method="POST"
                              onsubmit="return confirm('{{ __('Bạn có chắc chắn muốn xóa mục tiêu này?') }}');">
                              @csrf @method('DELETE')
                              <button type="submit" class="dropdown-item text-danger">
                                 <i class="fas fa-trash mr-2"></i> {{ __('Xóa') }}
                              </button>
                           </form>
                        </div>
                     </div>
                  </div>
                  @if($goal->status === 'completed')
                     <span class="badge badge-success">{{ __('Hoàn thành') }}</span>
                  @endif
               </div>
               <div class="card-body text-center">
                  <div class="d-flex justify-content-center mb-3">
                     <div class="progress-circle" style="--progress: {{ min($goal->progress, 100) }}%">
                        <div class="progress-value">{{ number_format($goal->progress, 1) }}%</div>
                     </div>
                  </div>

                  <div class="row mb-2">
                     <div class="col-6 text-left">
                        <small class="text-muted d-block">{{ __('Hiện tại') }}</small>
                        <span class="font-weight-bold text-success">{{ number_format($goal->current_amount) }}</span>
                     </div>
                     <div class="col-6 text-right">
                        <small class="text-muted d-block">{{ __('Mục tiêu') }}</small>
                        <span class="font-weight-bold">{{ number_format($goal->target_amount) }}</span>
                     </div>
                  </div>

                  @if($goal->target_date)
                     <small class="text-muted">
                        <i class="far fa-calendar-alt"></i> {{ __('Ngày đích') }}: {{ $goal->target_date->format('d/m/Y') }}
                     </small>
                  @endif
               </div>
            </div>
         </div>

         <!-- Add Money Modal -->
         <div class="modal fade" id="addMoneyModal{{ $goal->id }}">
            <div class="modal-dialog">
               <div class="modal-content">
                  <form action="{{ route('savings_goals.add_money', $goal) }}" method="POST">
                     @csrf
                     <div class="modal-header">
                        <h5 class="modal-title">{{ __('Nạp tiền vào') }}: {{ $goal->name }}</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                     </div>
                     <div class="modal-body">
                        <div class="form-group">
                           <label>{{ __('Số tiền') }}</label>
                           <div class="input-group">
                              <input type="number" name="amount" class="form-control" placeholder="0" min="0" required>
                              <div class="input-group-append">
                                 <span class="input-group-text">VND</span>
                              </div>
                           </div>
                        </div>
                        <div class="form-group">
                           <label>{{ __('Nguồn tiền (Ví)') }}</label>
                           <select name="wallet_id" class="form-control" required>
                              @foreach($wallets as $wallet)
                                 <option value="{{ $wallet->id }}">{{ $wallet->name }} ({{ number_format($wallet->balance) }})
                                 </option>
                              @endforeach
                           </select>
                        </div>
                        <p class="text-muted small">
                           {{ __('Hành động này sẽ tạo một giao dịch chi tiêu và trừ tiền từ ví đã chọn.') }}
                        </p>
                     </div>
                     <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Hủy') }}</button>
                        <button type="submit" class="btn btn-success">{{ __('Xác nhận') }}</button>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      @empty
         <div class="col-12 text-center py-5">
            <i class="fas fa-bullseye fa-3x text-muted mb-3"></i>
            <p class="text-muted">{{ __('Bạn chưa có mục tiêu tiết kiệm nào.') }}</p>
            <a href="{{ route('savings_goals.create') }}" class="btn btn-primary mt-2">
               {{ __('Đặt mục tiêu ngay') }}
            </a>
         </div>
      @endforelse
   </div>
</x-app-layout>