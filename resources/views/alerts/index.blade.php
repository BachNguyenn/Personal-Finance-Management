@php $header = __('Tất cả thông báo'); @endphp
<x-app-layout>
   <div class="card">
      <div class="card-header">
         <h3 class="card-title"><i class="fas fa-bell mr-2"></i>{{ __('Thông báo Ngân sách') }}</h3>
         @if($alerts->where('is_read', false)->count() > 0)
            <div class="card-tools">
               <form action="{{ route('alerts.mark-all-read') }}" method="POST" class="d-inline">
                  @csrf
                  <button type="submit" class="btn btn-sm btn-outline-primary">
                     <i class="fas fa-check-double"></i> {{ __('Đánh dấu tất cả đã đọc') }}
                  </button>
               </form>
            </div>
         @endif
      </div>
      <div class="card-body p-0">
         @if($alerts->isEmpty())
            <div class="text-center py-5 text-muted">
               <i class="fas fa-bell-slash fa-3x mb-3"></i>
               <p>{{ __('Chưa có thông báo nào') }}</p>
            </div>
         @else
            <ul class="list-group list-group-flush">
               @foreach($alerts as $alert)
                  <li class="list-group-item {{ $alert->is_read ? '' : 'bg-light' }}">
                     <div class="d-flex align-items-center">
                        <div class="mr-3">
                           @if($alert->alert_type === 'exceeded')
                              <span class="badge badge-danger p-2">
                                 <i class="fas fa-exclamation-circle"></i>
                              </span>
                           @else
                              <span class="badge badge-warning p-2">
                                 <i class="fas fa-exclamation-triangle"></i>
                              </span>
                           @endif
                        </div>
                        <div class="flex-grow-1">
                           <strong>
                              @if($alert->alert_type === 'exceeded')
                                 {{ __('Vượt ngân sách!') }}
                              @else
                                 {{ __('Cảnh báo ngân sách') }}
                              @endif
                           </strong>
                           <p class="mb-1">
                              {{ __('Danh mục') }}: <strong>{{ $alert->budget?->category?->name ?? 'N/A' }}</strong>
                              <br>
                              {{ __('Đã chi') }}: {{ number_format($alert->spent_amount, 0, ',', '.') }} ₫
                              ({{ $alert->percentage_used }}%)
                           </p>
                           <small class="text-muted">
                              <i class="fas fa-clock mr-1"></i>
                              {{ $alert->created_at->diffForHumans() }}
                           </small>
                        </div>
                        @if(!$alert->is_read)
                           <form action="{{ route('alerts.mark-read', $alert) }}" method="POST">
                              @csrf
                              <button type="submit" class="btn btn-sm btn-outline-secondary">
                                 <i class="fas fa-check"></i>
                              </button>
                           </form>
                        @endif
                     </div>
                  </li>
               @endforeach
            </ul>
         @endif
      </div>
      @if($alerts->hasPages())
         <div class="card-footer">
            {{ $alerts->links() }}
         </div>
      @endif
   </div>
</x-app-layout>