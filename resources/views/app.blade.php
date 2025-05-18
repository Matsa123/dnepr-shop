<!DOCTYPE html>
<html lang="uk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        window.cartAddUrl = "{{ route('cart.add') }}";
    </script>
    <title>@yield('title', 'Магазин одягу')</title>
    @vite(['resources/css/app.css'])
    @stack('styles')
</head>

<body>
    <div class="page-wrapper">
        @include('header')

        <main class="content">
            @yield('content')
        </main>

        @include('footer')
    </div>

    @vite(['resources/js/app.js'])
    @stack('scripts')
</body>

</html>