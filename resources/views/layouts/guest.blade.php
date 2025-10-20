<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" href="aset/buku.webp">

        {{-- <title>{{ config('app.name', 'Laravel') }}</title> --}}

        <title>Perpustakaan-Pesat Guest</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-row justify-around dark:text-white items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
            
                <div class="w-full justify-center max-w-xl hidden lg:block">
                    {{-- <x-application-logo class="w-20 h-20 fill-current text-gray-500" /> --}}

                    <img src="/aset/library.webp" alt="Image" loading="lazy">
                    {{-- <img src="/aset/login-register_pictures.png" alt="Image" loading="lazy"> --}}

                </div>

            <div class="lg:w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-[#ffc000] shadow-md overflow-hidden rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
