<x-modal wire:model="showChangePasswordModal" max-width="md" :title="$passwordUser ? 'تغییر رمز عبور برای ' . $this->passwordUserName : 'تغییر رمز عبور'" class="backdrop-blur">
    <div class="space-y-4">
        <x-input type="password" label="رمز عبور جدید" wire:model.defer="password" icon="o-key"
            helper-text="حداقل ۸ کاراکتر" />
        <x-input type="password" label="تکرار رمز عبور" wire:model.defer="password_confirmation" icon="o-key" />
    </div>

    <x-slot:actions separator>
        <div class="flex justify-end gap-3">
            <x-button label="انصراف" @click="$wire.closeChangePasswordModal()" />
            <x-button label="ثبت تغییرات" class="btn-primary" wire:click="updatePassword" spinner="updatePassword" />
        </div>
    </x-slot:actions>
</x-modal>
