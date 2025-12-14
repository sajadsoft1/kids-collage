<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'fa' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' - ' . config('app.name') : config('app.name') }}</title>
    <!-- Font -->
    <link rel="stylesheet" href="/assets/fonts/IRANYekan/style.css">
    <!-- Theme css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/error-css/error-page.css') }}">
    @vite(['resources/css/app.css'])
    @vite(['resources/js/error-parallax.js'])
</head>

<body>
    <!-- 01 Preloader -->
    <div class="loader-wrapper h-screen w-screen flex bg-white items-center justify-center fixed top-0 z-[9]"
        id="loader-wrapper">
        <div class="loader h-[50px] w-[50px] rounded-full"></div>
    </div>
    <!-- Preloader end -->
    <!-- 02 Main page -->
    <section class="bg-center bg-no-repeat bg-cover page-section"
        style="background-image: url('{{ asset('assets/error-images/background-3.png') }}')">
        <div class="px-4 w-full">
            <div class="flex flex-col justify-center items-center h-screen content-detail">
                <img id="shape1" class="parallax" src="{{ asset('assets/error-images/shape1.png') }}" alt="parallax">
                <img id="shape2" class="parallax top" src="{{ asset('assets/error-images/shape2.png') }}"
                    alt="parallax">
                <img id="shape4" class="parallax left top" src="{{ asset('assets/error-images/shape4.png') }}"
                    alt="parallax">
                <img id="shape5" class="parallax left" src="{{ asset('assets/error-images/shape5.png') }}"
                    alt="parallax">
                <img id="shape3" class="parallax top" src="{{ asset('assets/error-images/shape3.png') }}"
                    alt="parallax">
                <img id="shape6" class="parallax top" src="{{ asset('assets/error-images/shape6.png') }}"
                    alt="parallax">
                <img id="shape7" class="parallax" src="{{ asset('assets/error-images/shape7.png') }}"
                    alt="parallax">

                {{ $slot }}
            </div>
        </div>
    </section>
</body>

</html>
