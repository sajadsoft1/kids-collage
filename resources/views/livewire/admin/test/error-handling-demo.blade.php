<div class="p-6">
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-base-content mb-2">
            تست سیستم هندل کردن خطاهای Livewire
        </h1>
        <p class="text-base-content/70">
            این صفحه برای تست سیستم جدید هندل کردن خطاها در Livewire طراحی شده است.
            با کلیک روی هر دکمه، نوع خاصی از خطا یا پیام تولید می‌شود.
        </p>
    </div>

    {{-- Alert Box --}}
    <div role="alert" class="alert alert-warning mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <span>
            <strong>توجه:</strong> این کامپوننت فقط برای تست است. در محیط production حذف شود.
        </span>
    </div>

    {{-- Test Buttons Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

        {{-- Error Tests Card --}}
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title text-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    تست خطاهای 500
                </h2>
                <p class="text-sm text-base-content/70 mb-4">
                    این دکمه‌ها خطاهای مختلف سرور را شبیه‌سازی می‌کنند.
                </p>
                <div class="flex flex-col gap-2">
                    <button wire:click="test500Error" class="btn btn-error btn-sm">
                        <i class="fas fa-bomb"></i>
                        Exception عمومی
                    </button>
                    <button wire:click="testDivisionByZero" class="btn btn-error btn-sm">
                        <i class="fas fa-divide"></i>
                        تقسیم بر صفر
                    </button>
                    <button wire:click="testArrayError" class="btn btn-error btn-sm">
                        <i class="fas fa-list"></i>
                        خطای آرایه
                    </button>
                    <button wire:click="testNullMethodCall" class="btn btn-error btn-sm">
                        <i class="fas fa-ban"></i>
                        Call on Null
                    </button>
                </div>
            </div>
        </div>

        {{-- Success Tests Card --}}
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title text-success">
                    <i class="fas fa-check-circle"></i>
                    تست پیام‌های موفق
                </h2>
                <p class="text-sm text-base-content/70 mb-4">
                    این دکمه‌ها پیام‌های موفقیت‌آمیز نمایش می‌دهند.
                </p>
                <div class="flex flex-col gap-2">
                    <button wire:click="testSuccess" class="btn btn-success btn-sm">
                        <i class="fas fa-check"></i>
                        پیام موفقیت
                    </button>
                    <button wire:click="testWarning" class="btn btn-warning btn-sm">
                        <i class="fas fa-exclamation"></i>
                        پیام هشدار
                    </button>
                    <button wire:click="testInfo" class="btn btn-info btn-sm">
                        <i class="fas fa-info-circle"></i>
                        پیام اطلاعاتی
                    </button>
                </div>
            </div>
        </div>

        {{-- Information Card --}}
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title text-info">
                    <i class="fas fa-info-circle"></i>
                    اطلاعات سیستم
                </h2>
                <div class="text-sm space-y-2">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-cog text-base-content/50"></i>
                        <span>Debug Mode:
                            <span class="badge badge-sm {{ config('app.debug') ? 'badge-warning' : 'badge-success' }}">
                                {{ config('app.debug') ? 'ON' : 'OFF' }}
                            </span>
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-server text-base-content/50"></i>
                        <span>Environment:
                            <span class="badge badge-sm badge-primary">
                                {{ app()->environment() }}
                            </span>
                        </span>
                    </div>
                    <div class="divider my-2"></div>
                    <p class="text-xs text-base-content/60">
                        در حالت Debug، پیام دقیق خطا نمایش داده می‌شود.
                        در حالت Production، پیام عمومی نمایش داده می‌شود.
                    </p>
                </div>
            </div>
        </div>

    </div>

    {{-- Documentation Card --}}
    <div class="card bg-base-100 shadow-xl mt-6">
        <div class="card-body">
            <h2 class="card-title">
                <i class="fas fa-book"></i>
                نحوه کار سیستم
            </h2>
            <div class="prose max-w-none">
                <p class="text-sm text-base-content/70">
                    سیستم هندل کردن خطا شامل دو بخش اصلی است:
                </p>
                <ul class="text-sm text-base-content/70">
                    <li>
                        <strong>Exception Handler (bootstrap/app.php):</strong>
                        تمام خطاهای Livewire را شناسایی و به صورت JSON برمی‌گرداند
                    </li>
                    <li>
                        <strong>Livewire Hook (resources/js/app.js):</strong>
                        خطاها را در سمت کلاینت catch کرده و toast نمایش می‌دهد
                    </li>
                </ul>
                <div class="alert alert-info mt-4">
                    <i class="fas fa-lightbulb"></i>
                    <span class="text-sm">
                        برای مشاهده مستندات کامل، فایل
                        <code class="text-xs">LIVEWIRE_ERROR_HANDLING.md</code>
                        را مطالعه کنید.
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Loading Indicator --}}
    <div wire:loading class="fixed top-4 left-4 z-50">
        <div class="alert alert-info shadow-lg">
            <span class="loading loading-spinner"></span>
            <span>در حال پردازش...</span>
        </div>
    </div>
</div>
