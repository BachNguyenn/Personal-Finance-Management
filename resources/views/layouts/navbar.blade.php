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