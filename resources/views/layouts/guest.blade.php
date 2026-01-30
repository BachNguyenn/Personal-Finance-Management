<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Personal Finance') }}</title>

    <!-- Google Font: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Theme style (AdminLTE) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #e9ecef;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-box,
        .register-box {
            width: 400px;
        }

        @media (max-width: 576px) {

            .login-box,
            .register-box {
                width: 90%;
            }
        }

        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
            overflow: hidden;
            background: #fff;
        }

        .card-header {
            background: transparent;
            border-bottom: none;
            padding-top: 25px;
            padding-bottom: 0;
        }

        .card-header .h1 {
            color: #333;
            font-weight: 700;
            font-size: 26px;
            text-decoration: none;
        }

        .card-header .h1 b {
            color: #007bff;
        }

        .login-box-msg,
        .register-box-msg {
            color: #666;
            padding: 0 20px 20px;
        }

        .input-group {
            background: #fff;
            border-radius: 4px;
            border: 1px solid #ced4da;
            padding: 0;
            margin-bottom: 15px !important;
            transition: all 0.2s ease;
        }

        .input-group:focus-within {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
        }

        .form-control {
            border: none;
            background: transparent;
            height: 40px;
            padding-left: 10px;
            font-size: 14px;
        }

        .form-control:focus {
            background: transparent;
            box-shadow: none;
        }

        .input-group-text {
            background: transparent;
            border: none;
            color: #adb5bd;
            padding-left: 15px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            border-radius: 4px;
            height: 40px;
            font-weight: 600;
            box-shadow: none;
        }

        .btn-primary:hover {
            background-color: #0069d9;
            border-color: #0062cc;
        }

        .social-auth-links a {
            color: #007bff;
            font-weight: 500;
        }

        .icheck-primary label {
            color: #666;
            font-weight: 400;
            font-size: 14px;
        }

        /* Remove default adminlte borders */
        .card-primary.card-outline {
            border-top: 3px solid #007bff;
        }
    </style>
</head>

<body>

    {{ $slot }}

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>