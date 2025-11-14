@php
    use App\Services\Permissions\PermissionsService;
    use Spatie\Permission\Models\Permission;
@endphp

<div class="w-full min-h-screen p-6 mt-8 mb-12 bg-base-100 rounded-xl">
    {{-- Breadcrumbs --}}
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions" />

    {{-- Role Name --}}
    <div class="mb-6">
        <x-input label="عنوان" wire:model="name" name="name" placeholder="نام نقش مورد نظر خود را وارد کنید" />
        <x-textarea label="توضیحات" wire:model="description" name="description" placeholder="توضیحات مربوط به این نقش"
            rows="5" />
    </div>

    {{-- Permissions --}}
    <div class="mb-6">
        <h4 class="mb-5 text-base font-semibold text-content">دسترسی‌ها</h4>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach (PermissionsService::showPermissionsByService() as $group)
                @php
                    $groupAll = collect($group['items'])->first(
                        fn($item) => \Illuminate\Support\Str::endsWith($item['value'], '.All'),
                    );
                    $groupAllId = $groupAll ? Permission::where('name', $groupAll['value'])->value('id') : null;
                    $groupAllChecked = $groupAllId && in_array($groupAllId, $permissions, true);
                @endphp

                <div x-data="{
                    groupAllId: {{ $groupAllId ?? 'null' }},
                    get allSelected() {
                        if (!this.groupAllId || !$wire || !Array.isArray($wire.permissions)) {
                            return false;
                        }
                        return $wire.permissions.includes(this.groupAllId);
                    },
                    isSubDisabled(subId) {
                        return this.allSelected;
                    },
                    toggleAll(state) {
                        if (state) {
                            this.clearGroupCheckboxes();
                        }
                    },
                    clearGroupCheckboxes() {
                        const inputs = this.$refs.group.querySelectorAll('input[data-sub]');
                        inputs.forEach(i => {
                            const id = parseInt(i.value);
                            i.checked = false;
                            i.dispatchEvent(new Event('input')); // Uncheck visually
                            if ($wire && Array.isArray($wire.permissions)) {
                                $wire.permissions = $wire.permissions.filter(p => p !== id); // Remove from Livewire
                            }
                        });
                    }
                }" class="p-4 border shadow-sm border-base-300 bg-base-100 rounded-xl">
                    <h5 class="mb-3 text-sm font-bold text-content">{{ $group['title'] }}</h5>

                    <div class="space-y-2" x-ref="group">
                        @foreach ($group['items'] as $item)
                            @continue($item['value'] === 'Shared.Admin')
                            @php
                                $permissionId = Permission::where('name', $item['value'])->value('id');
                                $isAll = \Illuminate\Support\Str::endsWith($item['value'], '.All');
                                $isChecked = in_array($permissionId, $permissions, true);
                            @endphp

                            <label class="flex items-center space-x-2 text-sm text-content">
                                <input type="checkbox" wire:model="permissions" value="{{ $permissionId }}"
                                    class="text-blue-600 form-checkbox" {{ $isAll ? 'data-all' : 'data-sub' }}
                                    @if ($isChecked) checked @endif
                                    @if ($isAll) x-on:change="toggleAll($event.target.checked)"
                                    @else
                                        x-bind:disabled="isSubDisabled({{ $permissionId }})" @endif>
                                <span>{{ $item['title'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="flex justify-end mt-6 space-x-4">
        <x-button wire:click="cancel" type="button" wire:loading.attr="disabled" wire:target="cancel" spinner="cancel"
            class="px-4 py-2 text-sm rounded-md text-content bg-base-100 hover:bg-base-200" :label="trans('general.cancel')" />

        <x-button wire:click="submit" type="submit" wire:loading.attr="disabled" wire:target="submit" spinner="submit"
            class="px-4 py-2 text-sm rounded-md text-base-100 bg-primary hover:bg-primary/80" :label="$edit_mode ? trans('general.edit') : trans('general.create')" />
    </div>
</div>
