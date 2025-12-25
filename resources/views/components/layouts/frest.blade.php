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
        // Initialize viewport check
        this.viewportIsLg = window.matchMedia('(min-width: 1024px)').matches;

        // Watch for sidebar open/close and manage body scroll
        this.$watch('sidebarOpen', (value) => {
            if (value && !this.viewportIsLg) {
                // Mobile sidebar open - prevent body scroll
                document.body.style.overflow = 'hidden';
            } else {
                // Mobile sidebar closed or desktop - restore scroll
                document.body.style.overflow = '';
            }
        });

        // Watch for sidebar collapse changes and save to localStorage
        this.$watch('sidebarCollapsed', (value) => {
            localStorage.setItem('frest_sidebar_collapsed', value.toString());
        });

        // Handle window resize
        const handleResize = () => {
            const wasLg = this.viewportIsLg;
            this.viewportIsLg = window.matchMedia('(min-width: 1024px)').matches;
            
            // If switching from mobile to desktop, close mobile sidebar
            if (wasLg === false && this.viewportIsLg === true && this.sidebarOpen) {
                this.sidebarOpen = false;
            }
            
            // Restore body scroll if needed
            if (this.viewportIsLg) {
                document.body.style.overflow = '';
            }
        };

        window.addEventListener('resize', handleResize);
        
        // Cleanup on component destroy
        this.$el.addEventListener('alpine:destroyed', () => {
            window.removeEventListener('resize', handleResize);
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
