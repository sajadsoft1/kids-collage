{{-- Mobile Header (lg:hidden) --}}
<header
    class="flex lg:hidden items-center fixed z-10 top-0 start-0 end-0 shrink-0 bg-[#F6F6F9] dark:bg-[#1e1e2d] h-[60px]"
    id="header">
    <div class="container-fluid flex items-center justify-between flex-wrap gap-3">

        <!-- Logo -->
        <a href="{{ route('admin.dashboard') }}">
            <img class="dark:hidden min-h-[30px]" src="{{ asset('assets/media/app/mini-logo-gray.svg') }}" alt="Logo">
            <img class="hidden dark:block min-h-[30px]" src="{{ asset('assets/media/app/mini-logo-gray-dark.svg') }}"
                alt="Logo">
        </a>

        <!-- Mobile Menu Toggle -->
        <button @click="toggleSidebar()" class="btn btn-icon btn-light btn-clear btn-sm -me-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

    </div>
</header>
