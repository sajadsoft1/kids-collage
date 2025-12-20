@php
    use App\Helpers\Constants;
    use App\Enums\UserTypeEnum;
    use App\View\Composers\NavbarComposer;

    $isEmployee = auth()->user()?->type === UserTypeEnum::EMPLOYEE;
    $navbarMenu = app(NavbarComposer::class)->getMenu();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'fa' ? 'rtl' : 'ltr' }}">

@include('components.layouts.shared.head')

<body class="flex min-h-screen bg-base-200 dark:bg-base-300" x-data="{
    sidebarOpen: false,
    sidebarCollapsed: localStorage.getItem('frest_sidebar_collapsed') === 'true',
    isEmployee: {{ $isEmployee ? 'true' : 'false' }},
    init() {
        // Watch for sidebar collapse changes and save to localStorage
        this.$watch('sidebarCollapsed', (value) => {
            localStorage.setItem('frest_sidebar_collapsed', value.toString());
        });
    }
}"
    @toggle-sidebar.window="sidebarOpen = !sidebarOpen"
    @sidebar-collapse.window="sidebarCollapsed = $event.detail.collapsed">
    {{-- Fixed Sidebar --}}
    @if ($isEmployee)
        <x-frest-sidebar :navbar-menu="$navbarMenu" />
    @endif

    {{-- Main Content Wrapper --}}
    <div class="flex flex-col flex-1 min-h-screen transition-all duration-300"
        :class="isEmployee ? (sidebarCollapsed ? 'mr-20' : 'mr-72') : ''">
        {{-- Fixed Header --}}
        <livewire:admin.shared.frest-header :show-menu="!$isEmployee" />

        {{-- Main Content Area --}}
        <main class="flex flex-col flex-1 overflow-y-auto bg-base-200 dark:bg-base-300">
            <div @class([
                'flex flex-col flex-1',
                'p-6' => !isset($fullWidth) || !$fullWidth,
                'p-0' => isset($fullWidth) && $fullWidth,
            ])>
                <div @class([
                    'flex flex-col flex-1',
                    'container mx-auto' => !isset($fullWidth) || !$fullWidth,
                    'w-full' => isset($fullWidth) && $fullWidth,
                ])>
                    {{ $slot }}
                </div>
            </div>

            {{-- Footer --}}
            <x-frest-footer />
        </main>
    </div>

    @include('components.layouts.shared.shared')
</body>

</html>
