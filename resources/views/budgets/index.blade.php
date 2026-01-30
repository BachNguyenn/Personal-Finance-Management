<x-app-layout>
   <x-slot name="header">
      {{ __('Quản lý Ngân sách') }}
   </x-slot>

   <div class="row mb-3">
      <div class="col-12 text-right">
         <a href="{{ route('budgets.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i> {{ __('Tạo Ngân sách') }}
         </a>
      </div>
   </div>

   <div class="row">
      @forelse($budgets as $budget)
         <div class="col-md-6 col-lg-4">
            <div class="card h-100">
               <div class="card-header pb-0 border-0 bg-transparent">
                  <div class="d-flex justify-content-between align-items-center">
                     <h5 class="card-title font-weight-bold">
                        <i class="{{ $budget->category->icon ?? 'fas fa-tag' }}"
                           style="color: {{ $budget->category->color }}; margin-right: 5px;"></i>
                        {{ $budget->category->name }}
                     </h5>
                     <div class="dropdown">
                        <button class="btn btn-link btn-sm text-muted" type="button" data-toggle="dropdown">
                           <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                           <a class="dropdown-item" href="{{ route('budgets.edit', $budget) }}">
                              <i class="fas fa-edit mr-2"></i> {{ __('Sửa') }}
                           </a>
                           <form action="{{ route('budgets.destroy', $budget) }}" method="POST"
                              onsubmit="return confirm('{{ __('Bạn có chắc chắn muốn xóa ngân sách này?') }}');">
                              @csrf @method('DELETE')
                              <button type="submit" class="dropdown-item text-danger">
                                 <i class="fas fa-trash mr-2"></i> {{ __('Xóa') }}
                              </button>
                           </form>
                        </div>
                     </div>
                  </div>
                  <small class="text-muted">
                     {{ $budget->start_date->format('d/m/Y') }} - {{ $budget->end_date->format('d/m/Y') }}
                  </small>
               </div>
               <div class="card-body">
                  <div class="d-flex justify-content-between mb-2">
                     <span>{{ __('Đã chi') }}: <strong>{{ number_format($budget->spent) }}</strong></span>
                     <span>{{ __('Hạn mức') }}: <strong>{{ number_format($budget->amount) }}</strong></span>
                  </div>
                  <div class="progress" style="height: 20px;">
                     <div
                        class="progress-bar {{ $budget->progress > 100 ? 'bg-danger' : ($budget->progress > 80 ? 'bg-warning' : 'bg-success') }}"
                        role="progressbar" style="width: {{ min($budget->progress, 100) }}%"
                        aria-valuenow="{{ $budget->progress }}" aria-valuemin="0" aria-valuemax="100">
                        {{ number_format($budget->progress, 0) }}%
                     </div>
                  </div>
                  <div class="mt-2 text-right">
                     <small
                        class="{{ $budget->amount - $budget->spent < 0 ? 'text-danger font-weight-bold' : 'text-success' }}">
                        {{ $budget->amount - $budget->spent < 0 ? __('Vượt quá') : __('Còn lại') }}:
                        {{ number_format(abs($budget->amount - $budget->spent)) }} VND
                     </small>
                  </div>
               </div>
            </div>
         </div>
      @empty
         <div class="col-12 text-center py-5">
            <i class="fas fa-chart-pie fa-3x text-muted mb-3"></i>
            <p class="text-muted">{{ __('Chưa có ngân sách nào được tạo.') }}</p>
            <a href="{{ route('budgets.create') }}" class="btn btn-primary mt-2">
               {{ __('Tạo Ngân sách ngay') }}
            </a>
         </div>
      @endforelse
   </div>
</x-app-layout>