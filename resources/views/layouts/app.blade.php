<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Personal Finance') }}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Theme style (AdminLTE) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <!-- Flag Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css">

    @stack('styles')

    <!-- Custom CSS -->
    <style>
        .main-header {
            border-bottom: 0;
            box-shadow: none;
        }

        .content-wrapper {
            background-color: #f4f6f9;
        }

        .card {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            border: 0;
            border-radius: 0.5rem;
        }

        .card-header {
            background-color: transparent;
            border-bottom: 0;
            font-weight: 600;
            padding-top: 1.25rem;
        }

        .brand-link {
            border-bottom: 0;
        }

        .elevation-4 {
            box-shadow: none !important;
        }

        .nav-link.active {
            background-color: #007bff !important;
            color: #fff !important;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .btn {
            border-radius: 0.375rem;
        }

        .form-control {
            border-radius: 0.375rem;
        }

        /* Dark Mode Toggle Button */
        .dark-mode-toggle {
            cursor: pointer;
            padding: 8px;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .dark-mode-toggle:hover {
            transform: rotate(20deg);
            color: #ffc107 !important;
        }

        /* ============================================
           DARK MODE COMPREHENSIVE STYLES
           ============================================ */

        /* Main Layout */
        .dark-mode .content-wrapper {
            background-color: #1a1a2e !important;
        }

        .dark-mode .main-header {
            background-color: #16213e !important;
            border-bottom: 1px solid #3a3a5c !important;
        }

        .dark-mode .main-header.navbar-light {
            background-color: #16213e !important;
        }

        .dark-mode .navbar-light .navbar-nav .nav-link {
            color: #e4e4e4 !important;
        }

        .dark-mode .navbar-light .navbar-nav .nav-link:hover {
            color: #fff !important;
        }

        /* Cards */
        .dark-mode .card {
            background-color: #16213e !important;
            color: #e4e4e4;
            border-color: #3a3a5c;
        }

        .dark-mode .card-header {
            background-color: transparent;
            color: #e4e4e4;
            border-color: #3a3a5c;
        }

        .dark-mode .card-footer {
            background-color: rgba(0, 0, 0, 0.1);
            border-color: #3a3a5c;
        }

        .dark-mode .card-outline {
            border-top-width: 3px;
        }

        /* Tables */
        .dark-mode .table {
            color: #e4e4e4;
        }

        .dark-mode .table thead th,
        .dark-mode .table td,
        .dark-mode .table th {
            border-color: #3a3a5c;
        }

        .dark-mode .table-hover tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.05);
            color: #fff;
        }

        .dark-mode .thead-light th {
            background-color: #1a1a2e !important;
            color: #e4e4e4 !important;
        }

        /* Forms */
        .dark-mode .form-control {
            background-color: #1a1a2e !important;
            border-color: #3a3a5c !important;
            color: #e4e4e4 !important;
        }

        .dark-mode .form-control:focus {
            background-color: #1a1a2e !important;
            border-color: #007bff !important;
            color: #fff !important;
        }

        .dark-mode .form-control::placeholder {
            color: #888 !important;
        }

        .dark-mode .input-group-text {
            background-color: #2a2a4e;
            border-color: #3a3a5c;
            color: #e4e4e4;
        }

        .dark-mode select.form-control {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'%3e%3cpath fill='%23e4e4e4' d='M2 0L0 2h4zm0 5L0 3h4z'/%3e%3c/svg%3e");
        }

        .dark-mode .custom-control-label::before {
            background-color: #1a1a2e;
            border-color: #3a3a5c;
        }

        /* Dropdowns */
        .dark-mode .dropdown-menu {
            background-color: #16213e;
            border-color: #3a3a5c;
        }

        .dark-mode .dropdown-item {
            color: #e4e4e4;
        }

        .dark-mode .dropdown-item:hover,
        .dark-mode .dropdown-item:focus {
            background-color: #1a1a2e;
            color: #fff;
        }

        .dark-mode .dropdown-header {
            color: #a0a0a0;
        }

        .dark-mode .dropdown-divider {
            border-color: #3a3a5c;
        }

        /* Modals */
        .dark-mode .modal-content {
            background-color: #16213e;
            border-color: #3a3a5c;
        }

        .dark-mode .modal-header {
            border-color: #3a3a5c;
            color: #e4e4e4;
        }

        .dark-mode .modal-footer {
            border-color: #3a3a5c;
        }

        .dark-mode .close {
            color: #e4e4e4;
            text-shadow: none;
        }

        /* Lists */
        .dark-mode .list-group-item {
            background-color: #16213e;
            border-color: #3a3a5c;
            color: #e4e4e4;
        }

        .dark-mode .list-group-item.bg-light {
            background-color: #1a1a2e !important;
        }

        /* Nav Tabs */
        .dark-mode .nav-tabs {
            border-color: #3a3a5c;
        }

        .dark-mode .nav-tabs .nav-link {
            color: #a0a0a0;
        }

        .dark-mode .nav-tabs .nav-link:hover {
            border-color: #3a3a5c;
            color: #e4e4e4;
        }

        .dark-mode .nav-tabs .nav-link.active {
            background-color: #16213e;
            border-color: #3a3a5c #3a3a5c #16213e;
            color: #fff;
        }

        /* Alerts */
        .dark-mode .alert-success {
            background-color: #0d5c3d;
            border-color: #0a4f34;
            color: #a5e9c5;
        }

        .dark-mode .alert-danger {
            background-color: #7d1a1a;
            border-color: #6a1616;
            color: #f5c6c6;
        }

        .dark-mode .alert-info {
            background-color: #1a4a6e;
            border-color: #164060;
            color: #a5d4f7;
        }

        .dark-mode .alert-warning {
            background-color: #6e5620;
            border-color: #5c481a;
            color: #f7e7a5;
        }

        /* Badges */
        .dark-mode .badge-light {
            background-color: #3a3a5c;
            color: #e4e4e4;
        }

        /* Progress bars */
        .dark-mode .progress {
            background-color: #1a1a2e;
        }

        /* Footer */
        .dark-mode .main-footer {
            background-color: #16213e;
            border-color: #3a3a5c;
            color: #a0a0a0;
        }

        /* Small boxes (dashboard) */
        .dark-mode .small-box {
            color: #fff;
        }

        .dark-mode .small-box .icon {
            color: rgba(255, 255, 255, 0.15);
        }

        /* Info boxes */
        .dark-mode .info-box {
            background-color: #16213e;
            color: #e4e4e4;
        }

        /* Text overrides */
        .dark-mode .text-dark {
            color: #e4e4e4 !important;
        }

        .dark-mode .text-muted {
            color: #a0a0a0 !important;
        }

        .dark-mode h1,
        .dark-mode h2,
        .dark-mode h3,
        .dark-mode h4,
        .dark-mode h5,
        .dark-mode h6 {
            color: #e4e4e4;
        }

        .dark-mode label {
            color: #e4e4e4;
        }

        .dark-mode hr {
            border-color: #3a3a5c;
        }

        /* Scrollbar */
        .dark-mode ::-webkit-scrollbar {
            width: 8px;
        }

        .dark-mode ::-webkit-scrollbar-track {
            background: #1a1a2e;
        }

        .dark-mode ::-webkit-scrollbar-thumb {
            background: #3a3a5c;
            border-radius: 4px;
        }

        .dark-mode ::-webkit-scrollbar-thumb:hover {
            background: #4a4a7c;
        }

        /* Sidebar in dark mode */
        .dark-mode .main-sidebar {
            background-color: #0f0f1a !important;
        }

        .dark-mode .sidebar {
            background-color: #0f0f1a !important;
        }

        .dark-mode .brand-link {
            border-bottom-color: #3a3a5c !important;
        }

        /* Select2 dark mode (if used) */
        .dark-mode .select2-container--default .select2-selection--single {
            background-color: #1a1a2e;
            border-color: #3a3a5c;
        }

        .dark-mode .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #e4e4e4;
        }

        /* Calendar/Date picker */
        .dark-mode .datepicker {
            background-color: #16213e;
        }

        /* Transition for smooth mode switching */
        body {
            transition: background-color 0.3s ease;
        }

        .dark-mode * {
            transition: background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease;
        }
    </style>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <script>
        // Load dark mode preference immediately to prevent flash
        if (localStorage.getItem('darkMode') === 'enabled') {
            document.body.classList.add('dark-mode');
        }
    </script>
    <div class="wrapper">

        <!-- Navbar -->
        @include('layouts.navbar')
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        @include('layouts.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">{{ $header ?? '' }}</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- Flash Messages -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                            <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                            <i class="bi bi-exclamation-circle me-2"></i> {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    {{ $slot }}
                </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer border-0">
            <!-- Footer removed -->
        </footer>

    </div>
    <!-- ./wrapper -->

    <!-- User Menu Script for Logout -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

    @stack('scripts')
</body>

</html>