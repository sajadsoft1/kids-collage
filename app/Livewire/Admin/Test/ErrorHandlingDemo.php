<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Test;

use DivisionByZeroError;
use Exception;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

/**
 * کامپوننت نمایشی برای تست سیستم هندل کردن خطاهای Livewire
 *
 * این کامپوننت فقط برای تست است و باید در محیط توسعه استفاده شود.
 */
class ErrorHandlingDemo extends Component
{
    use Toast;

    /**
     * تست خطای 500 - Exception عمومی
     *
     * @throws Exception
     */
    public function test500Error(): void
    {
        throw new Exception('این یک خطای تستی 500 است. سیستم هندل کردن خطا باید این را بگیرد.');
    }

    /**
     * تست خطای قسمت‌بندی بر صفر (تولید Exception)
     *
     * @throws DivisionByZeroError
     */
    public function testDivisionByZero(): void
    {
        $result = 10 / 0;
    }

    /** تست خطای دسترسی به آرایه غیرموجود */
    public function testArrayError(): void
    {
        $array = ['key' => 'value'];
        $value = $array['nonexistent']['nested'];
    }

    /** تست خطای کال کردن متد روی null */
    public function testNullMethodCall(): void
    {
        $object = null;
        $object->someMethod();
    }

    /** تست موفقیت‌آمیز (بدون خطا) */
    public function testSuccess(): void
    {
        $this->success('عملیات با موفقیت انجام شد!', position: 'toast-top toast-center');
    }

    /** تست هشدار */
    public function testWarning(): void
    {
        $this->warning('این یک پیام هشدار است.', position: 'toast-top toast-center');
    }

    /** تست اطلاعات */
    public function testInfo(): void
    {
        $this->info('این یک پیام اطلاعاتی است.', position: 'toast-top toast-center');
    }

    /** رندر کردن view */
    public function render(): View
    {
        return view('livewire.admin.test.error-handling-demo');
    }
}
