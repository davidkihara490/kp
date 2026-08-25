<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — Professional Courier Service</title>
    <meta name="description" content="Karibu Parcels - Professional courier services for individuals and businesses. Send parcels across Kenya with ease." />

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" />
    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('logo.jpeg') }}" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Livewire Styles -->
    @livewireStyles

    <!-- Custom Styles -->
    <style>
        :root {
            --primary-color: #008f40;
            --primary-dark: #007a36;
            --primary-light: #e8f5e9;
            --accent-color: #ff3519;
            --accent-dark: #e62e15;
            --accent-light: #ffe9e5;
            --light-bg: #f8f9fa;
            --dark-bg: #212529;
            --text-dark: #343a40;
            --text-light: #6c757d;
            --border-color: #e9ecef;
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.05);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.05);
            --shadow-hover: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            background: var(--light-bg);
            padding-top: 0;
            overflow-x: hidden;
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--light-bg);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }

        /* Selection Color */
        ::selection {
            background: var(--primary-color);
            color: white;
        }

        /* Link Styles */
        a {
            text-decoration: none;
            transition: var(--transition);
        }

        a:hover {
            color: var(--primary-color);
        }

        /* Utility Classes */
        .text-primary-custom {
            color: var(--primary-color);
        }

        .bg-primary-custom {
            background-color: var(--primary-color);
        }

        .bg-primary-light {
            background-color: var(--primary-light);
        }

        .section-padding {
            padding: 80px 0;
        }

        .section-padding-sm {
            padding: 50px 0;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .animate-fade-up-delay-1 {
            animation-delay: 0.1s;
            opacity: 0;
        }

        .animate-fade-up-delay-2 {
            animation-delay: 0.2s;
            opacity: 0;
        }

        .animate-fade-up-delay-3 {
            animation-delay: 0.3s;
            opacity: 0;
        }

        .animate-fade-up-delay-4 {
            animation-delay: 0.4s;
            opacity: 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .section-padding {
                padding: 50px 0;
            }

            .section-padding-sm {
                padding: 30px 0;
            }
        }
    </style>

    <!-- Page Specific Styles -->
    @stack('styles')
</head>

<body>
    <!-- Preloader (Optional) -->
    @include('pages.clients.layouts.partials.preloader')

    <!-- Navigation -->
    @include('pages.clients.layouts.partials.navigation')

    <!-- Main Content -->
    <main>
        @yield('client-content')
    </main>

    <!-- Footer -->
    @include('pages.clients.layouts.partials.footer')

    <!-- WhatsApp Floating Button -->
    @include('pages.clients.layouts.partials.whatsapp')

    <!-- Back to Top Button -->
    @include('pages.clients.layouts.partials.back-to-top')

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Livewire Scripts -->
    @livewireScripts

    <!-- Custom Scripts -->
    @include('pages.clients.layouts.partials.scripts')

    <!-- Page Specific Scripts -->
    @stack('scripts')
</body>

</html>