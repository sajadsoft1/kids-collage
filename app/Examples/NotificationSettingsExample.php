<?php

declare(strict_types=1);

namespace App\Examples;

use App\Enums\NotificationChannelEnum;
use App\Enums\NotificationEventEnum;
use App\Models\User;

/**
 * Example usage of Notification Settings System
 *
 * This file demonstrates how to use the notification settings system
 * in your application when sending notifications to users.
 */
class NotificationSettingsExample
{
    /** Example 1: Check if user should receive a notification */
    public function checkUserNotificationPreference(User $user): void
    {
        // Check if user wants to receive ORDER_CREATED notifications via SMS
        if ($user->profile?->shouldReceiveNotification(
            NotificationEventEnum::ORDER_CREATED,
            NotificationChannelEnum::SMS
        )) {
            // Send SMS notification
            // SMS::send($user->mobile, 'Your order has been created');
        }

        // Check if user wants to receive ORDER_PAID notifications via EMAIL
        if ($user->profile?->shouldReceiveNotification(
            NotificationEventEnum::ORDER_PAID,
            NotificationChannelEnum::EMAIL
        )) {
            // Send Email notification
            // Mail::to($user->email)->send(new OrderPaidMail($order));
        }
    }

    /** Example 2: Get all enabled channels for a specific event */
    public function getEnabledChannelsForEvent(User $user): void
    {
        $enabledChannels = $user->profile?->getEnabledChannelsForEvent(
            NotificationEventEnum::PAYMENT_SUCCESS
        );

        // $enabledChannels might return: ['sms', 'email', 'push']
        // You can then loop through and send to each channel
        foreach ($enabledChannels as $channel) {
            match ($channel) {
                'sms' => $this->sendSmsNotification($user),
                'email' => $this->sendEmailNotification($user),
                'push' => $this->sendPushNotification($user),
                'database' => $this->sendDatabaseNotification($user),
                default => null,
            };
        }
    }

    /** Example 3: Send notification to multiple channels based on user preferences */
    public function sendNotificationRespectingUserPreferences(User $user, NotificationEventEnum $event): void
    {
        // Check each channel and send if enabled
        foreach (NotificationChannelEnum::cases() as $channel) {
            if ($user->profile?->shouldReceiveNotification($event, $channel)) {
                match ($channel) {
                    NotificationChannelEnum::SMS => $this->sendSmsNotification($user),
                    NotificationChannelEnum::EMAIL => $this->sendEmailNotification($user),
                    NotificationChannelEnum::PUSH => $this->sendPushNotification($user),
                    NotificationChannelEnum::DATABASE => $this->sendDatabaseNotification($user),
                };
            }
        }
    }

    /** Example 4: Programmatically update user notification settings */
    public function updateUserSettings(User $user): void
    {
        // Enable SMS for order notifications
        $user->profile?->enableNotification(
            NotificationEventEnum::ORDER_CREATED,
            NotificationChannelEnum::SMS
        );

        // Disable email for payment notifications
        $user->profile?->disableNotification(
            NotificationEventEnum::PAYMENT_SUCCESS,
            NotificationChannelEnum::EMAIL
        );

        // Or update all settings at once
        $settings = [
            'order_created' => [
                'sms' => true,
                'email' => true,
                'push' => false,
                'database' => true,
            ],
            'payment_success' => [
                'sms' => true,
                'email' => false,
                'push' => true,
                'database' => true,
            ],
        ];
        $user->profile?->updateNotificationSettings($settings);
    }

    /** Example 5: Use in a Laravel Notification */
    public function viaChannels(User $user): array
    {
        $channels = [];
        $event    = NotificationEventEnum::ORDER_CREATED;

        if ($user->profile?->shouldReceiveNotification($event, NotificationChannelEnum::SMS)) {
            $channels[] = 'sms';
        }

        if ($user->profile?->shouldReceiveNotification($event, NotificationChannelEnum::EMAIL)) {
            $channels[] = 'mail';
        }

        if ($user->profile?->shouldReceiveNotification($event, NotificationChannelEnum::DATABASE)) {
            $channels[] = 'database';
        }

        return $channels;
    }

    // Helper methods (implement these based on your notification system)
    private function sendSmsNotification(User $user): void {}

    private function sendEmailNotification(User $user): void {}

    private function sendPushNotification(User $user): void {}

    private function sendDatabaseNotification(User $user): void {}
}
