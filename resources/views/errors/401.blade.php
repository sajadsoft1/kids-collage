@php
    $title = __('error.401.title');
    $message = __('error.401.message');
    $description = __('error.401.description');
    $isAuthenticated = auth()->check();
@endphp

<x-layouts.error>
    <h1 class="global-title block text-[#adf3ce] mb-[8px] leading-[1.2] md:text-[150px] md:font-extrabold min-[481px]:text-[120px] min-[481px]:font-bold text-[80px] font-semibold">
        <span>4</span><span>0</span><span>1</span>
    </h1>

    <h4 class="block text-[#777] text-center md:text-[30px] leading-[1.2] md:font-semibold min-[481px]:text-[25px] text-[22px] font-medium mb-[15px]">{{ $title }}</h4>

    <p class="block text-center text-[#777] mb-[16px] md:text-[16px] text-[14px]">{{ $message }}<br>{{ $description }}</p>

    <div class="mt-[16px] flex flex-col md:flex-row justify-center items-center gap-3">
        @if ($isAuthenticated)
            <a href="{{ route('admin.dashboard') }}" class="error-btn w-full md:w-auto">
                {{ __('error.back_to_dashboard') }}
            </a>
        @else
            <a href="{{ route('admin.auth.login') }}" class="error-btn w-full md:w-auto">
                {{ __('auth.login') }}
            </a>
        @endif
        <button onclick="window.history.back()" class="error-btn w-full md:w-auto">
            {{ __('error.go_back') }}
        </button>
    </div>
</x-layouts.error>
