<?php

declare(strict_types=1);

namespace App\Notifications\Courses;

use App\Enums\NotificationEventEnum;
use App\Models\CourseSession;
use App\Notifications\BaseNotification;

class CourseSessionReminderNotification extends BaseNotification
{
    public function __construct(private readonly CourseSession $session)
    {
        parent::__construct();
    }

    public function event(): NotificationEventEnum
    {
        return NotificationEventEnum::COURSE_SESSION_REMINDER;
    }

    /** @return array<string, mixed> */
    protected function context(object $notifiable): array
    {
        $course = $this->session->course;
        $startAt = $this->session->start_time?->format('H:i');
        $sessionDate = $this->session->date?->format('Y-m-d');

        return [
            'user_name' => $notifiable->name ?? $notifiable->full_name ?? null,
            'course_title' => $course?->title ?? $this->session->course?->title ?? '',
            'session_date' => $sessionDate,
            'session_time' => $startAt,
            'action_url' => url('/courses'),
        ];
    }
}
