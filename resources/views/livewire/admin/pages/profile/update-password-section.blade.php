<form wire:submit.prevent="save">
    <x-card title="تغییر رمز عبور" shadow separator progress-indicator="save">
        {{-- Alert for password requirements --}}
        <x-alert class="mb-4 alert-info" icon="o-information-circle" title="الزامات رمز عبور">
            <x-slot:description>
                <div class="text-sm">
                    <ul class="mt-2 space-y-1 list-disc list-inside">
                        <li>حداقل 8 کاراکتر</li>
                        <li>شامل حروف انگلیسی (a-z یا A-Z)</li>
                        <li>شامل اعداد (0-9)</li>
                        <li>شامل حداقل یک کاراکتر خاص (@$!%*#?&)</li>
                    </ul>
                </div>
            </x-slot:description>
        </x-alert>

        {{-- Password Fields --}}
        <div class="grid grid-cols-1 gap-4">
            {{-- Current Password --}}
            <x-input :label="trans('validation.attributes.password')" wire:model="current_password" type="password" required icon="o-lock-closed"
                placeholder="رمز عبور فعلی خود را وارد کنید" />

            {{-- New Password --}}
            <x-input label="رمز عبور جدید" wire:model="password" type="password" required icon="o-key"
                placeholder="رمز عبور جدید را وارد کنید" hint="حداقل 8 کاراکتر با حروف، اعداد و کاراکتر خاص" />

            {{-- Confirm New Password --}}
            <x-input label="تایید رمز عبور جدید" wire:model="password_confirmation" type="password" required
                icon="o-key" placeholder="رمز عبور جدید را مجددا وارد کنید" />
        </div>

        {{-- Form Actions --}}
        <x-slot:actions>
            <x-button label="{{ trans('general.submit') }}" type="submit" spinner="save" icon="o-check"
                class="btn-primary" />
        </x-slot:actions>
    </x-card>
</form>
