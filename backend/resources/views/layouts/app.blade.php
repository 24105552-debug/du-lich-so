<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Quản trị') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Tailwind CSS CDN (Chống vỡ giao diện) -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                darkMode: 'class',
            }
        </script>

        <!-- CSS KHỐNG CHẾ CỨNG KÍCH THƯỚC SVG (CHỐNG PHÌNH LOGO & ICON) -->
        <style>
            svg {
                display: inline-block !important;
                vertical-align: middle !important;
                max-width: 48px !important;
                max-height: 48px !important;
                width: auto;
                height: auto;
            }
            svg.w-4, .w-4 > svg { width: 1rem !important; height: 1rem !important; max-width: 1rem !important; max-height: 1rem !important; }
            svg.w-5, .w-5 > svg { width: 1.25rem !important; height: 1.25rem !important; max-width: 1.25rem !important; max-height: 1.25rem !important; }
            svg.w-6, .w-6 > svg { width: 1.5rem !important; height: 1.5rem !important; max-width: 1.5rem !important; max-height: 1.5rem !important; }
            svg.w-7, .w-7 > svg { width: 1.75rem !important; height: 1.75rem !important; max-width: 1.75rem !important; max-height: 1.75rem !important; }
            svg.w-8, .w-8 > svg { width: 2rem !important; height: 2rem !important; max-width: 2rem !important; max-height: 2rem !important; }
            svg.w-10, .w-10 > svg { width: 2.5rem !important; height: 2.5rem !important; max-width: 2.5rem !important; max-height: 2.5rem !important; }
            svg.w-12, .w-12 > svg { width: 3rem !important; height: 3rem !important; max-width: 3rem !important; max-height: 3rem !important; }
            svg.w-16, .w-16 > svg { width: 4rem !important; height: 4rem !important; max-width: 4rem !important; max-height: 4rem !important; }
            svg.w-20, .w-20 > svg { width: 5rem !important; height: 5rem !important; max-width: 5rem !important; max-height: 5rem !important; }
        </style>

        <!-- SCRIPT XỬ LÝ LƯU TRẠNG THÁI DARK MODE -->
        <script>
            if (localStorage.getItem('theme') === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>

        <!-- Alpine.js CDN -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <!-- Scripts & Styles via Vite -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100 transition-colors duration-200">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 transition-colors duration-200">
                    <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- Hàm xử lý bật/tắt dark mode -->
        <script>
            function toggleDarkMode() {
                document.documentElement.classList.toggle('dark');

                if (document.documentElement.classList.contains('dark')) {
                    localStorage.setItem('theme', 'dark');
                } else {
                    localStorage.setItem('theme', 'light');
                }
            }
        </script>
    </body>
</html>
