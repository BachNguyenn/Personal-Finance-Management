<x-app-layout>
   <x-slot name="header">
      {{ __('Advanced Reports') }}
   </x-slot>

   <div class="row mb-3">
      <div class="col-12 text-right">
         <a href="{{ route('reports.export') }}" class="btn btn-success">
            <i class="fas fa-file-csv mr-1"></i> {{ __('Download CSV') }}
         </a>
      </div>
   </div>

   <div class="row">
      <!-- Income vs Expense Chart -->
      <div class="col-md-12 mb-4">
         <div class="card card-primary card-outline">
            <div class="card-header">
               <h3 class="card-title">{{ __('Income vs Expense') }} (12 {{ __('Months') }})</h3>
            </div>
            <div class="card-body">
               <canvas id="incomeExpenseChart"
                  style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
            </div>
         </div>
      </div>
   </div>

   <div class="row">
      <!-- Expense Structure Chart -->
      <div class="col-md-6 mb-4">
         <div class="card card-info card-outline">
            <div class="card-header">
               <h3 class="card-title">{{ __('Expense Structure') }} ({{ __('This Month') }})</h3>
            </div>
            <div class="card-body">
               <canvas id="expenseStructureChart"
                  style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
            </div>
         </div>
      </div>

      <!-- Financial Health / Net Income Chart -->
      <div class="col-md-6 mb-4">
         <div class="card card-success card-outline">
            <div class="card-header">
               <h3 class="card-title">{{ __('Financial Health') }} ({{ __('Net Income') }})</h3>
            </div>
            <div class="card-body">
               <canvas id="netIncomeChart"
                  style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
            </div>
         </div>
      </div>
   </div>

   @push('scripts')
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
      <script>
         document.addEventListener('DOMContentLoaded', function () {
            // Data from Controller
            const months = @json($months);
            const incomes = @json($incomes);
            const expenses = @json($expenses);
            const categoryLabels = @json($categoryLabels);
            const categoryData = @json($categoryData);
            const categoryColors = @json($categoryColors);

            // 1. Income vs Expense Chart (Bar)
            new Chart(document.getElementById('incomeExpenseChart'), {
               type: 'bar',
               data: {
                  labels: months,
                  datasets: [
                     {
                        label: '{{ __("Income") }}',
                        backgroundColor: '#28a745',
                        borderColor: '#28a745',
                        data: incomes
                     },
                     {
                        label: '{{ __("Expense") }}',
                        backgroundColor: '#dc3545',
                        borderColor: '#dc3545',
                        data: expenses
                     }
                  ]
               },
               options: {
                  responsive: true,
                  maintainAspectRatio: false,
                  scales: {
                     y: { beginAtZero: true }
                  }
               }
            });

            // 2. Expense Structure Chart (Doughnut)
            if (categoryLabels.length > 0) {
               new Chart(document.getElementById('expenseStructureChart'), {
                  type: 'doughnut',
                  data: {
                     labels: categoryLabels,
                     datasets: [{
                        data: categoryData,
                        backgroundColor: categoryColors,
                     }]
                  },
                  options: {
                     responsive: true,
                     maintainAspectRatio: false,
                  }
               });
            } else {
               // Show message if no data
               document.getElementById('expenseStructureChart').parentNode.innerHTML = '<p class="text-center text-muted py-5">{{ __("No expense data this month") }}</p>';
            }

            // 3. Net Income Chart (Line)
            const netIncomes = incomes.map((inc, i) => inc - expenses[i]);
            new Chart(document.getElementById('netIncomeChart'), {
               type: 'line',
               data: {
                  labels: months,
                  datasets: [{
                     label: '{{ __("Net Income") }}',
                     data: netIncomes,
                     borderColor: '#007bff',
                     backgroundColor: 'rgba(0, 123, 255, 0.1)',
                     fill: true,
                     tension: 0.3
                  }]
               },
               options: {
                  responsive: true,
                  maintainAspectRatio: false,
                  scales: {
                     y: { beginAtZero: true }
                  }
               }
            });
         });
      </script>
   @endpush
</x-app-layout>