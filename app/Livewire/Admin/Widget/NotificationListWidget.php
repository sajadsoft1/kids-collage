<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Widget;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Mary\Traits\Toast;

/**
 * Widget for displaying user notifications
 *
 * @property int  $limit
 * @property bool $show_read
 */
class NotificationListWidget extends Component
{
    use Toast;

    public User $user;

    public int $limit = 10;

    public bool $show_read = false;

    public string $filter_type = '';

    /** Mount the component */
    public function mount(?User $user = null, int $limit = 10, bool $show_read = false): void
    {
        if ($user?->id) {
            $this->user = $user;
        } else {
            $authUser = auth()->user();
            if ( ! $authUser instanceof User) {
                abort(401, 'Unauthorized');
            }
            $this->user = $authUser;
        }

        $this->limit = $limit;
        $this->show_read = $show_read;
    }

    /** Get notifications */
    #[Computed]
    public function notifications()
    {
        $query = $this->user->notifications();

        if ( ! $this->show_read) {
            $query->whereNull('read_at');
        }

        if ($this->filter_type) {
            $query->where('type', $this->filter_type);
        }

        return $query->latest()->limit($this->limit)->get();
    }

    /** Get unread count */
    #[Computed]
    public function unreadCount(): int
    {
        return $this->user->unreadNotifications()->count();
    }

    /** Get notification statistics */
    #[Computed]
    public function notificationStats(): array
    {
        return [
            'total' => $this->user->notifications()->count(),
            'unread' => $this->user->unreadNotifications()->count(),
            'read' => $this->user->notifications()->whereNotNull('read_at')->count(),
        ];
    }

    /** Mark notification as read */
    public function markAsRead(string $notificationId): void
    {
        $notification = $this->user->notifications()->find($notificationId);

        if ($notification) {
            $notification->markAsRead();
            $this->success(
                title: 'نوتیفیکیشن به عنوان خوانده شده علامت گذاری شد',
                timeout: 2000
            );
        }
    }

    /** Mark all as read */
    public function markAllAsRead(): void
    {
        $this->user->unreadNotifications->markAsRead();
        $this->success(
            title: 'همه نوتیفیکیشن‌ها خوانده شدند',
            timeout: 2000
        );
    }

    /** Delete notification */
    public function deleteNotification(string $notificationId): void
    {
        $notification = $this->user->notifications()->find($notificationId);

        if ($notification) {
            $notification->delete();
            $this->success(
                title: 'نوتیفیکیشن حذف شد',
                timeout: 2000
            );
        }
    }

    /** Delete all read notifications */
    public function deleteAllRead(): void
    {
        $this->user->notifications()->whereNotNull('read_at')->delete();
        $this->success(
            title: 'همه نوتیفیکیشن‌های خوانده شده حذف شدند',
            timeout: 2000
        );
    }

    /** Toggle show read */
    public function toggleShowRead(): void
    {
        $this->show_read = ! $this->show_read;
    }

    /** Filter by type */
    public function filterByType(string $type): void
    {
        $this->filter_type = ($this->filter_type === $type) ? '' : $type;
    }

    /** Get notification icon based on type */
    public function getNotificationIcon(string $type): string
    {
        return match (true) {
            str_contains($type, 'Order') => 'o-shopping-cart',
            str_contains($type, 'Payment') => 'o-credit-card',
            str_contains($type, 'Enrollment') => 'o-academic-cap',
            str_contains($type, 'Course') => 'o-book-open',
            str_contains($type, 'Auth') => 'o-shield-check',
            default => 'o-bell',
        };
    }

    /** Get notification color based on type */
    public function getNotificationColor(string $type): string
    {
        return match (true) {
            str_contains($type, 'Order') => 'text-blue-500',
            str_contains($type, 'Payment') => 'text-green-500',
            str_contains($type, 'Enrollment') => 'text-purple-500',
            str_contains($type, 'Course') => 'text-orange-500',
            str_contains($type, 'Auth') => 'text-indigo-500',
            default => 'text-gray-500',
        };
    }

    /** Refresh notifications */
    #[On('notification-received')]
    public function refreshNotifications(): void
    {
        unset($this->notifications, $this->unreadCount, $this->notificationStats);
    }

    /** Render the component */
    public function render(): View
    {
        return view('livewire.admin.widget.notification-list-widget');
    }
}
