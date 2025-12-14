@php
    use App\Helpers\Constants;
@endphp

<div class="py-4 md:py-6">
    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    {{-- HEADER SECTION - Minimal Header --}}
    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    @include('livewire.admin.pages.course.partials.course-header')

    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    {{-- MAIN LAYOUT - Two Column: Sessions List + Session Details --}}
    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3 lg:gap-6">
        {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
        {{-- SIDEBAR - Sessions List (Desktop) / Drawer (Mobile/Tablet) --}}
        {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
        <div class="lg:col-span-1">
            {{-- Mobile/Tablet Drawer Toggle Button --}}
            <div class="mb-4 lg:hidden">
                <x-button wire:click="$toggle('showSessionsDrawer')" wire:loading.attr="disabled" wire:target="$toggle"
                    class="btn-primary btn-block" icon="o-bars-3">
                    <span wire:loading.remove wire:target="$toggle">
                        {{ __('session.page.session_list') }} ({{ $course->sessions->count() }})
                    </span>
                    <span wire:loading wire:target="$toggle" class="loading loading-spinner loading-sm"></span>
                </x-button>
            </div>

            {{-- Desktop Sidebar --}}
            <div class="hidden space-y-4 lg:block">
                @include('livewire.admin.pages.course.partials.course-info-card-compact')
                @include('livewire.admin.pages.course.partials.session-list')
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
        {{-- MAIN CONTENT - Session Details --}}
        {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
        <div class="lg:col-span-2">
            @include('livewire.admin.pages.course.partials.session-details')
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    {{-- SESSIONS DRAWER - Mobile/Tablet Drawer for Sessions List --}}
    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    <x-drawer wire:model="showSessionsDrawer" :title="__('session.page.session_list')" right separator with-close-button close-on-escape
        class="w-11/12 lg:w-1/3">
        <div class="space-y-4">
            @include('livewire.admin.pages.course.partials.course-info-card-compact')
            @include('livewire.admin.pages.course.partials.session-list')
        </div>
    </x-drawer>

    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    {{-- RESOURCE MODAL - Display Resource Details --}}
    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    @include('livewire.admin.pages.course.partials.resource-modal')
</div>
