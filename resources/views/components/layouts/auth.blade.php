<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'fa' ? 'rtl' : 'ltr' }}"
    class="h-full light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Authentication</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="h-full">
    <div class="absolute inset-y-0 right-0 -z-10 w-full overflow-hidden bg-[#3b82f6]/10 ring-2 ring-[#3b82f6]/20">
        <svg class="absolute inset-0 h-full w-full stroke-[#3b82f6]/40 [mask-image:radial-gradient(100%_100%_at_top_right,white,transparent)]"
            aria-hidden="true">
            <defs>
                <pattern id="83fd4e5a-9d52-42fc-97b6-718e5d7ee527" width="200" height="200" x="100%" y="-1"
                    patternUnits="userSpaceOnUse">
                    <path d="M130 200V.5M.5 .5H200" fill="none"></path>
                </pattern>
            </defs>
            <rect width="100%" height="100%" stroke-width="0" fill="white"></rect>
            <svg x="100%" y="-1" class="overflow-visible fill-[#3b82f6]/10">
                <path d="M-470.5 0h201v201h-201Z" stroke-width="0"></path>
            </svg>
            <rect width="100%" height="100%" stroke-width="0" fill="url(#83fd4e5a-9d52-42fc-97b6-718e5d7ee527)">
            </rect>
        </svg>
    </div>
    <div class="flex min-h-screen justify-center items-center px-6 py-12 lg:px-8 ">
        <div class="shadow sm:rounded-lg w-fit lg:w-4/12 p-10 bg-base-100">
            <div class="sm:mx-auto sm:w-full sm:max-w-sm">
                <img class="mx-auto h-10 w-auto"
                    src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=600"
                    alt="Your Company">
                <h2 class="mt-10 text-center text-2xl/9 font-bold text-gray-900 dark:text-white">ورود به پنل مدیریت</h2>
            </div>

            <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm ">
                {{ $slot }}
            </div>
        </div>
    </div>

    @livewireScripts
</body>

</html>
