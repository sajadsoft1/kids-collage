<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class LogoutAction
{
    use AsAction;

    public function handle(User $user): void
    {
        // حذف همه توکن‌های API
        $user->tokens()->delete();

        // حذف تمام session های کاربر از دیتابیس
        DB::table(config('session.table', 'sessions'))
            ->where('user_id', $user->id)
            ->delete();

        // پاک کردن کامل session قبل از logout
        $session = request()->session();

        // Logout از guard (این کار session را flush می‌کند)
        Auth::guard('web')->logout();

        // Invalidate کردن session و regenerate کردن CSRF token
        $session->invalidate();
        $session->regenerateToken();

        // پاک کردن کامل تمام داده‌های session
        $session->flush();
    }
}
