<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\CourseSession;
use App\Models\Profile;
use App\Models\User;

class CourseSessionReminderDispatched
{
    public function __construct(
        public readonly User $user,
        public readonly CourseSession $session,
        public readonly ?Profile $profile = null,
    ) {}
}
