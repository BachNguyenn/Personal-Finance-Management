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
            transition: background-color 0.3s ease;
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
            transition: background-color 0.3s ease, color 0.3s ease;
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
            color: #495057;
        }

        .form-control:focus {
            background: transparent;
            box-shadow: none;
            color: #495057;
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

        .card-primary.card-outline {
            border-top: 3px solid #007bff;
        }

        /* DARK MODE STYLES */
        body.dark-mode {
            background-color: #12141d;
            color: #c2c7d0;
        }

        body.dark-mode .card {
            background-color: #1e222d;
            /* Slightly lighter than body */
            color: #fff;
        }

        body.dark-mode .card-header .h1 {
            color: #fff;
        }

        body.dark-mode .login-box-msg,
        body.dark-mode .register-box-msg {
            color: #bbb;
        }

        body.dark-mode .input-group {
            background-color: #2a303e;
            /* Darker input background */
            border-color: #4b546c;
        }

        body.dark-mode .form-control {
            color: #fff;
        }

        body.dark-mode .form-control:focus {
            color: #fff;
        }

        body.dark-mode .form-control::placeholder {
            color: #adb5bd;
        }

        body.dark-mode .input-group-text {
            color: #adb5bd;
        }

        body.dark-mode .icheck-primary label {
            color: #ccc;
        }
    </style>
    <script>
        // Apply dark mode immediately if saved in localStorage
        if (localStorage.getItem('darkMode') === 'enabled') {
            document.documentElement.classList.add('dark');
            // We also need to add 'dark-mode' class to body for AdminLTE
            document.addEventListener('DOMContentLoaded', (event) => {
                document.body.classList.add('dark-mode');
            });
        }
    </script>
</head>

<body>

    {{ $slot }}

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>