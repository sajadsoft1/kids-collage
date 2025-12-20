@php
    use App\Helpers\Constants;
    use App\Enums\UserTypeEnum;
    use App\View\Composers\NavbarComposer;

    $isEmployee = auth()->user()?->type === UserTypeEnum::EMPLOYEE;
    $navbarMenu = app(NavbarComposer::class)->getMenu();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">

@include('components.layouts.shared.head')

<body class="flex min-h-screen bg-gray-100" x-data="{
    sidebarOpen: false,
    sidebarCollapsed: localStorage.getItem('matronic_sidebar_collapsed') === 'true',
    isEmployee: {{ $isEmployee ? 'true' : 'false' }},
    init() {
        this.$watch('sidebarCollapsed', (value) => {
            localStorage.setItem('matronic_sidebar_collapsed', value.toString());
        });
    }
}" @toggle-sidebar.window="sidebarOpen = !sidebarOpen"
    @sidebar-collapse.window="sidebarCollapsed = $event.detail.collapsed">
    {{-- Fixed Sidebar (Left) --}}
    @if ($isEmployee)
        <x-matronic-sidebar :navbar-menu="$navbarMenu" />
    @endif

    {{-- Main Content Wrapper --}}
    <div class="flex flex-col flex-1 min-h-screen transition-all duration-300"
        x-bind:class="isEmployee ? (sidebarCollapsed ? 'ml-20' : 'ml-[280px]') : ''">
        {{-- Fixed Header --}}
        <livewire:admin.shared.matronic-header :show-menu="!$isEmployee" />

        {{-- Main Content Area --}}
        <main class="flex flex-col flex-1 overflow-y-auto bg-gray-100">
            {{-- Secondary Navigation Bar --}}
            <div class="bg-white border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-3">
                    {{-- Navigation Tabs --}}
                    <div class="flex items-center gap-1">
                        <button
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors flex items-center gap-2">
                            <x-icon name="o-list-bullet" class="w-4 h-4" />
                            <span>List</span>
                        </button>
                        <button
                            class="px-4 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-100 transition-colors flex items-center gap-2">
                            <x-icon name="o-squares-2x2" class="w-4 h-4" />
                            <span>Kanban</span>
                        </button>
                        <button
                            class="px-4 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-100 transition-colors flex items-center gap-2">
                            <x-icon name="o-calendar" class="w-4 h-4" />
                            <span>Calendar</span>
                        </button>
                        <button
                            class="px-4 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-100 transition-colors flex items-center gap-2">
                            <x-icon name="o-chart-bar" class="w-4 h-4" />
                            <span>Dashboard</span>
                        </button>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex items-center gap-2">
                        <button class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors" title="Sort">
                            <x-icon name="o-arrows-up-down" class="w-4 h-4" />
                        </button>
                        <button class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors" title="View">
                            <x-icon name="o-eye" class="w-4 h-4" />
                        </button>
                        <button class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors" title="Filter">
                            <x-icon name="o-funnel" class="w-4 h-4" />
                        </button>
                        <button class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors" title="Search">
                            <x-icon name="o-magnifying-glass" class="w-4 h-4" />
                        </button>
                    </div>
                </div>
            </div>

            {{-- Page Content --}}
            <div @class([
                'flex flex-col flex-1',
                'p-6' => !isset($fullWidth) || !$fullWidth,
                'p-0' => isset($fullWidth) && $fullWidth,
            ])>
                <div @class([
                    'flex flex-col flex-1 bg-white rounded-lg shadow-sm',
                    'container mx-auto p-6' => !isset($fullWidth) || !$fullWidth,
                    'w-full p-6' => isset($fullWidth) && $fullWidth,
                ])>
                    {{ $slot }}
                </div>
            </div>
        </main>
    </div>

    @include('components.layouts.shared.shared')
</body>

</html>
