<div class="space-y-6">
    {{-- Warning Alert --}}
    <x-alert class="alert-warning" icon="o-exclamation-triangle" title="هشدار امنیتی">
        <x-slot:description>
            <p class="text-sm">
                با خروج از حساب کاربری، تمام اطلاعات session شما پاک می‌شود و باید مجدداً وارد شوید.
                اگر از دستگاه عمومی یا مشترک استفاده می‌کنید، حتماً از حساب خود خارج شوید.
            </p>
        </x-slot:description>
    </x-alert>

    {{-- Current Session Information --}}
    <x-card title="اطلاعات Session فعلی" shadow separator>
        <div class="space-y-4">
            {{-- User Info --}}
            <div class="flex items-center gap-4 p-4 rounded-lg bg-base-200">
                <div class="avatar">
                    <div class="w-16 h-16 rounded-full ring ring-primary ring-offset-2">
                        <img src="{{ $user->getFirstMediaUrl('avatar') }}" alt="{{ $user->full_name }}" />
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold">{{ $user->full_name }}</h3>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    <p class="text-sm text-gray-500">{{ $user->mobile }}</p>
                </div>
                <div class="text-sm text-success">
                    <x-icon name="o-check-circle" class="inline w-4 h-4" />
                    آنلاین
                </div>
            </div>

            <div class="divider">جزئیات دستگاه و مرورگر</div>

            {{-- Device Information --}}
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                {{-- Device --}}
                <div class="flex items-start gap-3 p-4 rounded-lg bg-base-200">
                    <div class="mt-1">
                        <x-icon :name="$this->getDeviceIcon()" class="w-8 h-8 text-primary" />
                    </div>
                    <div class="flex-1">
                        <div class="text-xs text-gray-500">دستگاه</div>
                        <div class="font-semibold">{{ $sessionInfo['device'] }}</div>
                        <div class="text-xs text-gray-500">
                            {{ $sessionInfo['platform'] }}
                            @if ($sessionInfo['platform_version'])
                                {{ $sessionInfo['platform_version'] }}
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Browser --}}
                <div class="flex items-start gap-3 p-4 rounded-lg bg-base-200">
                    <div class="mt-1">
                        <x-icon :name="$this->getBrowserIcon($sessionInfo['browser'])" class="w-8 h-8 text-primary" />
                    </div>
                    <div class="flex-1">
                        <div class="text-xs text-gray-500">مرورگر</div>
                        <div class="font-semibold">{{ $sessionInfo['browser'] }}</div>
                        <div class="text-xs text-gray-500">
                            @if ($sessionInfo['browser_version'])
                                نسخه {{ $sessionInfo['browser_version'] }}
                            @endif
                        </div>
                    </div>
                </div>

                {{-- IP Address --}}
                <div class="flex items-start gap-3 p-4 rounded-lg bg-base-200">
                    <div class="mt-1">
                        <x-icon name="o-signal" class="w-8 h-8 text-primary" />
                    </div>
                    <div class="flex-1">
                        <div class="text-xs text-gray-500">آدرس IP</div>
                        <div class="font-mono font-semibold">{{ $sessionInfo['ip'] }}</div>
                        <div class="text-xs text-gray-500">آدرس اینترنت شما</div>
                    </div>
                </div>

                {{-- Last Activity --}}
                <div class="flex items-start gap-3 p-4 rounded-lg bg-base-200">
                    <div class="mt-1">
                        <x-icon name="o-clock" class="w-8 h-8 text-primary" />
                    </div>
                    <div class="flex-1">
                        <div class="text-xs text-gray-500">آخرین فعالیت</div>
                        <div class="font-semibold">{{ $sessionInfo['last_activity']->diffForHumans() }}</div>
                        <div class="text-xs text-gray-500">
                            {{ $sessionInfo['last_activity']->format('Y/m/d H:i') }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Session ID (Technical Info) --}}
            <details class="collapse collapse-arrow bg-base-200">
                <summary class="text-sm font-medium collapse-title">اطلاعات فنی Session</summary>
                <div class="collapse-content">
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Session ID:</span>
                            <code
                                class="px-2 py-1 rounded bg-base-300">{{ substr($sessionInfo['session_id'], 0, 20) }}...</code>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">User Agent:</span>
                            <code
                                class="max-w-xs px-2 py-1 text-xs truncate rounded bg-base-300">{{ substr($sessionInfo['user_agent'], 0, 50) }}...</code>
                        </div>
                    </div>
                </div>
            </details>
        </div>
    </x-card>

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
