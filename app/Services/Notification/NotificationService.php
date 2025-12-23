<?php

declare(strict_types=1);

namespace App\Services\Notification;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class NotificationService
{
    /** Get unread notifications count for user */
    public function getUnreadCount(User $user): int
    {
        return Cache::remember(
            "user.{$user->id}.unread_notifications_count",
            60, // 1 minute cache
            fn () => $user->unreadNotifications()->count()
        );
    }

    /**
     * Get recent notifications for user
     *
     * @return array<int, array{id: string, title: string, body: string, created_at: string, read_at: string|null}>
     */
    public function getRecentNotifications(User $user, int $limit = 10): array
    {
        return $user->notifications()
            ->latest()
            ->limit($limit)
            ->get()
            ->map(function ($notification) {
                $data = $notification->data ?? [];

                return [
                    'id' => $notification->id,
                    'title' => $data['title'] ?? 'اعلان جدید',
                    'body' => $data['body'] ?? $data['subtitle'] ?? '',
                    'created_at' => $notification->created_at?->diffForHumans() ?? 'همین الان',
                    'read_at' => $notification->read_at,
                ];
            })
            ->toArray();
    }

    /** Clear notification cache for user */
    public function clearCache(User $user): void
    {
        Cache::forget("user.{$user->id}.unread_notifications_count");
    }
}
