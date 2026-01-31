<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
   <!-- Left navbar links -->
   <ul class="navbar-nav">
      <li class="nav-item">
         <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
         <a href="{{ route('dashboard') }}" class="nav-link">Home</a>
      </li>
   </ul>

   <!-- Right navbar links -->
   <ul class="navbar-nav ml-auto">

      <!-- Dark Mode Toggle -->
      <li class="nav-item">
         <a class="nav-link dark-mode-toggle" href="#" id="darkModeToggle" title="{{ __('Chế độ tối') }}">
            <i class="fas fa-moon" id="darkModeIcon"></i>
         </a>
      </li>

      <!-- Notifications Dropdown -->
      <li class="nav-item dropdown" id="alerts-dropdown">
         <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-bell"></i>
            <span class="badge badge-warning navbar-badge" id="alerts-count" style="display: none;">0</span>
         </a>
         <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <span class="dropdown-header">{{ __('Thông báo') }}</span>
            <div class="dropdown-divider"></div>
            <div id="alerts-list">
               <a href="#" class="dropdown-item text-center text-muted">
                  <i class="fas fa-spinner fa-spin"></i> {{ __('Đang tải...') }}
               </a>
            </div>
            <div class="dropdown-divider"></div>
            <a href="{{ route('alerts.index') }}" class="dropdown-item dropdown-footer">
               {{ __('Xem tất cả') }}
            </a>
         </div>
      </li>

      <!-- User Dropdown Menu -->
      <li class="nav-item dropdown">
         <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-user mr-1"></i>
            <span class="text-dark">{{ Auth::user()->name }}</span>
         </a>
         <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right border-0 shadow-lg">
            <span class="dropdown-header">{{ __('Profile') }}</span>
            <div class="dropdown-divider"></div>
            <a href="{{ route('profile.edit') }}" class="dropdown-item">
               <i class="fas fa-user-cog mr-2"></i> {{ __('Profile') }}
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item text-danger"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
               <i class="fas fa-sign-out-alt mr-2"></i> {{ __('Log Out') }}
            </a>
         </div>
      </li>
   </ul>
</nav>
<!-- /.navbar -->

@push('scripts')
   <script>
      document.addEventListener('DOMContentLoaded', function () {
         // Dark Mode Toggle
         const darkModeToggle = document.getElementById('darkModeToggle');
         const darkModeIcon = document.getElementById('darkModeIcon');
         
         function updateDarkModeIcon() {
            if (document.body.classList.contains('dark-mode')) {
               darkModeIcon.classList.remove('fa-moon');
               darkModeIcon.classList.add('fa-sun');
            } else {
               darkModeIcon.classList.remove('fa-sun');
               darkModeIcon.classList.add('fa-moon');
            }
         }
         
         // Initial icon state
         updateDarkModeIcon();
         
         darkModeToggle.addEventListener('click', function(e) {
            e.preventDefault();
            document.body.classList.toggle('dark-mode');
            
            // Also toggle sidebar
            const sidebar = document.querySelector('.main-sidebar');
            if (document.body.classList.contains('dark-mode')) {
               localStorage.setItem('darkMode', 'enabled');
               if (sidebar) {
                  sidebar.classList.remove('sidebar-dark-primary');
                  sidebar.classList.add('sidebar-dark-navy');
               }
            } else {
               localStorage.setItem('darkMode', 'disabled');
               if (sidebar) {
                  sidebar.classList.remove('sidebar-dark-navy');
                  sidebar.classList.add('sidebar-dark-primary');
               }
            }
            
            updateDarkModeIcon();
         });

         // Load unread alerts count
         fetch('{{ route("alerts.unread-count") }}')
            .then(response => response.json())
            .then(data => {
               const badge = document.getElementById('alerts-count');
               if (data.count > 0) {
                  badge.textContent = data.count;
                  badge.style.display = 'block';
               }
            })
            .catch(err => console.log('Error loading alerts:', err));

         // Load alerts when dropdown is opened
         document.getElementById('alerts-dropdown').addEventListener('show.bs.dropdown', function () {
            fetch('{{ route("alerts.recent") }}')
               .then(response => response.json())
               .then(alerts => {
                  const list = document.getElementById('alerts-list');
                  if (alerts.length === 0) {
                     list.innerHTML = '<a href="#" class="dropdown-item text-center text-muted">{{ __("Không có thông báo") }}</a>';
                     return;
                  }

                  list.innerHTML = alerts.map(alert => {
                     const icon = alert.alert_type === 'exceeded'
                        ? '<i class="fas fa-exclamation-circle text-danger mr-2"></i>'
                        : '<i class="fas fa-exclamation-triangle text-warning mr-2"></i>';
                     const categoryName = alert.budget?.category?.name || 'N/A';
                     const message = alert.alert_type === 'exceeded'
                        ? `Vượt ngân sách "${categoryName}": ${alert.percentage_used}%`
                        : `Cảnh báo "${categoryName}": ${alert.percentage_used}%`;
                     const isRead = alert.is_read ? '' : 'bg-light';

                     return `<a href="#" class="dropdown-item ${isRead}">${icon}<small>${message}</small></a>`;
                  }).join('<div class="dropdown-divider"></div>');
               })
               .catch(err => console.log('Error loading alerts:', err));
         });
      });
   </script>
@endpush