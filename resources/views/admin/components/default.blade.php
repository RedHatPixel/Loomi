<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Loomi Admin')</title>
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
        @vite(['resources/css/app.scss', 'resources/js/app.js'])
        @yield('links')
    </head>
    <body>
        @include('includes.alert')

        <div class="d-flex">
            {{-- Sidebar --}}
            <nav style="flex-shrink:0">
                @include('admin.components.sidebar')
            </nav>

            {{-- Main --}}
            <main class="flex-grow-1 min-vh-100" style="min-width:0;">
                @yield('content')
            </main>
        </div>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
            AOS.init({
                duration: 700,     
                easing: 'ease-out', 
                offset: 50,         
                disable: window.innerWidth < 768
            });
        });
        </script>
        @yield('scripts')
    </body>
</html>