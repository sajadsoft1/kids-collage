<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'fa' ? 'rtl' : 'ltr' }}"
    class="h-full light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} - Authentication</title>
    <meta name="description" content="Secure authentication for {{ config('app.name') }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <!-- Additional Styles -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Dark mode scrollbar */
        .dark ::-webkit-scrollbar-track {
            background: #374151;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: #6b7280;
        }

        .dark ::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }
    </style>
</head>

<body class="h-full bg-gray-50 dark:bg-gray-900">
    <!-- Background Pattern -->
    <div class="fixed top-0 right-0 bottom-0 left-0 -z-10 overflow-hidden">
        <div
            class="absolute top-0 right-0 bottom-0 left-0 bg-gradient-to-br from-blue-50 via-white to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        </div>
        <div class="absolute top-0 right-0 bottom-0 left-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60"
            viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg
            fill="%239C92AC" fill-opacity="0.05"%3E%3Ccircle cx="30" cy="30" r="2"
            /%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-50"></div>
    </div>

    <!-- Main Content -->
    <div class="min-h-screen flex items-center justify-center p-4">
        {{ $slot }}
    </div>

    <!-- Toast Notifications -->
    <x-toast position="toast-top toast-center" />

    @livewireScripts

    <!-- Additional Scripts -->
    <script>
        // Auto-hide toasts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const toasts = document.querySelectorAll('[data-toast]');
                toasts.forEach(toast => {
                    if (toast.style.display !== 'none') {
                        toast.style.display = 'none';
                    }
                });
            }, 5000);
        });
    </script>
</body>

</html>
