<div
    class="sticky top-0 z-50 flex h-16 shrink-0 items-center gap-x-4 border-b border-base-300 dark:border-base-content/10 px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8 {{ $nav_class ?? '' }}">
    <button type="button"
        class="p-2.5 -m-2.5 rounded-lg text-base-content/70 hover:text-base-content hover:bg-base-200 dark:hover:bg-base-300 transition-colors"
        @click="$dispatch('toggle-sidebar')" aria-label="Toggle sidebar">
        <span class="sr-only">Toggle sidebar</span>
        <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"
            data-slot="icon">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5">
            </path>
        </svg>
    </button>

    <!-- Separator -->
    {{--    <div class="w-px h-6 bg-gray-900/10 lg:hidden" aria-hidden="true"></div> --}}
    <x-menu-separator />
    <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
        <form class="grid flex-1 grid-cols-1" action="#" method="GET">
            <input type="search" name="search" aria-label="Search"
                class="block col-start-1 row-start-1 text-base text-base-content size-full ps-8 outline-hidden placeholder:text-base-content/50 sm:text-sm/6 bg-base-200 dark:bg-base-300 border border-base-300 dark:border-base-content/20 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors"
                placeholder="Search">
            <svg class="col-start-1 row-start-1 self-center text-base-content/40 pointer-events-none size-5"
                viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                <path fill-rule="evenodd"
                    d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z"
                    clip-rule="evenodd"></path>
            </svg>
        </form>
        <div class="flex gap-x-4 items-center lg:gap-x-6">

            <x-popover>
                <x-slot:trigger class="btn-ghost">
                    <x-icon name="o-rectangle-stack" />
                </x-slot:trigger>
                <x-slot:content class="!w-70 grid grid-cols-4 gap-4">
                    <x-button class="bg-primary text-white w-[50px] h-[50px]">
                        <x-icon name="lucide.activity" />
                    </x-button>
                    <x-button class="bg-primary text-white w-[50px] h-[50px]">
                        <x-icon name="lucide.activity" />
                    </x-button>
                    <x-button class="bg-primary text-white w-[50px] h-[50px]">
                        <x-icon name="lucide.activity" />
                    </x-button>
                    <x-button class="bg-primary text-white w-[50px] h-[50px]">
                        <x-icon name="lucide.activity" />
                    </x-button>
                    <x-button class="bg-primary text-white w-[50px] h-[50px]">
                        <x-icon name="lucide.activity" />
                    </x-button>
                    <x-button class="bg-primary text-white w-[50px] h-[50px]">
                        <x-icon name="lucide.activity" />
                    </x-button>
                </x-slot:content>
            </x-popover>

            <x-button class="btn-sm btn-ghost hover-none" icon="o-bell-alert" {{--                    :link="route('admin.notification.index')" --}}
                wire:click="$toggle('notifications_drawer')" />
            <x-theme-toggle />

            <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-base-content/10" aria-hidden="true"></div>

            <!-- Profile dropdown -->
            <x-dropdown>
                <x-slot:trigger>
                    <x-button class="btn-circle"><img class="rounded-full"
                            src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80"
                            alt=""></x-button>
                </x-slot:trigger>
                <x-menu-item :title="trans('_menu.setting')" :link="route('admin.setting')" />
                <x-menu-item title="Logout" :link="route('admin.auth.logout')" />
            </x-dropdown>
        </div>
    </div>
</div>
