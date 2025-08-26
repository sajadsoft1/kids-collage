<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'fa' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('100_100.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/web/vendor/swiper/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/web/vendor/font-awesome/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/web/vendor/slim-select/slimselect.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('assets/web/css/output.css') }}"> --}}
    @vite(['resources/css/web.css'])
    {{-- <link rel="stylesheet" href="{{ asset('assets/web/css/style.css') }}"> --}}
    {!! app('seotools')->generate() !!}
    {{-- @livewireStyles --}}
</head>

<body>
    <div class="ed-overlay group">
        <div
            class="fixed inset-0 z-[100] group-[.active]:bg-edblue/80 duration-[400ms] pointer-events-none group-[.active]:pointer-events-auto">
        </div>
    </div>

    <!-- cart -->
    <div class="ed-cart-bar group">
        <div
            class="w-[420px] max-w-full fixed z-[100] right-0 top-0 h-full bg-white flex flex-col translate-x-[100%] duration-[400ms] group-[.active]:translate-x-0">
            <!-- heading -->
            <div class="flex items-center justify-between px-[25px] border-b border-edgray/20 pb-[23px] pt-[22px]">
                <h5 class="text-[20px]">My Cart List</h5>
                <h6>(03 Items)</h6>
            </div>

            <!-- cart items -->
            <div>
                <!-- single cart item -->
                @foreach (range(1, 3) as $index)
                    <div class="flex items-center gap-[20px] py-[30px] px-[25px] border-b border-edgray/20">
                        <img src="/assets/web/img/cart-item-{{ $index }}.jpg" alt="Cart Item Image"
                            class="rounded-[10px] shrink-0">
                        <div class="grow">
                            <h6 class="font-medium text-[18px] text-edblue"><a href="#"
                                    class="hover:text-edpurple">Web Development</a></h6>
                            <h6 class="font-medium text-edgray">$15.00</h6>
                        </div>
                        <button class="text-[20px] text-edgray shrink-0 hover:text-edpurple">x</button>
                    </div>
                @endforeach
            </div>

            <!-- cart bottom -->
            <div class="mt-auto px-[25px] mb-[30px]">
                <div class="flex items-center justify-between font-medium text-[18px] text-edblue mb-[33px]">
                    <span>Total</span>
                    <span>$999</span>
                </div>
                <div class="space-y-[15px]">
                    <a href="#"
                        class="ed-btn w-full !rounded-[10px] !bg-transparent border border-edblue !text-edblue hover:!bg-edblue hover:!text-white">Proceed
                        to checkout</a>
                    <a href="#" class="ed-btn w-full !rounded-[10px]">Proceed to checkout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- search -->
    <div class="ed-search group">
        <form action="#"
            class="bg-white fixed z-[100] top-[50%] left-[50%] -translate-x-[50%] -translate-y-[50%] h-[100px] md:h-[70px] xxs:h-[50px] w-[1224px] max-w-[95%] flex gap-[10px] rounded-full overflow-hidden px-[40px] xxs:px-[20px] pointer-events-none opacity-0 group-[.active]:pointer-events-auto group-[.active]:opacity-100 duration-[400ms]">
            <input type="search" name="ed-search" placeholder="Search Here..."
                class="w-full bg-transparent focus:outline-none">
            <button class="text-[25px] md:text-[22px] xxs:text-[20px]"><i
                    class="fa-solid fa-magnifying-glass"></i></button>
        </form>
    </div>

    <!-- sidebar -->
    <div class="ed-sidebar">
        <div
            class="translate-x-[100%] transition-transform ease-linear duration-300 fixed right-0 w-full max-w-[25%] xl:max-w-[30%] lg:max-w-[40%] md:max-w-[50%] sm:max-w-[60%] xxs:max-w-full bg-white h-full z-[100] overflow-auto">
            <!-- heading -->
            <div class="ed-sidebar-heading p-[20px] lg:p-[20px] border-b border-edgray/20">
                <div class="flex items-center justify-between logo">
                    <a href="{{ localized_route('home-page') }}"><img src="/100_100.png" class="w-10 h-10"
                            alt="logo"></a>

                    <button type="button"
                        class="ed-sidebar-close-btn border border-edgray/20 w-[45px] aspect-square shrink-0 text-black text-[22px] rounded-full hover:text-edpurple"><i
                            class="fa-solid fa-xmark"></i></button>
                </div>
            </div>

            <!-- mobile menu -->
            <div class="ed-header-nav-in-mobile"></div>
        </div>
    </div>

    @include('web.layouts.header')

    <main>
        {{ $slot }}
    </main>
    @include('web.layouts.footer')

    <script src="{{ asset('assets/web/vendor/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/web/vendor/fslightbox/fslightbox.js') }}"></script>
    <script src="{{ asset('assets/web/vendor/mixitup/mixitup.min.js') }}"></script>
    <script src="{{ asset('assets/web/vendor/slim-select/slimselect.min.js') }}"></script>
    <script src="{{ asset('assets/web/js/main.js') }}"></script>
    <script src="{{ asset('assets/web/js/header-2.js') }}"></script>
    <script src="{{ asset('assets/web/js/accordion.js') }}"></script>
    {{-- @vite(['resources/js/web/web.js']) --}}
    {{-- @livewireScripts --}}
</body>

</html>
