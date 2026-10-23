<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Đặc sản Việt') }} - Đăng nhập</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Tải Tailwind CSS qua CDN (Đảm bảo 100% không bao giờ bị mất CSS) -->
        <script src="https://cdn.tailwindcss.com"></script>

        <!-- Scripts & Styles qua Vite -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* Khống chế kích thước Logo SVG tuyệt đối, chống tràn màn hình */
            svg {
                max-width: 100%;
            }
            .guest-logo svg, .guest-logo img {
                width: 70px !important;
                height: 70px !important;
                max-width: 70px !important;
                max-height: 70px !important;
                object-fit: contain !important;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-100 min-h-screen flex flex-col justify-center items-center py-8">
        <div class="w-full sm:max-w-md flex flex-col items-center px-4">
            
            <!-- Logo -->
            <div class="guest-logo mb-4">
                <a href="/">
                    <x-application-logo class="w-16 h-16 fill-current text-emerald-800" />
                </a>
            </div>

            <!-- Khung Form Đăng nhập / Đăng ký -->
            <div class="w-full bg-white shadow-lg overflow-hidden sm:rounded-2xl px-6 py-8 border border-gray-200">
                {{ $slot }}
            </div>

        </div>
    </body>
</html>
