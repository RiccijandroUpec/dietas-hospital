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
        
        <!-- Google AdSense -->
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-6769013572390921"
             crossorigin="anonymous"></script>
        <style>
            /* Alpine.js cloak */
            [x-cloak] { display: none !important; }
            
            /* Forzar que el texto blanco se muestre en negro */
            .text-white, .dark .text-white {
                color: #000 !important;
            }
            /* Botones azul marino globales - excepto botones con colores específicos */
            button:not(.bg-indigo-50):not(.bg-indigo-100):not(.bg-indigo-600):not(.bg-red-600):not(.bg-green-600):not(.bg-blue-600):not(.bg-pink-600):not(.bg-gradient-to-r),
            input[type="submit"]:not(.bg-indigo-50):not(.bg-indigo-100):not(.bg-indigo-600):not(.bg-red-600):not(.bg-green-600):not(.bg-blue-600):not(.bg-pink-600),
            a.btn,
            .btn,
            .inline-flex.px-4.py-2:not(.bg-indigo-50):not(.bg-indigo-100):not(.bg-indigo-600):not(.bg-red-600):not(.bg-green-600):not(.bg-blue-600):not(.bg-pink-600):not(.bg-gradient-to-r),
            .inline-flex.px-2.py-1:not(.bg-indigo-50):not(.bg-indigo-100):not(.bg-indigo-600):not(.bg-red-600):not(.bg-green-600):not(.bg-blue-600):not(.bg-pink-600) {
                background-color: #0b2340 !important; /* azul marino */
                color: #ffffff !important;
                border-color: #0b2340 !important;
            }
            button:hover:not(.bg-indigo-50):not(.bg-indigo-100):not(.bg-indigo-600):not(.bg-red-600):not(.bg-green-600):not(.bg-blue-600):not(.bg-pink-600):not(.bg-gradient-to-r),
            input[type="submit"]:hover:not(.bg-indigo-50):not(.bg-indigo-100):not(.bg-indigo-600):not(.bg-red-600):not(.bg-green-600):not(.bg-blue-600):not(.bg-pink-600),
            a.btn:hover,
            .inline-flex.px-4.py-2:hover:not(.bg-indigo-50):not(.bg-indigo-100):not(.bg-indigo-600):not(.bg-red-600):not(.bg-green-600):not(.bg-blue-600):not(.bg-pink-600):not(.bg-gradient-to-r),
            .inline-flex.px-2.py-1:hover:not(.bg-indigo-50):not(.bg-indigo-100):not(.bg-indigo-600):not(.bg-red-600):not(.bg-green-600):not(.bg-blue-600):not(.bg-pink-600) {
                background-color: #091a33 !important;
            }

            /* Mejora de responsividad global */
            @media (max-width: 640px) {
                /* Tablas compactas en móviles */
                table {
                    font-size: 0.75rem;
                    width: 100%;
                }
                table th,
                table td {
                    padding: 0.4rem 0.5rem !important;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                }
                table th { font-size: 0.65rem; }
                .overflow-x-auto {
                    overflow-x: auto;
                    -webkit-overflow-scrolling: touch;
                }
                /* Encabezados y tarjetas */
                header .py-6 { padding-top: 1rem; padding-bottom: 1rem; }
                h1, h2 { font-size: 1.25rem; }
                h3 { font-size: 1.125rem; }
                .sm\:rounded-lg { border-radius: 0.5rem; }
                /* Grids apiladas */
                .grid { gap: 0.75rem; }
                .px-6 { padding-left: 1rem; padding-right: 1rem; }
                .lg\:px-8 { padding-left: 1rem; padding-right: 1rem; }
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        @include('layouts.navigation')
        {{-- Scripts adicionales inyectados desde las vistas --}}
        @stack('scripts')
    </body>
</html>
