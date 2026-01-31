@php $header = __('Báo cáo chi tiêu') . ' - ' . $family->name; @endphp
<x-app-layout>
   <div class="row">
      <div class="col-12">
         <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
               <h3 class="card-title">
                  <i class="fas fa-chart-bar mr-2"></i>{{ __('Báo cáo Chi tiêu Tháng') }} {{ now()->format('m/Y') }}
               </h3>
               <a href="{{ route('families.show', $family) }}" class="btn btn-outline-secondary btn-sm">
                  <i class="fas fa-arrow-left"></i> {{ __('Quay lại') }}
               </a>
            </div>
            <div class="card-body">
               @if(empty($memberSpending))
                  <div class="text-center py-5 text-muted">
                     <i class="fas fa-chart-pie fa-3x mb-3"></i>
                     <p>{{ __('Chưa có dữ liệu chi tiêu') }}</p>
                  </div>
               @else
                  <div class="row">
                     <div class="col-md-8">
                        <canvas id="spendingChart" height="300"></canvas>
                     </div>
                     <div class="col-md-4">
                        <h5 class="mb-3">{{ __('Bảng xếp hạng') }}</h5>
                        <ul class="list-group">
                           @foreach($memberSpending as $index => $data)
                              <li class="list-group-item d-flex justify-content-between align-items-center">
                                 <div>
                                    @if($index === 0)
                                       <i class="fas fa-trophy text-warning mr-1"></i>
                                    @elseif($index === 1)
                                       <i class="fas fa-medal text-secondary mr-1"></i>
                                    @elseif($index === 2)
                                       <i class="fas fa-award text-danger mr-1"></i>
                                    @else
                                       <span class="badge badge-dark mr-1">{{ $index + 1 }}</span>
                                    @endif
                                    {{ $data['user']->name }}
                                 </div>
                                 <span class="badge badge-primary badge-pill">
                                    {{ number_format($data['spent'], 0, ',', '.') }} ₫
                                 </span>
                              </li>
                           @endforeach
                        </ul>

                        <div class="mt-4 p-3 bg-light rounded">
                           <h6>{{ __('Tổng chi tiêu') }}</h6>
                           <h3 class="text-danger mb-0">
                              {{ number_format(array_sum(array_column($memberSpending, 'spent')), 0, ',', '.') }} ₫
                           </h3>
                        </div>
                     </div>
                  </div>
               @endif
            </div>
         </div>
      </div>
   </div>

   @push('scripts')
      <script>
         const ctx = document.getElementById('spendingChart').getContext('2d');
         new Chart(ctx, {
            type: 'bar',
            data: {
               labels: {!! json_encode(array_map(fn($d) => $d['user']->name, $memberSpending)) !!},
               datasets: [{
                  label: '{{ __("Chi tiêu (VNĐ)") }}',
                  data: {!! json_encode(array_map(fn($d) => $d['spent'], $memberSpending)) !!},
                  backgroundColor: [
                     'rgba(255, 99, 132, 0.8)',
                     'rgba(54, 162, 235, 0.8)',
                     'rgba(255, 206, 86, 0.8)',
                     'rgba(75, 192, 192, 0.8)',
                     'rgba(153, 102, 255, 0.8)',
                  ],
                  borderColor: [
                     'rgba(255, 99, 132, 1)',
                     'rgba(54, 162, 235, 1)',
                     'rgba(255, 206, 86, 1)',
                     'rgba(75, 192, 192, 1)',
                     'rgba(153, 102, 255, 1)',
                  ],
                  borderWidth: 2,
                  borderRadius: 8,
               }]
            },
            options: {
               responsive: true,
               maintainAspectRatio: false,
               plugins: {
                  legend: {
                     display: false
                  }
               },
               scales: {
                  y: {
                     beginAtZero: true,
                     ticks: {
                        callback: function (value) {
                           return new Intl.NumberFormat('vi-VN').format(value) + ' ₫';
                        }
                     }
                  }
               }
            }
         });
      </script>
   @endpush
</x-app-layout>