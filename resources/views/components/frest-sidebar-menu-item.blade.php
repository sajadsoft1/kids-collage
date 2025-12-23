@props(['menu'])

@php
    use Illuminate\Support\Arr;
@endphp

@if (Arr::has($menu, 'sub_menu'))
    {{-- Menu with Submenu --}}
    <li x-data="{
        // Sidebar collapse state (kept local because nested x-data scopes don't reliably inherit parent data)
        collapsed: false,
        isMobileSidebar: false,
        init() {
            this.isMobileSidebar = this.$el.closest('[data-frest-sidebar=&quot;mobile&quot;]') !== null;
            this.collapsed = this.isMobileSidebar ? false : (localStorage.getItem('frest_sidebar_collapsed') === 'true');
        },
        submenuOpen: {{ Arr::get($menu, 'hasActiveSubmenu', false) ? 'true' : 'false' }},
        popupSide: 'left',
        popupTop: 0,
        popupLeft: null,
        popupRight: null,
        updatePopupSide() {
            const rect = this.$el.getBoundingClientRect();
            const spaceRight = window.innerWidth - rect.right;
            const spaceLeft = rect.left;
            // If we're near the right edge (typical for right-aligned sidebar), open to left.
            this.popupSide = spaceRight < 260 && spaceLeft > spaceRight ? 'left' : 'right';
            this.updatePopupPosition();
        },
        updatePopupPosition() {
            const rect = this.$el.getBoundingClientRect();
            const gap = 8;

            this.popupTop = Math.max(8, rect.top);

            if (this.popupSide === 'left') {
                this.popupLeft = null;
                this.popupRight = (window.innerWidth - rect.left) + gap;
                return;
            }

            this.popupRight = null;
            this.popupLeft = rect.right + gap;
        },
        focusSubmenuItem(event, position) {
            const li = event.target.closest('li');
            if (!li) return;
            const submenuItems = Array.from(li.querySelectorAll('a[role=menuitem]'));
            if (submenuItems.length === 0) return;
    
            submenuItems.forEach((item) => item.tabIndex = -1);
            const target = position === 'last' ? submenuItems[submenuItems.length - 1] : submenuItems[0];
            target.tabIndex = 0;
            target.focus();
        },
        openAndFocus(event, position = 'first') {
            this.submenuOpen = true;
            this.$nextTick(() => this.focusSubmenuItem(event, position));
        },
        handleKeydown(event) {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                this.submenuOpen = !this.submenuOpen;
                if (this.submenuOpen) {
                    this.$nextTick(() => this.focusSubmenuItem(event, 'first'));
                }
                return;
            }
    
            if (event.key === 'ArrowDown' && !this.submenuOpen) {
                event.preventDefault();
                this.openAndFocus(event, 'first');
                return;
            }
    
            if (event.key === 'ArrowUp' && !this.submenuOpen) {
                event.preventDefault();
                this.openAndFocus(event, 'last');
            }
        }
    }" @sidebar-collapse.window="if (!isMobileSidebar) { collapsed = $event.detail.collapsed; }" class="relative">
        {{-- Expanded View --}}
        <div x-show="!collapsed" class="space-y-1">
            <button @click="submenuOpen = !submenuOpen" @keydown="handleKeydown($event)"
                x-bind:aria-expanded="submenuOpen"
                aria-label="{{ Arr::get($menu, 'title') }} - {{ Arr::get($menu, 'sub_menu', []) ? 'منوی دارای زیرمنو' : '' }}"
                class="flex gap-3 items-center px-3 py-2.5 w-full rounded-lg transition-colors text-white/90 hover:text-white hover:bg-white/10 focus-visible:outline-2 focus-visible:outline-primary focus-visible:outline-offset-2"
                :class="(window.isRouteActive && window.isRouteActive('{{ Arr::get($menu, 'url', '#') }}')) || submenuOpen ||
                    {{ Arr::get($menu, 'hasActiveSubmenu', false) ? 'true' : 'false' }} ? 'bg-white/5' : ''">
                <x-icon name="{{ Arr::get($menu, 'icon') }}" class="w-5 h-5 text-white shrink-0" />
                <span class="flex-1 text-right text-white">{{ Arr::get($menu, 'title') }}</span>
                @if (Arr::get($menu, 'badge'))
                    <span
                        class="px-2 py-0.5 text-xs font-medium rounded-full {{ Arr::get($menu, 'badge_classes', 'bg-primary/20 text-primary') }}">
                        {{ Arr::get($menu, 'badge') }}
                    </span>
                @endif
                <x-icon name="o-chevron-down" class="w-4 h-4 text-white transition-transform duration-200"
                    x-bind:class="submenuOpen ? 'rotate-180' : ''" />
            </button>
            {{-- Submenu Items --}}
            <div x-show="submenuOpen" x-collapse class="mt-1 mr-6 space-y-1" role="menu" x-init="if (submenuOpen) { $nextTick(() => { const first = $el.querySelector('a[role=menuitem]'); if (first) first.tabIndex = 0; }); }"
                @keydown="(e) => {
                    if (e.target.tagName !== 'A') return;
                    const items = Array.from(e.target.closest('[role=menu]').querySelectorAll('a[role=menuitem]'));
                    if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        const currentIndex = items.indexOf(e.target);
                        const nextIndex = (currentIndex + 1) % items.length;
                        items.forEach(item => item.tabIndex = -1);
                        items[nextIndex].tabIndex = 0;
                        items[nextIndex].focus();
                    } else if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        const currentIndex = items.indexOf(e.target);
                        const prevIndex = currentIndex === 0 ? items.length - 1 : currentIndex - 1;
                        items.forEach(item => item.tabIndex = -1);
                        items[prevIndex].tabIndex = 0;
                        items[prevIndex].focus();
                    } else if (e.key === 'Escape') {
                        e.preventDefault();
                        items.forEach(item => item.tabIndex = -1);
                        submenuOpen = false;
                        e.target.closest('li').querySelector('button').focus();
                    }
                }">
                @foreach (Arr::get($menu, 'sub_menu', []) as $index => $subMenu)
                    <a href="{{ Arr::get($subMenu, 'url', '#') }}" role="menuitem"
                        :tabindex="submenuOpen && {{ $index }} === 0 ? 0 : -1"
                        class="flex gap-3 items-center px-3 py-2 rounded-lg transition-colors text-white/80 hover:text-white hover:bg-white/10 focus-visible:outline-2 focus-visible:outline-primary focus-visible:outline-offset-2"
                        :class="(window.isRouteActive && window.isRouteActive('{{ Arr::get($subMenu, 'url', '#') }}',
                            {{ Arr::get($subMenu, 'exact', false) ? 'true' : 'false' }}) ? true : false) ||
                        {{ Arr::get($subMenu, 'isActive', false) ? 'true' : 'false' }} ? 'bg-primary text-white' : ''"
                        wire:navigate wire:loading.class="opacity-50 pointer-events-none">
                        <span wire:loading.remove>
                            <x-icon name="{{ Arr::get($subMenu, 'icon') }}" class="w-4 h-4 shrink-0" />
                        </span>
                        <span wire:loading class="loading loading-spinner loading-sm"></span>
                        <span class="flex-1 text-sm leading-5 text-right">{{ Arr::get($subMenu, 'title') }}</span>
                        @if (Arr::get($subMenu, 'badge'))
                            <span
                                class="px-2 py-0.5 text-xs font-medium rounded-full {{ Arr::get($subMenu, 'badge_classes', 'bg-primary/20 text-primary') }}">
                                {{ Arr::get($subMenu, 'badge') }}
                            </span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Collapsed View - Icon Only with Popup --}}
        <div x-show="collapsed" @mouseenter="submenuOpen = true; updatePopupSide()" @mouseleave="submenuOpen = false"
            class="relative">
            <button @click.stop.prevent="submenuOpen = !submenuOpen; if (submenuOpen) updatePopupSide()"
                @keydown="(e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        submenuOpen = !submenuOpen;
                        if (submenuOpen) updatePopupSide();
                    }
                }"
                x-bind:aria-expanded="submenuOpen"
                aria-label="{{ Arr::get($menu, 'title') }} - {{ Arr::get($menu, 'sub_menu', []) ? 'منوی دارای زیرمنو' : '' }}"
                class="flex justify-center items-center p-3 w-full rounded-lg transition-colors focus-visible:outline-2 focus-visible:outline-primary focus-visible:outline-offset-2"
                :class="submenuOpen || {{ Arr::get($menu, 'hasActiveSubmenu', false) ? 'true' : 'false' }} ?
                    'bg-primary text-white' : 'text-white/90 hover:text-white hover:bg-white/10'">
                <x-icon name="{{ Arr::get($menu, 'icon') }}" class="w-5 h-5" />
            </button>
            {{-- Popup Submenu --}}
            <div x-show="submenuOpen" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95" x-cloak @click.stop @mouseenter="submenuOpen = true"
                @mouseleave="submenuOpen = false" role="menu" @scroll.window="if (submenuOpen) updatePopupPosition()"
                @resize.window="if (submenuOpen) updatePopupSide()"
                class="fixed w-56 bg-base-300 border border-base-content/20 rounded-lg shadow-2xl z-[9999] frest-sidebar-popup"
                :style="popupSide === 'left'
                    ? ('top: ' + popupTop + 'px; right: ' + popupRight + 'px;')
                    : ('top: ' + popupTop + 'px; left: ' + popupLeft + 'px;')"
                style="background-color: var(--b3) !important;">
                {{-- Popup arrow --}}
                <div aria-hidden="true"
                    class="absolute top-4 w-0 h-0 border-t-[8px] border-t-transparent border-b-[8px] border-b-transparent"
                    :class="popupSide === 'left'
                        ?
                        '-right-2 border-l-[8px] border-l-base-300' :
                        '-left-2 border-r-[8px] border-r-base-300'">
                </div>
                <div class="py-2" role="menu" x-init="if (submenuOpen) {
                    $nextTick(() => {
                        const first = $el.querySelector('a[role=menuitem]');
                        if (first) first.tabIndex = 0;
                    });
                }"
                    @keydown="(e) => {
                        if (e.target.tagName !== 'A') return;
                        const items = Array.from(e.target.closest('[role=menu]').querySelectorAll('a[role=menuitem]'));
                        if (e.key === 'ArrowDown') {
                            e.preventDefault();
                            const currentIndex = items.indexOf(e.target);
                            const nextIndex = (currentIndex + 1) % items.length;
                            items.forEach(item => item.tabIndex = -1);
                            items[nextIndex].tabIndex = 0;
                            items[nextIndex].focus();
                        } else if (e.key === 'ArrowUp') {
                            e.preventDefault();
                            const currentIndex = items.indexOf(e.target);
                            const prevIndex = currentIndex === 0 ? items.length - 1 : currentIndex - 1;
                            items.forEach(item => item.tabIndex = -1);
                            items[prevIndex].tabIndex = 0;
                            items[prevIndex].focus();
                        } else if (e.key === 'Escape') {
                            e.preventDefault();
                            items.forEach(item => item.tabIndex = -1);
                            submenuOpen = false;
                            e.target.closest('li').querySelector('button').focus();
                        }
                    }">
                    @foreach (Arr::get($menu, 'sub_menu', []) as $index => $subMenu)
                        <a href="{{ Arr::get($subMenu, 'url', '#') }}" role="menuitem"
                            :tabindex="submenuOpen && {{ $index }} === 0 ? 0 : -1"
                            class="flex gap-3 items-center px-4 py-2.5 mx-1.5 rounded-md transition-all duration-150 text-white/90 hover:text-white hover:bg-white/10 focus-visible:outline-2 focus-visible:outline-primary focus-visible:outline-offset-2"
                            :class="(window.isRouteActive && window.isRouteActive('{{ Arr::get($subMenu, 'url', '#') }}',
                                {{ Arr::get($subMenu, 'exact', false) ? 'true' : 'false' }}) ? true : false) ||
                            {{ Arr::get($subMenu, 'isActive', false) ? 'true' : 'false' }} ? 'bg-primary text-white' :
                                ''"
                            wire:navigate wire:loading.class="opacity-50 pointer-events-none">
                            <span wire:loading.remove>
                                <x-icon name="{{ Arr::get($subMenu, 'icon') }}" class="w-4 h-4 shrink-0" />
                            </span>
                            <span wire:loading class="loading loading-spinner loading-sm"></span>
                            <span class="text-sm leading-5 whitespace-nowrap">{{ Arr::get($subMenu, 'title') }}</span>
                            @if (Arr::get($subMenu, 'badge'))
                                <span
                                    class="px-2 py-0.5 text-xs font-medium rounded-full {{ Arr::get($subMenu, 'badge_classes', 'bg-primary/20 text-primary') }}">
                                    {{ Arr::get($subMenu, 'badge') }}
                                </span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </li>
@elseif(Arr::has($menu, 'type') && Arr::get($menu, 'type') === 'seperator')
    {{-- Separator --}}
    <li x-data="{
        collapsed: false,
        isMobileSidebar: false,
        init() {
            this.isMobileSidebar = this.$el.closest('[data-frest-sidebar=&quot;mobile&quot;]') !== null;
            this.collapsed = this.isMobileSidebar ? false : (localStorage.getItem('frest_sidebar_collapsed') === 'true');
        }
    }" @sidebar-collapse.window="if (!isMobileSidebar) { collapsed = $event.detail.collapsed; }" class="my-4">
        <div class="h-px bg-white/10"></div>
        @if (Arr::get($menu, 'title'))
            <span x-show="!collapsed" class="block px-3 mt-2 text-xs text-white/50">{{ Arr::get($menu, 'title') }}</span>
        @endif
    </li>
@else
    {{-- Single Menu Item --}}
    <li x-data="{
        collapsed: false,
        isMobileSidebar: false,
        init() {
            this.isMobileSidebar = this.$el.closest('[data-frest-sidebar=&quot;mobile&quot;]') !== null;
            this.collapsed = this.isMobileSidebar ? false : (localStorage.getItem('frest_sidebar_collapsed') === 'true');
        }
    }" @sidebar-collapse.window="if (!isMobileSidebar) { collapsed = $event.detail.collapsed; }">
        {{-- Expanded View --}}
        <a href="{{ Arr::get($menu, 'url', '#') }}" x-show="!collapsed"
            class="flex gap-3 items-center px-3 py-2.5 rounded-lg transition-colors text-white/90 hover:text-white hover:bg-white/10 focus-visible:outline-2 focus-visible:outline-primary focus-visible:outline-offset-2"
            :class="(window.isRouteActive && window.isRouteActive('{{ Arr::get($menu, 'url', '#') }}',
                {{ Arr::get($menu, 'exact', false) ? 'true' : 'false' }}) ? true : false) ||
            {{ Arr::get($menu, 'isActive', false) ? 'true' : 'false' }} ? 'bg-primary text-white' : ''"
            wire:navigate wire:loading.class="opacity-50 pointer-events-none">
            <span wire:loading.remove>
                <x-icon name="{{ Arr::get($menu, 'icon') }}" class="w-5 h-5 shrink-0" />
            </span>
            <span wire:loading class="loading loading-spinner loading-sm"></span>
            <span class="flex-1 leading-5 text-right">{{ Arr::get($menu, 'title') }}</span>
            @if (Arr::get($menu, 'badge'))
                <span
                    class="px-2 py-0.5 text-xs font-medium rounded-full {{ Arr::get($menu, 'badge_classes', 'bg-primary/20 text-primary') }}">
                    {{ Arr::get($menu, 'badge') }}
                </span>
            @endif
        </a>
        {{-- Collapsed View - Icon Only --}}
        <a href="{{ Arr::get($menu, 'url', '#') }}" x-show="collapsed"
            class="flex justify-center items-center p-3 rounded-lg transition-colors focus-visible:outline-2 focus-visible:outline-primary focus-visible:outline-offset-2"
            :class="(window.isRouteActive && window.isRouteActive('{{ Arr::get($menu, 'url', '#') }}',
                {{ Arr::get($menu, 'exact', false) ? 'true' : 'false' }}) ? true : false) ||
            {{ Arr::get($menu, 'isActive', false) ? 'true' : 'false' }} ? 'bg-primary text-white' :
                'text-white/90 hover:text-white hover:bg-white/10'"
            :aria-label="'{{ Arr::get($menu, 'title') }}'" title="{{ Arr::get($menu, 'title') }}" wire:navigate
            wire:loading.class="opacity-50 pointer-events-none">
            <span wire:loading.remove>
                <x-icon name="{{ Arr::get($menu, 'icon') }}" class="w-5 h-5" />
            </span>
            <span wire:loading class="loading loading-spinner loading-sm"></span>
        </a>
    </li>
@endif
