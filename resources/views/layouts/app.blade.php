<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            /* Forzar que el texto blanco se muestre en negro */
            .text-white, .dark .text-white {
                color: #000 !important;
            }
            /* Botones azul marino globales */
            button,
            input[type="submit"],
            a.btn,
            .btn,
            .inline-flex.px-4.py-2,
            .inline-flex.px-2.py-1 {
                background-color: #0b2340 !important; /* azul marino */
                color: #ffffff !important;
                border-color: #0b2340 !important;
            }
            button:hover,
            input[type="submit"]:hover,
            a.btn:hover,
            .inline-flex.px-4.py-2:hover,
            .inline-flex.px-2.py-1:hover {
                background-color: #091a33 !important;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                @isset($slot)
                    {{ $slot }}
                @else
                    @yield('content')
                @endisset
            </main>
        </div>
    </body>
</html>
