@php use App\Helpers\Constants; @endphp
<x-nav sticky full-width>
    <x-slot:brand>
        {{-- Drawer toggle for "main-drawer" --}}
        <label for="main-drawer" class="lg:hidden mr-3">
            <x-icon name="o-bars-3" class="cursor-pointer" />
        </label>

        {{-- Brand --}}
        <div>{{$title??config('app.name')}}</div>
    </x-slot:brand>
    <x-slot:actions>
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

        <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-gray-900/10" aria-hidden="true"></div>

        <!-- Profile dropdown -->
        <x-dropdown>
            <x-slot:trigger>
                <x-button class="btn-circle">
                    <img class="rounded-full"
                         src="{{auth()->user()->getFirstMediaUrl('avatar',Constants::RESOLUTION_100_SQUARE)}}"
                         alt="{{auth()->user()->full_name}}">
                </x-button>
            </x-slot:trigger>
            <x-menu-item :title="trans('_menu.setting')" :link="route('admin.setting')" />
            <x-menu-item title="Logout" :link="route('admin.auth.logout')" />
        </x-dropdown>
    </x-slot:actions>


    <x-drawer wire:model="notifications_drawer" :title="trans('notification.models')" separator with-close-button close-on-escape
        class="w-11/12 lg:w-1/3" right>
        @forelse($notificaations as $notif)
            <x-list-item :item="$notif">
                <x-slot:value>
                    {{ App\Helpers\NotifyHelper::title($notif->data) }}
                </x-slot:value>
                <x-slot:sub-value>
                    {{ \Illuminate\Support\Str::limit(App\Helpers\NotifyHelper::subTitle($notif->data)) }}
                </x-slot:sub-value>
                <x-slot:actions>
                    <x-button icon="o-eye" class="btn-sm" wire:click="toastNotification('{{ $notif->id }}')" />
                </x-slot:actions>

            </x-list-item>
            @if ($loop->last)
                <div class="flex gap-4 mt-5">
                    <x-button class="btn-primary flex-1" spinner :label="trans('notification.read_all')" />
                    <x-button class="btn-primary flex-1" spinner :label="trans('notification.read_all')" />
                </div>
            @endif

        @empty
            <x-admin.shared.empty-view class:image="size-60" :title="trans('core.notification.empty_title')" :description="trans('core.notification.empty_description')" :image="asset('assets/images/png/no-data.png')"
                :btn_label="trans('core.notification.empty_btn')" :btn_link="route('admin.notification.index')" />
        @endforelse
    </x-drawer>
</x-nav>
