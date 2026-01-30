<x-app-layout>
    <x-slot name="header">
        Dashboard
    </x-slot>

    <!-- Summary Widgets -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-white border">
                <div class="inner">
                    <h4 class="text-success font-weight-bold">{{ number_format($totalIncome, 0, ',', '.') }} ₫</h4>
                    <p class="text-muted">{{ __('Total Income This Month') }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-arrow-down text-success opacity-25"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-white border">
                <div class="inner">
                    <h4 class="text-danger font-weight-bold">{{ number_format($totalExpense, 0, ',', '.') }} ₫</h4>
                    <p class="text-muted">{{ __('Total Expense This Month') }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-arrow-up text-danger opacity-25"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-white border">
                <div class="inner">
                    <h4 class="{{ $netResult >= 0 ? 'text-primary' : 'text-danger' }} font-weight-bold">
                        {{ number_format($netResult, 0, ',', '.') }} ₫
                    </h4>
                    <p class="text-muted">{{ __('Net Result This Month') }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-scale-balanced opacity-25"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h4 class="font-weight-bold">{{ number_format($totalBalance, 0, ',', '.') }} ₫</h4>
                    <p>{{ __('Total Assets') }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-wallet opacity-25"></i>
                </div>
                <a href="{{ route('wallets.index') }}" class="small-box-footer">{{ __('Details') }} <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
    </div>

    <!-- Charts and Actions -->
    <div class="row">
        <!-- Left col -->
        <section class="col-lg-7 connectedSortable">
            <!-- Income vs Expense Chart -->
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line mr-1"></i>
                        {{ __('Income/Expense Trend (Last 30 Days)') }}
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="trendChart" style="min-height: 250px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">
                        <i class="fas fa-list mr-1"></i>
                        {{ __('Recent Transactions') }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('transactions.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> {{ __('Add New') }}
                        </a>
                        <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-tool">
                            {{ __('View All') }}
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                            <tr>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Description') }}</th>
                                <th>{{ __('Category') }}</th>
                                <th class="text-right">{{ __('Amount') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($recentTransactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->transaction_date->format('d/m') }}</td>
                                    <td>
                                        {{ $transaction->description ?? __('No description') }}
                                        <small class="d-block text-muted">{{ $transaction->wallet->name }}</small>
                                    </td>
                                    <td>
                                        <span class="badge" style="background-color: {{ $transaction->category->color ?? '#ccc' }}20; color: {{ $transaction->category->color ?? '#333' }}">
                                            {{ $transaction->category->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="text-right font-weight-bold {{ $transaction->type == 'income' ? 'text-success' : ($transaction->type == 'expense' ? 'text-danger' : 'text-primary') }}">
                                        {{ $transaction->type == 'income' ? '+' : ($transaction->type == 'expense' ? '-' : '') }}
                                        {{ number_format($transaction->amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">{{ __('No recent transactions') }}</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Right col -->
        <section class="col-lg-5 connectedSortable">
             <!-- Quick Actions -->
             <div class="card shadow-none bg-transparent">
                <div class="card-body p-0">
                    <a href="{{ route('transactions.create', ['type' => 'expense']) }}" class="btn btn-app bg-danger">
                        <i class="fas fa-minus"></i> {{ __('Expense') }}
                    </a>
                    <a href="{{ route('transactions.create', ['type' => 'income']) }}" class="btn btn-app bg-success">
                        <i class="fas fa-plus"></i> {{ __('Income') }}
                    </a>
                    <a href="{{ route('wallets.create') }}" class="btn btn-app bg-info">
                        <i class="fas fa-wallet"></i> {{ __('Create Wallet') }}
                    </a>
                </div>
            </div>

            <!-- Expense by Category Chart -->
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-1"></i>
                        {{ __('Expense by Category (This Month)') }}
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="expenseChart" style="min-height: 250px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                </div>
            </div>

            <!-- Wallets List -->
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">{{ __('Wallets List') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('wallets.index') }}" class="btn btn-tool btn-sm">
                            <i class="fas fa-bars"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <ul class="products-list product-list-in-card pl-2 pr-2">
                        @foreach($wallets as $wallet)
                            <li class="item">
                                <div class="product-img">
                                    <div class="float-left d-flex align-items-center justify-content-center bg-light rounded" style="width: 40px; height: 40px;">
                                        <i class="{{ $wallet->icon ?? 'fas fa-wallet' }}" style="color: {{ $wallet->color }}; font-size: 1.2rem;"></i>
                                    </div>
                                </div>
                                <div class="product-info ml-3">
                                    <a href="{{ route('wallets.edit', $wallet) }}" class="product-title text-dark">
                                        {{ $wallet->name }}
                                        <span class="float-right font-weight-bold text-dark">
                                            {{ number_format($wallet->balance, 0, ',', '.') }} ₫
                                        </span>
                                    </a>
                                    <span class="product-description text-muted">
                                        {{ ucfirst($wallet->type) }}
                                    </span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </section>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Trend Chart
            const trendCtx = document.getElementById('trendChart').getContext('2d');
            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($trendLabels) !!},
                    datasets: [{
                        label: 'Thu nhập',
                        data: {!! json_encode($trendIncomeData) !!},
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        fill: true,
                        tension: 0.4
                    }, {
                        label: 'Chi tiêu',
                        data: {!! json_encode($trendExpenseData) !!},
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220, 53, 69, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top' }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { borderDash: [2, 2] } },
                        x: { grid: { display: false } }
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                }
            });

            // Expense Pie Chart
            const expenseCtx = document.getElementById('expenseChart').getContext('2d');
            new Chart(expenseCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($expenseLabels) !!},
                    datasets: [{
                        data: {!! json_encode($expenseData) !!},
                        backgroundColor: {!! json_encode($expenseColors) !!},
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right', labels: { boxWidth: 12 } }
                    },
                    cutout: '70%'
                }
            });
        });
    </script>
    @endpush
</x-app-layout>