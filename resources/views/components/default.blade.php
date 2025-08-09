<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Loomi')</title>
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        @vite(['resources/css/app.scss', 'resources/js/app.js'])
        @yield('links')
    </head>
    <body>
        @include('includes.alert')

        @yield('nav')

        <main class="container my-3">
            <div class="row">
                <div class="col-md-3">
                    @yield('sidebar')
                </div>

                <div class="col-md-9">
                    @yield('mainbar')
                </div>
            </div>

            @yield('content')
        </main>

        @yield('footer')
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        @yield('scripts')
    </body>
</html>