@php $header = __('Sổ Nợ'); @endphp
<x-app-layout>
   <div class="row">
      <!-- Summary Cards -->
      <div class="col-md-6">
         <div class="small-box bg-success">
            <div class="inner">
               <h3>{{ number_format($totalLend, 0, ',', '.') }} ₫</h3>
               <p>{{ __('Người khác nợ bạn') }}</p>
            </div>
            <div class="icon">
               <i class="fas fa-hand-holding-usd"></i>
            </div>
         </div>
      </div>
      <div class="col-md-6">
         <div class="small-box bg-danger">
            <div class="inner">
               <h3>{{ number_format($totalBorrow, 0, ',', '.') }} ₫</h3>
               <p>{{ __('Bạn đang nợ') }}</p>
            </div>
            <div class="icon">
               <i class="fas fa-credit-card"></i>
            </div>
         </div>
      </div>
   </div>

   <div class="card">
      <div class="card-header">
         <ul class="nav nav-tabs card-header-tabs">
            <li class="nav-item">
               <a class="nav-link {{ $type === 'lend' ? 'active' : '' }}"
                  href="{{ route('debts.index', ['type' => 'lend']) }}">
                  <i class="fas fa-arrow-up text-success"></i> {{ __('Cho vay') }}
               </a>
            </li>
            <li class="nav-item">
               <a class="nav-link {{ $type === 'borrow' ? 'active' : '' }}"
                  href="{{ route('debts.index', ['type' => 'borrow']) }}">
                  <i class="fas fa-arrow-down text-danger"></i> {{ __('Đi vay') }}
               </a>
            </li>
         </ul>
         <div class="card-tools">
            <a href="{{ route('debts.create', ['type' => $type]) }}" class="btn btn-primary btn-sm">
               <i class="fas fa-plus"></i> {{ __('Thêm mới') }}
            </a>
         </div>
      </div>
      <div class="card-body p-0">
         @if($debts->isEmpty())
            <div class="text-center py-5 text-muted">
               <i class="fas fa-inbox fa-3x mb-3"></i>
               <p>{{ __('Chưa có khoản nợ nào') }}</p>
            </div>
         @else
            <div class="table-responsive">
               <table class="table table-hover mb-0">
                  <thead class="thead-light">
                     <tr>
                        <th>{{ __('Người liên quan') }}</th>
                        <th class="text-right">{{ __('Số tiền') }}</th>
                        <th class="text-right">{{ __('Còn lại') }}</th>
                        <th>{{ __('Ngày') }}</th>
                        <th>{{ __('Hạn trả') }}</th>
                        <th>{{ __('Trạng thái') }}</th>
                        <th></th>
                     </tr>
                  </thead>
                  <tbody>
                     @foreach($debts as $debt)
                        <tr class="{{ $debt->status === 'settled' ? 'table-secondary' : '' }}">
                           <td>
                              <strong>{{ $debt->person_name }}</strong>
                              @if($debt->phone)
                                 <br><small class="text-muted">{{ $debt->phone }}</small>
                              @endif
                           </td>
                           <td class="text-right">{{ number_format($debt->amount, 0, ',', '.') }} ₫</td>
                           <td class="text-right font-weight-bold {{ $type === 'lend' ? 'text-success' : 'text-danger' }}">
                              {{ number_format($debt->remaining, 0, ',', '.') }} ₫
                           </td>
                           <td>{{ $debt->debt_date->format('d/m/Y') }}</td>
                           <td>
                              @if($debt->due_date)
                                 <span class="{{ $debt->isOverdue() ? 'text-danger font-weight-bold' : '' }}">
                                    {{ $debt->due_date->format('d/m/Y') }}
                                    @if($debt->isOverdue())
                                       <i class="fas fa-exclamation-triangle"></i>
                                    @endif
                                 </span>
                              @else
                                 <span class="text-muted">-</span>
                              @endif
                           </td>
                           <td>
                              @if($debt->status === 'settled')
                                 <span class="badge badge-success">{{ __('Đã thanh toán') }}</span>
                              @elseif($debt->status === 'partial')
                                 <span class="badge badge-warning">{{ __('Trả một phần') }}</span>
                              @else
                                 <span class="badge badge-secondary">{{ __('Chưa trả') }}</span>
                              @endif
                           </td>
                           <td class="text-right">
                              <a href="{{ route('debts.show', $debt) }}" class="btn btn-sm btn-info">
                                 <i class="fas fa-eye"></i>
                              </a>
                           </td>
                        </tr>
                     @endforeach
                  </tbody>
               </table>
            </div>
         @endif
      </div>
   </div>
</x-app-layout>