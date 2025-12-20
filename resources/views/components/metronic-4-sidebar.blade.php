{{-- Sidebar: fixed top-0 bottom-0 hidden lg:flex --}}
<div id="sidebar" 
    class="fixed top-0 bottom-0 z-20 items-stretch shrink-0 w-[290px] bg-[#F6F6F9] dark:bg-[#1e1e2d]"
    :class="sidebarOpen ? 'flex' : 'hidden lg:flex'"
    x-show="sidebarOpen || window.innerWidth >= 1024"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform -translate-x-full"
    x-transition:enter-end="opacity-100 transform translate-x-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform translate-x-0"
    x-transition:leave-end="opacity-0 transform -translate-x-full">

    {{-- Sidebar Primary (Icon Bar - 70px) --}}
    <div class="flex flex-col items-stretch shrink-0 gap-5 py-5 w-[70px] border-e border-gray-300 dark:border-gray-700" id="sidebar_primary">
        
        {{-- Logo (Desktop Only) --}}
        <div class="hidden lg:flex items-center justify-center shrink-0" id="sidebar_primary_header">
            <a href="{{ route('admin.dashboard') }}">
                <img class="dark:hidden min-h-[30px]" src="{{ asset('assets/media/app/mini-logo-gray.svg') }}" alt="Logo">
                <img class="hidden dark:block min-h-[30px]" src="{{ asset('assets/media/app/mini-logo-gray-dark.svg') }}" alt="Logo">
            </a>
        </div>

        {{-- Primary Menu Icons --}}
        <div class="flex grow shrink-0" id="sidebar_primary_content">
            <div class="grow gap-2.5 shrink-0 flex ps-4 flex-col overflow-y-auto">
                
                <!-- Dashboard Icon -->
                <a href="{{ route('admin.dashboard') }}" 
                    class="btn btn-icon rounded-md size-9 border border-transparent text-gray-600 hover:bg-light hover:text-primary hover:border-gray-200 {{ request()->routeIs('admin.dashboard') ? 'bg-light text-primary border-gray-200' : '' }}"
                    title="Dashboard">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </a>

                <!-- Network Icon -->
                <a href="#" 
                    class="btn btn-icon rounded-md size-9 border border-transparent text-gray-600 hover:bg-light hover:text-primary hover:border-gray-200"
                    title="Network">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </a>

            </div>
        </div>

        {{-- Primary Footer --}}
        <div class="flex flex-col gap-5 items-center shrink-0" id="sidebar_primary_footer">
            <div class="flex flex-col gap-1.5">
                
                <!-- Messages Icon -->
                <button class="btn btn-icon relative rounded-md size-9 border border-transparent hover:bg-light hover:text-primary hover:border-gray-200 text-gray-600"
                    title="Messages">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                </button>

                <!-- Settings Icon -->
                <button class="btn btn-icon relative rounded-md size-9 border border-transparent hover:bg-light hover:text-primary hover:border-gray-200 text-gray-600"
                    title="Settings">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </button>

                <!-- Theme Toggle -->
                <button @click="toggleTheme()" 
                    class="btn btn-icon relative rounded-md size-9 border border-transparent hover:bg-light hover:text-primary hover:border-gray-200 text-gray-600"
                    title="Toggle Theme">
                    <svg x-show="themeMode === 'light'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                    <svg x-show="themeMode === 'dark'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </button>

                <!-- RTL/LTR Toggle -->
                <button @click="toggleDirection()" 
                    class="btn btn-icon relative rounded-md size-9 border border-transparent hover:bg-light hover:text-primary hover:border-gray-200 text-gray-600"
                    title="Toggle Direction">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                    </svg>
                </button>

            </div>

            <!-- User Avatar -->
            @auth
                <div class="menu">
                    <button class="btn btn-icon rounded-full">
                        <img src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}" 
                            alt="{{ auth()->user()->name }}" 
                            class="size-8 rounded-full border border-gray-500 shrink-0">
                    </button>
                </div>
            @endauth
        </div>

    </div>
    {{-- End Sidebar Primary --}}

    {{-- Sidebar Secondary (Menu - 220px) --}}
    <div class="flex items-stretch grow shrink-0 justify-center ps-1.5 my-5 me-1.5" id="sidebar_secondary">
        <div class="grow overflow-y-auto">
            
            {{-- Sidebar Menu --}}
            <div class="menu flex flex-col w-full gap-px px-2.5">
                
                <!-- Get Started -->
                <div class="menu-item {{ request()->routeIs('admin.metronic.test') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}" 
                        class="menu-link py-2 ps-2.5 pe-2.5 rounded-md border border-transparent hover:bg-light hover:border-gray-200 {{ request()->routeIs('admin.metronic.test') ? 'border-gray-200 bg-light' : '' }}">
                        <span class="menu-title text-2sm text-gray-800 {{ request()->routeIs('admin.metronic.test') ? 'font-medium text-primary' : '' }}">
                            Get Started
                        </span>
                    </a>
                </div>

                <!-- User Cards (with submenu) -->
                <div class="menu-item" x-data="{ open: false }">
                    <div @click="open = !open" 
                        class="menu-link py-2 ps-2.5 pe-2.5 rounded-md border border-transparent cursor-pointer hover:bg-light hover:border-gray-200">
                        <span class="menu-title text-2sm text-gray-800">
                            User Cards
                        </span>
                        <span class="menu-arrow text-gray-500 ms-auto">
                            <svg x-show="!open" class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                            <svg x-show="open" class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"/>
                            </svg>
                        </span>
                    </div>
                    <div x-show="open" x-collapse class="menu-accordion gap-px">
                        <div class="menu-item">
                            <a href="#" class="menu-link py-2 ps-5 pe-2.5 rounded-md border border-transparent hover:bg-light hover:border-gray-200">
                                <span class="menu-title text-2sm text-gray-800">Mini Cards</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="#" class="menu-link py-2 ps-5 pe-2.5 rounded-md border border-transparent hover:bg-light hover:border-gray-200">
                                <span class="menu-title text-2sm text-gray-800">Team Crew</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- User Table (with submenu) -->
                <div class="menu-item" x-data="{ open: false }">
                    <div @click="open = !open" 
                        class="menu-link py-2 ps-2.5 pe-2.5 rounded-md border border-transparent cursor-pointer hover:bg-light hover:border-gray-200">
                        <span class="menu-title text-2sm text-gray-800">
                            User Table
                        </span>
                        <span class="menu-arrow text-gray-500 ms-auto">
                            <svg x-show="!open" class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                            <svg x-show="open" class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"/>
                            </svg>
                        </span>
                    </div>
                    <div x-show="open" x-collapse class="menu-accordion gap-px">
                        <div class="menu-item">
                            <a href="#" class="menu-link py-2 ps-5 pe-2.5 rounded-md border border-transparent hover:bg-light hover:border-gray-200">
                                <span class="menu-title text-2sm text-gray-800">Team Crew</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="#" class="menu-link py-2 ps-5 pe-2.5 rounded-md border border-transparent hover:bg-light hover:border-gray-200">
                                <span class="menu-title text-2sm text-gray-800">App Roster</span>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
            {{-- End Sidebar Menu --}}

        </div>
    </div>
    {{-- End Sidebar Secondary --}}

</div>

{{-- Mobile Backdrop --}}
<div x-show="sidebarOpen" 
    @click="toggleSidebar()"
    x-transition:enter="transition-opacity ease-linear duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-black bg-opacity-50 z-10 lg:hidden"
    style="display: none;">
</div>
