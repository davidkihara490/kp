<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ config('app.name') }} — Professional Courier Service</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" />
    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('logo.jpeg') }}" />

    <!-- Styles -->
    @include('layouts.partials.styles')

    <!-- Page Specific Styles -->
    @stack('styles')
</head>

<body>
    <!-- Reading Progress Bar (only on blog detail) -->
    <!-- @yield('reading-progress') -->

    <!-- @include('layouts.partials.header') -->
    <main>
        @yield('client-content')
    </main>
    <!-- @include('layouts.partials.footer')

    @include('layouts.partials.whatsapp')

    @include('layouts.partials.scripts') -->

    <!-- @stack('scripts') -->
</body>

</html>