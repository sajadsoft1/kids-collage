<div class="space-y-6">
    {{-- Security Actions --}}
    <x-card title="عملیات امنیتی" shadow separator>
        <div class="space-y-4">
            {{-- Security Tips --}}
            <x-alert class="alert-info" icon="o-information-circle" title="نکات امنیتی">
                <x-slot:description>
                    <ul class="space-y-1 text-sm list-disc list-inside">
                        <li>همیشه پس از استفاده از حساب خود خارج شوید</li>
                        <li>از دستگاه‌های عمومی با احتیاط استفاده کنید</li>
                        <li>رمز عبور خود را به صورت دوره‌ای تغییر دهید</li>
                        <li>اگر فعالیت مشکوکی مشاهده کردید، سریعاً از همه دستگاه‌ها خارج شوید</li>
                    </ul>
                </x-slot:description>
            </x-alert>

            <div class="divider">اقدامات</div>

            {{-- Action Buttons --}}
            <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                {{-- Logout Current Session --}}
                <x-button label="خروج از این دستگاه" icon="o-arrow-left-on-rectangle" class="btn-error"
                    wire:click="logout" wire:confirm="آیا مطمئن هستید که می‌خواهید خارج شوید؟" spinner />

                {{-- Clear Cache --}}
                <x-button label="پاکسازی Cache مرورگر" icon="o-trash" class="btn-outline btn-warning"
                    wire:click="suggestClearCache" spinner />
            </div>

            {{-- Logout from all devices (commented - needs password confirmation) --}}
            {{--
            <div class="pt-4 mt-4 border-t">
                <x-button
                    label="خروج از همه دستگاه‌ها"
                    icon="o-device-phone-mobile"
                    class="w-full btn-outline btn-error"
                    wire:click="logoutFromAllDevices"
                    wire:confirm="این عملیات شما را از همه دستگاه‌ها خارج می‌کند. ادامه می‌دهید؟"
                    spinner
                />
                <p class="mt-2 text-xs text-center text-gray-500">
                    با این عملیات از تمام دستگاه‌هایی که به حساب شما متصل هستند خارج می‌شوید
                </p>
            </div>
            --}}
        </div>
    </x-card>

    {{-- Additional Security Info --}}
    <x-card title="نکات امنیتی" shadow class="bg-gradient-to-br from-primary/5 to-secondary/5">
        <div class="space-y-3">
            <div class="flex items-start gap-3">
                <x-icon name="o-shield-check" class="w-6 h-6 mt-1 text-success" />
                <div>
                    <h4 class="font-semibold">اتصال امن</h4>
                    <p class="text-sm text-gray-600">اتصال شما از طریق HTTPS رمزنگاری شده است</p>
                </div>
            </div>

            <div class="flex items-start gap-3">
                <x-icon name="o-lock-closed" class="w-6 h-6 mt-1 text-success" />
                <div>
                    <h4 class="font-semibold">Session امن</h4>
                    <p class="text-sm text-gray-600">Session شما با استفاده از توکن‌های امن محافظت می‌شود</p>
                </div>
            </div>

            <div class="flex items-start gap-3">
                <x-icon name="o-eye-slash" class="w-6 h-6 mt-1 text-success" />
                <div>
                    <h4 class="font-semibold">حفظ حریم خصوصی</h4>
                    <p class="text-sm text-gray-600">اطلاعات شما به صورت محرمانه نگهداری می‌شود</p>
                </div>
            </div>
        </div>
    </x-card>
</div>
