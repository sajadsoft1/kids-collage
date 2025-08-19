@php
    use App\Services\Permissions\PermissionsService;
    use Spatie\Permission\Models\Permission;

    $adminPermissionId = Permission::where('name', 'Shared.Admin')->value('id');
@endphp

<div
    x-data="{
        adminSelected: {{ in_array($adminPermissionId, $permissions) ? 'true' : 'false' }},
        toggleAdmin(state) {
            this.adminSelected = state;
            if (state) {
                this.clearAllExceptAdmin();
            }
        },
        clearAllExceptAdmin() {
            const checkboxes = this.$el.querySelectorAll('input[data-clearable]');
            checkboxes.forEach(cb => {
                const id = parseInt(cb.value);
                cb.checked = false;
                cb.dispatchEvent(new Event('input'));
                if ($wire && Array.isArray($wire.permissions)) {
                    $wire.permissions = $wire.permissions.filter(p => p !== id);
                }
            });

            // Let Livewire know to clear all except Admin
            Livewire.dispatch('admin-selected');
        }
    }"
    class="min-h-screen w-full p-6 bg-white mt-8 mb-12 rounded-xl"
>
    {{-- Breadcrumbs --}}
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions"/>

    {{-- Role Name --}}
    <div class="mb-6">
        <x-input label="عنوان" wire:model="name" name="name" placeholder="نام نقش مورد نظر خود را وارد کنید"/>
        <x-textarea label="توضیحات" wire:model="description" name="description" placeholder="توضیحات مربوط به این نقش" rows="5"/>
    </div>

    {{-- Admin Global Permission --}}
    <div class="mb-6 bg-red-50 border border-red-300 rounded-xl p-4">
        <h5 class="text-sm font-bold text-red-700 mb-3">دسترسی ادمین (Admin)</h5>

        <label class="flex items-center space-x-2 text-sm text-red-800">
            <input type="checkbox"
                   wire:model="permissions"
                   value="{{ $adminPermissionId }}"
                   class="form-checkbox text-red-600"
                   x-model="adminSelected"
                   x-on:change="toggleAdmin($event.target.checked)"
            >
            <span>دسترسی کامل به همه‌ی امکانات</span>
        </label>
    </div>

    {{-- Permissions --}}
    <div class="mb-6">
        <h4 class="text-base font-semibold text-gray-700 mb-5">دسترسی‌ها</h4>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach(PermissionsService::showPermissionsByService() as $group)
                @php
                    $groupAll = collect($group['items'])->first(fn($item) => \Illuminate\Support\Str::endsWith($item['value'], '.All'));
                    $groupAllId = $groupAll ? Permission::where('name', $groupAll['value'])->value('id') : null;
                    $groupAllChecked = $groupAllId && in_array($groupAllId, $permissions, true);
                @endphp

                <div
                    x-data="{
                        allSelected: {{ $groupAllChecked ? 'true' : 'false' }},
                        toggleAll(state) {
                            this.allSelected = state;
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
                    }"
                    class="bg-gray-50 border border-gray-200 rounded-xl shadow-sm p-4"
                >
                    <h5 class="text-sm font-bold text-gray-800 mb-3">{{ $group['title'] }}</h5>

                    <div class="space-y-2" x-ref="group">
                        @foreach($group['items'] as $item)
                            @continue($item['value'] === "Shared.Admin")
                            @php
                                $permissionId = Permission::where('name', $item['value'])->value('id');
                                $isAll = \Illuminate\Support\Str::endsWith($item['value'], '.All');
                                $isAdmin = $permissionId === $adminPermissionId;
                            @endphp

                            <label class="flex items-center space-x-2 text-sm text-gray-700">
                                <input type="checkbox"
                                       wire:model="permissions"
                                       value="{{ $permissionId }}"
                                       class="form-checkbox text-blue-600"
                                       {{ !$isAdmin ? 'data-clearable' : '' }} {{-- for admin clearing --}}
                                       {{ !$isAll && !$isAdmin ? 'data-sub' : '' }} {{-- for group clearing --}}
                                       x-model="{{ $isAll ? 'allSelected' : '' }}"
                                       x-on:change="{{ $isAll ? 'toggleAll($event.target.checked)' : '' }}"
                                       x-bind:disabled="adminSelected || {{ !$isAll ? 'allSelected' : 'false' }}"
                                >
                                <span>{{ $item['title'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="flex justify-end space-x-4 mt-6">
        <button wire:click="cancel"
                type="button"
                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-md text-sm">
            انصراف
        </button>

        <button wire:click="submit"
                type="button"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm">
            {{ $edit_mode ? 'ویرایش' : 'ایجاد' }}
        </button>
    </div>
</div>
