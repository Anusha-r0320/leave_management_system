
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Figtree', sans-serif; }
            .main-wrapper {
                min-height: 100vh;
                display: flex;
                flex-direction: column;
            }
        </style>
    </head>
    <body class="antialiased bg-light">
        <div class="main-wrapper">

            @include('layouts.navigation')

            @isset($header)
                <header class="bg-white shadow-sm border-bottom">
                    <div class="container py-3">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="py-4 flex-grow-1">
                @yield('content')
            </main>

        </div>

        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

        @stack('scripts')
    </body>
</html>
