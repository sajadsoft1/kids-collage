<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Shared;

use App\Helpers\NotifyHelper;
use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class Header extends Component
{
    use Toast;

    public bool $notifications_drawer = false;

    public function toastNotification($notificationId): void
    {
        $this->info(NotifyHelper::subTitle(DatabaseNotification::find($notificationId)->data));
    }

    public function render(): View
    {
        return view('livewire.admin.shared.header', [
            'notificaations' => DatabaseNotification::where('notifiable_type', User::class)
                ->where('notifiable_id', auth()->id())
                ->where('read_at', null)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
        ]);
    }
}
