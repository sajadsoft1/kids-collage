<div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 bg-white dark:bg-base-100 border-b border-gray-200 dark:border-b-gray-700  px-4 shadow-xs sm:gap-x-6 sm:px-6 lg:px-8">
    <button type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden" @click="open = true">
        <span class="sr-only">Open sidebar</span>
        <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"></path>
        </svg>
    </button>

    <!-- Separator -->
    {{--    <div class="h-6 w-px bg-gray-900/10 lg:hidden" aria-hidden="true"></div>--}}
    <x-menu-separator/>
    <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
        <form class="grid flex-1 grid-cols-1" action="#" method="GET">
            <input type="search" name="search" aria-label="Search" class="col-start-1 row-start-1 block size-full  ps-8 text-base text-gray-900 outline-hidden placeholder:text-gray-400 sm:text-sm/6" placeholder="Search">
            <svg class="pointer-events-none col-start-1 row-start-1 size-5 self-center text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z" clip-rule="evenodd"></path>
            </svg>
        </form>
        <div class="flex items-center gap-x-4 lg:gap-x-6">

            <x-popover>
                <x-slot:trigger class="btn-ghost">
                    <x-icon name="o-rectangle-stack"/>
                </x-slot:trigger>
                <x-slot:content class="!w-70 grid grid-cols-4 gap-4">
                    <x-button class="bg-primary text-white w-[50px] h-[50px]">
                        <x-icon name="lucide.activity"/>
                    </x-button>
                    <x-button class="bg-primary text-white w-[50px] h-[50px]">
                        <x-icon name="lucide.activity"/>
                    </x-button>
                    <x-button class="bg-primary text-white w-[50px] h-[50px]">
                        <x-icon name="lucide.activity"/>
                    </x-button>
                    <x-button class="bg-primary text-white w-[50px] h-[50px]">
                        <x-icon name="lucide.activity"/>
                    </x-button>
                    <x-button class="bg-primary text-white w-[50px] h-[50px]">
                        <x-icon name="lucide.activity"/>
                    </x-button>
                    <x-button class="bg-primary text-white w-[50px] h-[50px]">
                        <x-icon name="lucide.activity"/>
                    </x-button>
                </x-slot:content>
            </x-popover>

            <x-button
                    class="btn-sm btn-ghost hover-none"
                    icon="o-bell-alert"
{{--                    :link="route('admin.notification.index')"--}}
                    wire:click="$toggle('notifications_drawer')"
            />
            <x-theme-toggle/>

            <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-gray-900/10" aria-hidden="true"></div>

            <!-- Profile dropdown -->
            <x-dropdown>
                <x-slot:trigger>
                    <x-button class="btn-circle"><img class="rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80" alt=""></x-button>
                </x-slot:trigger>
                <x-menu-item :title="trans('_menu.setting')" :link="route('admin.setting')"/>
                <x-menu-item title="Logout" :link="route('admin.auth.logout')"/>
            </x-dropdown>
        </div>
    </div>
</div>
