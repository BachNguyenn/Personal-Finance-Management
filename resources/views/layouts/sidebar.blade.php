<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
   <!-- Brand Logo -->
   <a href="{{ route('dashboard') }}" class="brand-link">
      <span class="brand-text font-weight-light px-3">{{ config('app.name') }}</span>
   </a>

   <!-- Sidebar -->
   <div class="sidebar">
      <!-- Sidebar Menu -->
      <nav class="mt-3">
         <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
               <a href="{{ route('dashboard') }}"
                  class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-tachometer-alt"></i>
                  <p>{{ __('Dashboard') }}</p>
               </a>
            </li>

            <li class="nav-header">{{ __('Manage') }}</li>

            <li class="nav-item">
               <a href="{{ route('transactions.index') }}"
                  class="nav-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-exchange-alt"></i>
                  <p>{{ __('Transactions') }}</p>
               </a>
            </li>

            <li class="nav-item">
               <a href="{{ route('wallets.index') }}"
                  class="nav-link {{ request()->routeIs('wallets.*') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-wallet"></i>
                  <p>{{ __('Wallets') }}</p>
               </a>
            </li>

            <li class="nav-item">
               <a href="{{ route('categories.index') }}"
                  class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-tags"></i>
                  <p>{{ __('Categories') }}</p>
               </a>
            </li>

            <li class="nav-item">
               <a href="{{ route('budgets.index') }}"
                  class="nav-link {{ request()->routeIs('budgets.*') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-chart-pie"></i>
                  <p>{{ __('Budgets') }}</p>
               </a>
            </li>

            <li class="nav-item">
               <a href="{{ route('savings_goals.index') }}"
                  class="nav-link {{ request()->routeIs('savings_goals.*') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-bullseye"></i>
                  <p>{{ __('Savings Goals') }}</p>
               </a>
            </li>

            <li class="nav-item">
               <a href="{{ route('debts.index') }}"
                  class="nav-link {{ request()->routeIs('debts.*') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-hand-holding-usd"></i>
                  <p>{{ __('Sổ Nợ') }}</p>
               </a>
            </li>

            <li class="nav-item">
               <a href="{{ route('families.index') }}"
                  class="nav-link {{ request()->routeIs('families.*') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-users"></i>
                  <p>{{ __('Gia đình') }}</p>
               </a>
            </li>

            <li class="nav-header">{{ __('Analytics') }}</li>

            <li class="nav-item">
               <a href="{{ route('insights.index') }}"
                  class="nav-link {{ request()->routeIs('insights.*') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-brain text-warning"></i>
                  <p>{{ __('Phân tích Thông minh') }}</p>
               </a>
            </li>

            <li class="nav-item">
               <a href="{{ route('calendar.index') }}"
                  class="nav-link {{ request()->routeIs('calendar.*') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-calendar-alt text-info"></i>
                  <p>{{ __('Lịch Giao dịch') }}</p>
               </a>
            </li>

            <li class="nav-item">
               <a href="{{ route('reports.index') }}"
                  class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-chart-line"></i>
                  <p>{{ __('Advanced Reports') }}</p>
               </a>
            </li>
         </ul>
      </nav>
      <!-- /.sidebar-menu -->
   </div>
   <!-- /.sidebar -->
</aside>