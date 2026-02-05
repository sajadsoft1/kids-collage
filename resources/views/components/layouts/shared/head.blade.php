<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' - ' . config('app.name') : config('app.name') }}</title>


    <link rel="stylesheet" href="/assets/fonts/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="/assets/fonts/IRANYekan/style.css">

    <link rel="stylesheet" href="/assets/css/cropper.min.css" />
    <script src="/assets/js/cropper.min.js"></script>
    <script src="/assets/js/tinymce/tinymce.min.js"></script>
    <script src="/assets/js/chart.umd.min.js"></script>

    <script src="/assets/js/photoswipe.umd.min.js"></script>
    <script src="/assets/js/photoswipe-lightbox.umd.min.js"></script>
    <link href="/assets/css/photoswipe.min.css" rel="stylesheet">

    <link rel="stylesheet" href="/assets/css/flatpickr.min.css">
    <script src="/assets/js/flatpickr.min.js"></script>
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('style')

    <script>
        // Define mainLayoutState before Alpine initializes
        function mainLayoutState() {
            return {
                init() {
                    // Store is initialized in sidebar component
                }
            }
        }
    </script>
</head>
