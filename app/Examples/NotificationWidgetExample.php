<?php

declare(strict_types=1);

namespace App\Examples;

use App\Models\User;
use Illuminate\Notifications\Notification;

/**
 * Example usage of NotificationListWidget
 *
 * This file demonstrates how to:
 * 1. Use the NotificationListWidget in Blade views
 * 2. Create and send notifications
 * 3. Integrate with the notification settings system
 */
class NotificationWidgetExample
{
    /**
     * Example 1: Use widget in Blade view
     *
     * Simply add this to any Blade file:
     *
     * @livewire(admin.widget.notification-list-widget)
     *
     * Or with parameters:
     *
     * @livewire(admin.widget.notification-list-widget, ['limit=5, 'show_read=true)
     */
    public function exampleBladeUsage(): string
    {
        return <<<'BLADE'
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    {{-- Your main content --}}
                </div>

                <div>
                    {{-- Notification Widget in Sidebar --}}
                    @livewire('admin.widget.notification-list-widget', ['limit' => 10])
                </div>
            </div>
            BLADE;
    }

    /** Example 2: Send a simple notification */
    public function sendSimpleNotification(User $user): void
    {
        $user->notify(new class extends Notification {
            public function via($notifiable): array
            {
                return ['database'];
            }

            public function toArray($notifiable): array
            {
                return [
                    'title'   => 'عنوان اعلان',
                    'message' => 'پیام اعلان شما',
                    'link'    => route('admin.dashboard'),
                ];
            }
        });
    }

    /** Example 3: Send notification respecting user settings */
    public function sendNotificationWithSettings(User $user): void
    {
        // Import enums
        // use App\Enums\NotificationEventEnum;
        // use App\Enums\NotificationChannelEnum;

        // Check if user wants database notifications for orders
        // if ($user->profile?->shouldReceiveNotification(
        //     NotificationEventEnum::ORDER_CREATED,
        //     NotificationChannelEnum::DATABASE
        // )) {
        //     $user->notify(new OrderCreatedNotification($order));
        // }
    }

    /**
     * Example 4: Real-time notification update
     *
     * When a new notification is sent, dispatch a Livewire event:
     *
     * After sending notification:
     * $this->dispatch('notification-received')->to(NotificationListWidget::class);
     */
    public function sendRealtimeNotification(User $user): void
    {
        // Send notification
        $user->notify(new class extends Notification {
            public function via($notifiable): array
            {
                return ['database'];
            }

            public function toArray($notifiable): array
            {
                return [
                    'title'   => 'اعلان جدید',
                    'message' => 'شما یک اعلان جدید دریافت کردید',
                ];
            }
        });

        // Trigger widget refresh (in Livewire component)
        // $this->dispatch('notification-received');
    }

    /** Example 5: Create a custom notification class */
    public function customNotificationExample(): string
    {
        return <<<'PHP'
            <?php

            namespace App\Notifications;

            use App\Models\Order;
            use Illuminate\Notifications\Notification;

            class OrderCreatedNotification extends Notification
            {
                public function __construct(public Order $order) {}

                public function via($notifiable): array
                {
                    // You can check user preferences here
                    $channels = [];

                    if ($notifiable->profile?->shouldReceiveNotification(
                        NotificationEventEnum::ORDER_CREATED,
                        NotificationChannelEnum::DATABASE
                    )) {
                        $channels[] = 'database';
                    }

                    if ($notifiable->profile?->shouldReceiveNotification(
                        NotificationEventEnum::ORDER_CREATED,
                        NotificationChannelEnum::EMAIL
                    )) {
                        $channels[] = 'mail';
                    }

                    return $channels;
                }

                public function toDatabase($notifiable): array
                {
                    return [
                        'title'    => 'سفارش جدید ثبت شد',
                        'message'  => "سفارش شما با شماره {$this->order->id} ثبت شد",
                        'link'     => route('admin.order.show', $this->order),
                        'order_id' => $this->order->id,
                        'type'     => 'order',
                    ];
                }

                public function toMail($notifiable)
                {
                    return (new MailMessage)
                        ->subject('سفارش جدید')
                        ->line("سفارش شما با شماره {$this->order->id} ثبت شد")
                        ->action('مشاهده سفارش', route('admin.order.show', $this->order));
                }
            }
            PHP;
    }

    /** Example 6: Widget parameters */
    public function widgetParameters(): array
    {
        return [
            'user'      => null,     // User instance (default: auth()->user())
            'limit'     => 10,       // Number of notifications to show
            'show_read' => false,    // Show read notifications
        ];
    }

    /** Example 7: Available methods in widget */
    public function availableMethods(): array
    {
        return [
            'markAsRead($notificationId)' => 'Mark single notification as read',
            'markAllAsRead()'             => 'Mark all notifications as read',
            'deleteNotification($id)'     => 'Delete single notification',
            'deleteAllRead()'             => 'Delete all read notifications',
            'toggleShowRead()'            => 'Toggle showing read notifications',
            'filterByType($type)'         => 'Filter by notification type',
            'refreshNotifications()'      => 'Refresh notification list',
        ];
    }
}
