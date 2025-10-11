<form wire:submit.prevent="save">
    <x-card title="تنظیمات اطلاع‌رسانی" shadow separator progress-indicator="save">
        {{-- Alert Info --}}
        <x-alert class="mb-6 alert-info" icon="o-information-circle" title="راهنما">
            <x-slot:description>
                <p class="text-sm">
                    می‌توانید مشخص کنید که برای هر نوع رویداد، از چه کانال‌هایی اطلاع‌رسانی دریافت کنید.
                    به طور پیش‌فرض، همه اطلاع‌رسانی‌ها فعال هستند.
                </p>
            </x-slot:description>
        </x-alert>

        {{-- Quick Actions --}}
        <div class="mt-6 divider">عملیات سریع</div>

        <div class="flex flex-wrap gap-2">
            {{-- Enable/Disable All --}}
            <x-button label="فعال کردن همه" icon="o-check-circle" class="btn-sm btn-success" wire:click="enableAll"
                spinner wire:target="enableAll" />

            <x-button label="غیرفعال کردن همه" icon="o-x-circle" class="btn-sm btn-error" wire:click="disableAll"
                spinner wire:target="disableAll" />

            {{-- Channel-specific toggles --}}
            @foreach ($this->channels as $channel)
                <x-button label="تنها {{ $channel->title() }}" icon="{{ $channel->icon() }}" class="btn-sm btn-outline"
                    wire:click="enableOnlyChannel('{{ $channel->value }}')" spinner
                    wire:target="enableOnlyChannel('{{ $channel->value }}')" />
            @endforeach
        </div>

        <hr class="my-6" />

        {{-- Channels Header --}}
        <div class="mb-4 overflow-x-auto">
            <table class="table w-full table-zebra">
                <thead>
                    <tr>
                        <th class="text-right">نوع رویداد</th>
                        @foreach ($this->channels as $channel)
                            <th class="text-center">
                                <div class="flex flex-col items-center gap-1">
                                    <x-icon :name="$channel->icon()" class="w-5 h-5" />
                                    <span class="text-xs">{{ $channel->title() }}</span>
                                </div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->groupedEvents as $category => $events)
                        {{-- Category Header --}}
                        <tr class="bg-base-200">
                            <td colspan="{{ count($this->channels) + 1 }}" class="font-bold">
                                {{ $category }}
                            </td>
                        </tr>

                        {{-- Events in this category --}}
                        @foreach ($events as $event)
                            <tr wire:key="event-{{ $event->value }}">
                                <td>
                                    <div class="flex flex-col">
                                        <span class="font-medium">{{ $event->title() }}</span>
                                        <span class="text-xs text-gray-500">{{ $event->description() }}</span>
                                    </div>
                                </td>
                                @foreach ($this->channels as $channel)
                                    <td class="text-center">
                                        <x-checkbox
                                            wire:model="notificationSettings.{{ $event->value }}.{{ $channel->value }}"
                                            wire:key="checkbox-{{ $event->value }}-{{ $channel->value }}" />
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>



        {{-- Form Actions --}}
        <x-slot:actions>
            <x-button label="{{ trans('general.submit') }}" type="submit" spinner="save" icon="o-check"
                class="btn-primary" />
        </x-slot:actions>
    </x-card>
</form>
