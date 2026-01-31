@php $header = __('Phân tích Thông minh'); @endphp
<x-app-layout>
   <div class="row">
      <!-- Financial Health Score -->
      <div class="col-lg-4 col-12">
         <div class="card card-primary card-outline">
            <div class="card-header">
               <h3 class="card-title">
                  <i class="fas fa-heartbeat mr-2"></i>{{ __('Điểm sức khỏe Tài chính') }}
               </h3>
            </div>
            <div class="card-body text-center">
               <div class="health-score-circle mb-3">
                  <div class="display-1 font-weight-bold text-{{ $healthScore['grade']['color'] }}">
                     {{ $healthScore['score'] }}
                  </div>
                  <div class="text-muted">/ {{ $healthScore['max'] }}</div>
               </div>

               <h4>
                  <span class="badge badge-{{ $healthScore['grade']['color'] }} px-4 py-2">
                     {{ $healthScore['grade']['letter'] }} - {{ $healthScore['grade']['label'] }}
                  </span>
               </h4>

               <hr>

               <!-- Score Breakdown -->
               <div class="text-left">
                  <div class="mb-3">
                     <div class="d-flex justify-content-between mb-1">
                        <span><i class="fas fa-piggy-bank mr-1"></i> {{ __('Tỷ lệ tiết kiệm') }}</span>
                        <span>{{ $healthScore['breakdown']['savings']['score'] }}/{{ $healthScore['breakdown']['savings']['max'] }}</span>
                     </div>
                     <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success"
                           style="width: {{ ($healthScore['breakdown']['savings']['score'] / 25) * 100 }}%"></div>
                     </div>
                     <small class="text-muted">{{ $healthScore['breakdown']['savings']['rate'] }}% thu nhập</small>
                  </div>

                  <div class="mb-3">
                     <div class="d-flex justify-content-between mb-1">
                        <span><i class="fas fa-chart-pie mr-1"></i> {{ __('Tuân thủ ngân sách') }}</span>
                        <span>{{ $healthScore['breakdown']['budget']['score'] }}/{{ $healthScore['breakdown']['budget']['max'] }}</span>
                     </div>
                     <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-info"
                           style="width: {{ ($healthScore['breakdown']['budget']['score'] / 25) * 100 }}%"></div>
                     </div>
                     <small class="text-muted">{{ $healthScore['breakdown']['budget']['count'] }} ngân sách</small>
                  </div>

                  <div class="mb-3">
                     <div class="d-flex justify-content-between mb-1">
                        <span><i class="fas fa-hand-holding-usd mr-1"></i> {{ __('Quản lý nợ') }}</span>
                        <span>{{ $healthScore['breakdown']['debt']['score'] }}/{{ $healthScore['breakdown']['debt']['max'] }}</span>
                     </div>
                     <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-warning"
                           style="width: {{ ($healthScore['breakdown']['debt']['score'] / 25) * 100 }}%"></div>
                     </div>
                  </div>

                  <div class="mb-3">
                     <div class="d-flex justify-content-between mb-1">
                        <span><i class="fas fa-bullseye mr-1"></i> {{ __('Mục tiêu tiết kiệm') }}</span>
                        <span>{{ $healthScore['breakdown']['goals']['score'] }}/{{ $healthScore['breakdown']['goals']['max'] }}</span>
                     </div>
                     <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-primary"
                           style="width: {{ ($healthScore['breakdown']['goals']['score'] / 25) * 100 }}%"></div>
                     </div>
                     <small class="text-muted">{{ $healthScore['breakdown']['goals']['count'] }} mục tiêu</small>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <!-- Insights & Predictions -->
      <div class="col-lg-8 col-12">
         <!-- Quick Stats -->
         <div class="row">
            <div class="col-md-4">
               <div class="small-box bg-gradient-success">
                  <div class="inner">
                     <h4>{{ number_format($insights['thisMonth'], 0, ',', '.') }} ₫</h4>
                     <p>{{ __('Chi tiêu tháng này') }}</p>
                  </div>
                  <div class="icon"><i class="fas fa-shopping-cart"></i></div>
               </div>
            </div>
            <div class="col-md-4">
               <div
                  class="small-box bg-gradient-{{ $insights['changeDirection'] === 'down' ? 'success' : ($insights['changeDirection'] === 'up' ? 'danger' : 'info') }}">
                  <div class="inner">
                     <h4>
                        @if($insights['changeDirection'] === 'up')
                           <i class="fas fa-arrow-up"></i>
                        @elseif($insights['changeDirection'] === 'down')
                           <i class="fas fa-arrow-down"></i>
                        @endif
                        {{ abs($insights['changePercent']) }}%
                     </h4>
                     <p>{{ __('So với tháng trước') }}</p>
                  </div>
                  <div class="icon"><i class="fas fa-chart-line"></i></div>
               </div>
            </div>
            <div class="col-md-4">
               <div class="small-box bg-gradient-info">
                  <div class="inner">
                     <h4>{{ number_format($insights['avgDailySpending'], 0, ',', '.') }} ₫</h4>
                     <p>{{ __('Chi tiêu TB/ngày') }}</p>
                  </div>
                  <div class="icon"><i class="fas fa-calendar-day"></i></div>
               </div>
            </div>
         </div>

         <!-- Smart Tips -->
         <div class="card">
            <div class="card-header">
               <h3 class="card-title"><i class="fas fa-lightbulb mr-2 text-warning"></i>{{ __('Gợi ý Thông minh') }}
               </h3>
            </div>
            <div class="card-body p-0">
               <ul class="list-group list-group-flush">
                  @foreach($tips as $tip)
                     <li class="list-group-item">
                        <div class="d-flex">
                           <div class="mr-3">
                              <span class="badge badge-{{ $tip['type'] }} p-2">
                                 <i class="{{ $tip['icon'] }}"></i>
                              </span>
                           </div>
                           <div>
                              <strong>{{ $tip['title'] }}</strong>
                              <p class="mb-0 text-muted small">{{ $tip['message'] }}</p>
                           </div>
                        </div>
                     </li>
                  @endforeach
               </ul>
            </div>
         </div>

         <!-- Predictions -->
         <div class="card card-outline card-warning">
            <div class="card-header">
               <h3 class="card-title"><i class="fas fa-crystal-ball mr-2"></i>{{ __('Dự đoán Chi tiêu') }}</h3>
            </div>
            <div class="card-body">
               <div class="row text-center">
                  <div class="col-md-3 border-right">
                     <h5 class="text-muted">{{ __('Đã chi') }}</h5>
                     <h4>{{ number_format($predictions['currentExpense'], 0, ',', '.') }} ₫</h4>
                  </div>
                  <div class="col-md-3 border-right">
                     <h5 class="text-muted">{{ __('Dự đoán cuối tháng') }}</h5>
                     <h4 class="text-{{ $predictions['vsAveragePercent'] > 10 ? 'danger' : 'success' }}">
                        {{ number_format($predictions['predictedTotal'], 0, ',', '.') }} ₫
                     </h4>
                  </div>
                  <div class="col-md-3 border-right">
                     <h5 class="text-muted">{{ __('TB 3 tháng') }}</h5>
                     <h4>{{ number_format($predictions['avgMonthly'], 0, ',', '.') }} ₫</h4>
                  </div>
                  <div class="col-md-3">
                     <h5 class="text-muted">{{ __('Ngân sách/ngày còn lại') }}</h5>
                     <h4 class="text-info">{{ number_format(max(0, $predictions['dailyBudget']), 0, ',', '.') }} ₫</h4>
                     <small class="text-muted">còn {{ $predictions['daysRemaining'] }} ngày</small>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>

   <!-- Charts Row -->
   <div class="row">
      <!-- Monthly Comparison Chart -->
      <div class="col-lg-8">
         <div class="card">
            <div class="card-header">
               <h3 class="card-title"><i class="fas fa-chart-bar mr-2"></i>{{ __('Xu hướng 6 tháng gần nhất') }}</h3>
            </div>
            <div class="card-body">
               <canvas id="monthlyChart" height="250"></canvas>
            </div>
         </div>
      </div>

          <div class="card">
             <div class="card-header">
                <h3 class="card-title"><i class="fas fa-list-ol mr-2"></i>{{ __('Top 5 Giao dịch lớn nhất') }}</h3>
             </div>
             <div class="card-body p-0">
                @if(empty($topExpenses))
                   <p class="text-muted text-center p-3">{{ __('Chưa có dữ liệu') }}</p>
                @else
                   <ul class="list-group list-group-flush">
                      @foreach($topExpenses as $expense)
                         <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                               <div>
                                  <div class="font-weight-bold">{{ $expense['description'] }}</div>
                                  <small class="text-muted">
                                     <i class="far fa-calendar-alt mr-1"></i>{{ $expense['date'] }}
                                     @if($expense['category'])
                                        <span class="ml-2">
                                           <i class="{{ $expense['category']['icon'] ?? 'fas fa-tag' }}" 
                                              style="color: {{ $expense['category']['color'] ?? '#6c757d' }}"></i>
                                           {{ $expense['category']['name'] }}
                                        </span>
                                     @endif
                                  </small>
                               </div>
                               <div class="text-danger font-weight-bold">
                                  {{ number_format($expense['amount'], 0, ',', '.') }} ₫
                               </div>
                            </div>
                         </li>
                      @endforeach
                   </ul>
                @endif
             </div>
          </div>
   </div>

   @push('scripts')
      <script>
         // Monthly Comparison Chart
         const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
         new Chart(monthlyCtx, {
            type: 'bar',
            data: {
               labels: {!! json_encode(array_column($monthlyComparison, 'monthName')) !!},
               datasets: [
                  {
                     label: '{{ __("Thu nhập") }}',
                     data: {!! json_encode(array_column($monthlyComparison, 'income')) !!},
                     backgroundColor: 'rgba(40, 167, 69, 0.8)',
                     borderColor: 'rgb(40, 167, 69)',
                     borderWidth: 2,
                     borderRadius: 4,
                  },
                  {
                     label: '{{ __("Chi tiêu") }}',
                     data: {!! json_encode(array_column($monthlyComparison, 'expense')) !!},
                     backgroundColor: 'rgba(220, 53, 69, 0.8)',
                     borderColor: 'rgb(220, 53, 69)',
                     borderWidth: 2,
                     borderRadius: 4,
                  },
                  {
                     label: '{{ __("Tiết kiệm") }}',
                     data: {!! json_encode(array_column($monthlyComparison, 'savings')) !!},
                     type: 'line',
                     borderColor: 'rgb(0, 123, 255)',
                     backgroundColor: 'rgba(0, 123, 255, 0.1)',
                     fill: true,
                     tension: 0.4,
                  }
               ]
            },
            options: {
               responsive: true,
               maintainAspectRatio: false,
               plugins: {
                  legend: { position: 'top' }
               },
               scales: {
                  y: {
                     beginAtZero: true,
                     ticks: {
                        callback: (v) => new Intl.NumberFormat('vi-VN', { notation: 'compact' }).format(v) + ' ₫'
                     }
                  }
               }
            }
         });
      </script>
   @endpush

   <style>
      .health-score-circle {
         position: relative;
         display: inline-block;
      }

      .small-box h4 {
         font-size: 1.5rem;
      }
   </style>
</x-app-layout>