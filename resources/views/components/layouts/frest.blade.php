@php
    use App\Enums\UserTypeEnum;

    $isEmployee = auth()->user()?->type === UserTypeEnum::EMPLOYEE;
    $sidebarWidthCollapsed = config('frest.sidebar.width.collapsed', 20);
    $sidebarWidthExpanded = config('frest.sidebar.width.expanded', 72);
    $sidebarWidthCollapsedPx = $sidebarWidthCollapsed * 4;
    $sidebarWidthExpandedPx = $sidebarWidthExpanded * 4;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ str_starts_with(app()->getLocale(), 'fa') ? 'rtl' : 'ltr' }}">

@include('components.layouts.shared.head')

<body class="flex overflow-x-hidden min-h-screen bg-base-200 dark:bg-base-300" x-data="{
    sidebarOpen: false,
    sidebarCollapsed: localStorage.getItem('frest_sidebar_collapsed') === 'true',
    viewportIsLg: window.matchMedia('(min-width: 1024px)').matches,
    isEmployee: {{ $isEmployee ? 'true' : 'false' }},
    sidebarOffsetStyle() {
        if (!this.isEmployee || !this.viewportIsLg) {
            return '';
        }

        const px = this.sidebarCollapsed ? {{ $sidebarWidthCollapsedPx }} : {{ $sidebarWidthExpandedPx }};
        if (document.documentElement.dir === 'rtl') {
            return 'margin-right: ' + px + 'px; margin-left: 0px;';
        }

        return 'margin-left: ' + px + 'px; margin-right: 0px;';
    },
    init() {
        this.$watch('sidebarOpen', (value) => {
            document.body.classList.toggle('overflow-hidden', value);
        });

        // Watch for sidebar collapse changes and save to localStorage
        this.$watch('sidebarCollapsed', (value) => {
            localStorage.setItem('frest_sidebar_collapsed', value.toString());
        });

        window.addEventListener('resize', () => {
            this.viewportIsLg = window.matchMedia('(min-width: 1024px)').matches;
        });
    }
}"
    @toggle-sidebar.window="sidebarOpen = !sidebarOpen"
    @sidebar-collapse.window="sidebarCollapsed = $event.detail.collapsed">
    {{-- Fixed Sidebar --}}
    @if ($isEmployee)
        <x-frest-sidebar :navbar-menu="\App\Helpers\FrestMenuHelper::processMenu($navbarMenu ?? [])" />
    @endif

    {{-- Main Content Wrapper --}}
    <div class="flex flex-col flex-1 min-h-screen transition-all duration-300" :style="sidebarOffsetStyle()">
        {{-- Fixed Header --}}
        <livewire:admin.shared.frest-header :show-menu="!$isEmployee" />

        {{-- Main Content Area --}}
        <main class="flex overflow-y-auto flex-col flex-1 bg-base-200 dark:bg-base-300">
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
