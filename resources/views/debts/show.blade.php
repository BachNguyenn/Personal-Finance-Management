@php 
    $header = $debt->type === 'lend' ? __('Chi tiết cho vay') : __('Chi tiết đi vay'); 
@endphp
<x-app-layout>
   <div class="row">
      <!-- Main Info Card -->
      <div class="col-md-8">
         <div class="card">
            <div class="card-header {{ $debt->type === 'lend' ? 'bg-success' : 'bg-danger' }} text-white">
               <h3 class="card-title mb-0">
                  @if($debt->type === 'lend')
                     <i class="fas fa-arrow-up mr-2"></i>{{ __('Cho vay') }}
                  @else
                     <i class="fas fa-arrow-down mr-2"></i>{{ __('Đi vay') }}
                  @endif
                  - {{ $debt->person_name }}
               </h3>
               <div class="card-tools">
                  @if($debt->status !== 'settled')
                     <a href="{{ route('debts.edit', $debt) }}" class="btn btn-sm btn-light">
                        <i class="fas fa-edit"></i>
                     </a>
                  @endif
               </div>
            </div>
            <div class="card-body">
               <div class="row mb-4">
                  <div class="col-sm-6">
                     <h2 class="mb-0 {{ $debt->type === 'lend' ? 'text-success' : 'text-danger' }}">
                        {{ number_format($debt->remaining, 0, ',', '.') }} ₫
                     </h2>
                     <small class="text-muted">
                        {{ __('còn lại') }} / {{ number_format($debt->amount, 0, ',', '.') }} ₫
                     </small>
                  </div>
                  <div class="col-sm-6">
                     <div class="progress" style="height: 25px;">
                        <div class="progress-bar {{ $debt->type === 'lend' ? 'bg-success' : 'bg-danger' }}"
                           role="progressbar" style="width: {{ $debt->getProgressPercentage() }}%">
                           {{ $debt->getProgressPercentage() }}%
                        </div>
                     </div>
                     <small class="text-muted">{{ __('Đã thanh toán') }}</small>
                  </div>
               </div>

               <table class="table table-sm">
                  <tr>
                     <th width="150">{{ __('Số điện thoại') }}</th>
                     <td>{{ $debt->phone ?: '-' }}</td>
                  </tr>
                  <tr>
                     <th>{{ __('Ngày vay') }}</th>
                     <td>{{ $debt->debt_date->format('d/m/Y') }}</td>
                  </tr>
                  <tr>
                     <th>{{ __('Hạn trả') }}</th>
                     <td>
                        @if($debt->due_date)
                           <span class="{{ $debt->isOverdue() ? 'text-danger font-weight-bold' : '' }}">
                              {{ $debt->due_date->format('d/m/Y') }}
                              @if($debt->isOverdue())
                                 <i class="fas fa-exclamation-triangle ml-1"></i> {{ __('Quá hạn!') }}
                              @endif
                           </span>
                        @else
                           <span class="text-muted">{{ __('Không có hạn') }}</span>
                        @endif
                     </td>
                  </tr>
                  <tr>
                     <th>{{ __('Trạng thái') }}</th>
                     <td>
                        @if($debt->status === 'settled')
                           <span class="badge badge-success"><i class="fas fa-check"></i> {{ __('Đã thanh toán') }}</span>
                        @elseif($debt->status === 'partial')
                           <span class="badge badge-warning">{{ __('Trả một phần') }}</span>
                        @else
                           <span class="badge badge-secondary">{{ __('Chưa trả') }}</span>
                        @endif
                     </td>
                  </tr>
                  @if($debt->description)
                     <tr>
                        <th>{{ __('Ghi chú') }}</th>
                        <td>{{ $debt->description }}</td>
                     </tr>
                  @endif
               </table>
            </div>
            <div class="card-footer">
               <a href="{{ route('debts.index', ['type' => $debt->type]) }}" class="btn btn-secondary">
                  <i class="fas fa-arrow-left"></i> {{ __('Quay lại') }}
               </a>
               @if($debt->status !== 'settled')
                  <form action="{{ route('debts.settle', $debt) }}" method="POST" class="d-inline float-right ml-2">
                     @csrf
                     <button type="submit" class="btn btn-success"
                        onclick="return confirm('{{ __('Đánh dấu đã thanh toán xong?') }}')">
                        <i class="fas fa-check-double"></i> {{ __('Hoàn tất') }}
                     </button>
                  </form>
               @endif
               <form action="{{ route('debts.destroy', $debt) }}" method="POST" class="d-inline float-right">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-outline-danger"
                     onclick="return confirm('{{ __('Xác nhận xóa?') }}')">
                     <i class="fas fa-trash"></i> {{ __('Xóa') }}
                  </button>
               </form>
            </div>
         </div>
      </div>

      <!-- Payment History -->
      <div class="col-md-4">
         <div class="card">
            <div class="card-header">
               <h3 class="card-title"><i class="fas fa-history mr-2"></i>{{ __('Lịch sử thanh toán') }}</h3>
            </div>
            <div class="card-body p-0">
               @if($debt->payments->isEmpty())
                  <div class="text-center py-4 text-muted">
                     <i class="fas fa-clock fa-2x mb-2"></i>
                     <p class="mb-0">{{ __('Chưa có giao dịch') }}</p>
                  </div>
               @else
                  <ul class="list-group list-group-flush">
                     @foreach($debt->payments as $payment)
                        <li class="list-group-item">
                           <div class="d-flex justify-content-between">
                              <strong class="text-success">+{{ number_format($payment->amount, 0, ',', '.') }} ₫</strong>
                              <small class="text-muted">{{ $payment->payment_date->format('d/m/Y') }}</small>
                           </div>
                           @if($payment->note)
                              <small class="text-muted">{{ $payment->note }}</small>
                           @endif
                        </li>
                     @endforeach
                  </ul>
               @endif
            </div>
            @if($debt->status !== 'settled')
               <div class="card-footer">
                  <button type="button" class="btn btn-primary btn-block" data-toggle="modal"
                     data-target="#addPaymentModal">
                     <i class="fas fa-plus"></i> {{ __('Thêm thanh toán') }}
                  </button>
               </div>
            @endif
         </div>
      </div>
   </div>

   <!-- Add Payment Modal -->
   @if($debt->status !== 'settled')
      <div class="modal fade" id="addPaymentModal" tabindex="-1">
         <div class="modal-dialog">
            <div class="modal-content">
               <form action="{{ route('debts.payments.store', $debt) }}" method="POST">
                  @csrf
                  <div class="modal-header">
                     <h5 class="modal-title">{{ __('Ghi nhận thanh toán') }}</h5>
                     <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>
                  <div class="modal-body">
                     <div class="form-group">
                        <label>{{ __('Số tiền') }} <span class="text-danger">*</span></label>
                        <div class="input-group">
                           <input type="number" class="form-control" name="amount" min="1" max="{{ $debt->remaining }}"
                              step="1000" value="{{ $debt->remaining }}" required>
                           <div class="input-group-append">
                              <span class="input-group-text">₫</span>
                           </div>
                        </div>
                        <small class="text-muted">{{ __('Tối đa') }}: {{ number_format($debt->remaining, 0, ',', '.') }}
                           ₫</small>
                     </div>
                     <div class="form-group">
                        <label>{{ __('Ngày thanh toán') }} <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="payment_date" value="{{ date('Y-m-d') }}" required>
                     </div>
                     <div class="form-group mb-0">
                        <label>{{ __('Ghi chú') }}</label>
                        <input type="text" class="form-control" name="note" placeholder="{{ __('Ghi chú tùy chọn...') }}">
                     </div>
                  </div>
                  <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Hủy') }}</button>
                     <button type="submit" class="btn btn-primary">{{ __('Lưu') }}</button>
                  </div>
               </form>
            </div>
         </div>
      </div>
   @endif
</x-app-layout>