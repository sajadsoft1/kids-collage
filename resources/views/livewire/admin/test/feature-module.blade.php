@push('style')
<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }

    @keyframes float {
        0%, 100% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-20px);
        }
    }

    .animate-fadeInUp {
        animation: fadeInUp 1s ease-out forwards;
    }

    .animate-pulse-slow {
        animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    .animate-float {
        animation: float 3s ease-in-out infinite;
    }

    .delay-100 {
        animation-delay: 0.1s;
    }

    .delay-200 {
        animation-delay: 0.2s;
    }

    .delay-300 {
        animation-delay: 0.3s;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
</style>
@endpush
<div class="h-full bg-gradient-to-br bg-base-200 flex items-center justify-center overflow-hidde mx-auto my-auto">

    <!-- Main Content -->
    <div class="relative z-10 text-center px-6 max-w-2xl mx-auto my-auto">

        <!-- Icon/Logo -->
        <div class="mb-8 animate-fadeInUp opacity-0">
            <div class="inline-block p-6 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-3xl shadow-2xl transform hover:scale-110 transition-transform duration-300">
                <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
        </div>

        <!-- Main Title -->
        <h1 class="text-5xl md:text-7xl font-bold mb-6 bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent animate-fadeInUp opacity-0 delay-100">
            به زودی...
        </h1>

        <!-- Subtitle -->
        <p class="text-xl md:text-2xl text-gray-600 mb-4 animate-fadeInUp opacity-0 delay-200">
            ما در حال ساخت چیزی فوق‌العاده هستیم
        </p>

        <p class="text-lg text-gray-500 mb-12 animate-fadeInUp opacity-0 delay-300">
            این صفحه به زودی آماده خواهد شد
        </p>

        <!-- Animated Dots -->
        <div class="flex justify-center gap-2 mb-12 animate-fadeInUp opacity-0 delay-300">
            <div class="w-3 h-3 bg-indigo-500 rounded-full animate-pulse-slow"></div>
            <div class="w-3 h-3 bg-purple-500 rounded-full animate-pulse-slow" style="animation-delay: 0.2s;"></div>
            <div class="w-3 h-3 bg-pink-500 rounded-full animate-pulse-slow" style="animation-delay: 0.4s;"></div>
        </div>

        <!-- Contact Info (Optional) -->
        <div class="animate-fadeInUp opacity-0 delay-300">
            <p class="text-gray-500 mb-6">برای اطلاعات بیشتر با ما در تماس باشید</p>
            <div class="flex justify-center gap-6">
                <a href="mailto:info@example.com" class="text-gray-600 hover:text-indigo-600 transition-colors duration-300">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                    </svg>
                </a>
                <a href="#" class="text-gray-600 hover:text-indigo-600 transition-colors duration-300">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.477 2 2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.879V14.89h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.989C18.343 21.129 22 16.99 22 12c0-5.523-4.477-10-10-10z"/>
                    </svg>
                </a>
                <a href="#" class="text-gray-600 hover:text-indigo-600 transition-colors duration-300">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/>
                    </svg>
                </a>
            </div>
        </div>

    </div>
</div>