<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('ui/css/app.css') }}">
    
    <title>@yield('title', config('app.name', 'Karibu Parcels'))</title>
    
    <!-- SEO Meta Tags -->
    @yield('meta')
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.jpeg') }}">
    
    <!-- Styles -->
    @include('layouts.partials.styles')
    
    <!-- Page Specific Styles -->
    @stack('styles')
</head>
<body>
    <!-- Reading Progress Bar (only on blog detail) -->
    @yield('reading-progress')
    
    <!-- Header -->
    @include('layouts.partials.header')
    
    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
    
    <!-- Footer -->
    @include('layouts.partials.footer')
    
    <!-- WhatsApp Button -->
    @include('layouts.partials.whatsapp')
    
    <!-- Scripts -->
    @include('layouts.partials.scripts')
    
    <!-- Page Specific Scripts -->
    @stack('scripts')
</body>
</html>