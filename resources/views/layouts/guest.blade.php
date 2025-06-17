<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="{{ asset('/') }}logo/AdroidCMTLogo2.png" rel="icon" />

        <title>{{ config('app.name', 'MYCONF') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

        <style>
            :root {
                --primary-color: #1e88e5;
                --primary-light: #6ab7ff;
                --primary-dark: #005cb2;
                --secondary-color: #f5f5f5;
            }

            body {
                font-family: 'Figtree', sans-serif;
                background-color: #f8f9fa;
            }

            .auth-card {
                border: none;
                border-radius: 10px;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                background: white;
            }

            .auth-header {
                background: linear-gradient(135deg, var(--primary-light), var(--primary-dark));
                color: white;
                padding: 1.5rem;
                border-radius: 10px 10px 0 0;
                text-align: center;
            }

            .auth-logo {
                height: 60px;
                margin-bottom: 1rem;
            }

            .btn-primary {
                background-color: var(--primary-color);
                border-color: var(--primary-color);
            }

            .btn-primary:hover {
                background-color: var(--primary-dark);
                border-color: var(--primary-dark);
            }

            .form-control:focus {
                border-color: var(--primary-light);
                box-shadow: 0 0 0 0.25rem rgba(30, 136, 229, 0.25);
            }

            .auth-link {
                color: var(--primary-color);
                text-decoration: none;
            }

            .auth-link:hover {
                color: var(--primary-dark);
                text-decoration: underline;
            }
        </style>
    </head>
    <body>
        <div class="min-vh-100 d-flex flex-column justify-content-center py-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6">
                        <div class="auth-card">
                            <div class="auth-header">
                                <img src="{{ asset('logo.png') }}" alt="MYCONF Logo" class="auth-logo" onerror="this.style.display='none'">
                                <h2 class="mb-0">{{ $title ?? 'MYCONF' }}</h2>
                            </div>
                            <div class="p-4 p-md-5">
                                {{ $slot }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
