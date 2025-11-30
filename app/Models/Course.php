<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\CourseStatusEnum;
use App\Enums\CourseTypeEnum;
use App\Facades\SmartCache;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Course Model
 *
 * A real execution of a CourseTemplate in a Term with a teacher.
 * This represents an actual course instance that students can enroll in.
 *
 * @property int              $id
 * @property int              $course_template_id
 * @property int              $term_id
 * @property int              $teacher_id
 * @property int|null         $capacity
 * @property float            $price
 * @property CourseTypeEnum   $type
 * @property CourseStatusEnum $status
 * @property array|null       $days_of_week
 * @property Carbon|null      $start_time
 * @property Carbon|null      $end_time
 * @property int|null         $room_id
 * @property string|null      $meeting_link
 * @property Carbon|null      $created_at
 * @property Carbon|null      $updated_at
 * @property Carbon|null      $deleted_at
 *
 * @property-read CourseTemplate $template
 * @property-read Term                                                         $term
 * @property-read User                                                         $teacher
 * @property-read Room|null                                                    $room
 * @property-read \Illuminate\Database\Eloquent\Collection<int, CourseSession> $sessions
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Enrollment>    $enrollments
 * @property-read OrderItem|null                                               $orderItem
 */
class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'course_template_id',
        'term_id',
        'teacher_id',
        'price',
        'capacity',
        'status',
    ];

    protected $casts = [
        'course_template_id' => 'integer',
        'term_id' => 'integer',
        'teacher_id' => 'integer',
        'price' => 'float',
        'capacity' => 'integer',
        'status' => CourseStatusEnum::class,
    ];

    /** Get the course template that this course is based on. */
    public function template(): BelongsTo
    {
        return $this->belongsTo(CourseTemplate::class, 'course_template_id');
    }

    /** Get the term that this course belongs to. */
    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class);
    }

    /** Get the teacher for this course. */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /** Get the room for this course. */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /** Get the sessions for this course. */
    public function sessions(): HasMany
    {
        return $this->hasMany(CourseSession::class);
    }

    /** Get the enrollments for this course. */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    /** Get the order item for this course. */
    public function orderItem(): MorphOne
    {
        return $this->morphOne(OrderItem::class, 'itemable');
    }

    /** Get active enrollments. */
    public function activeEnrollments(): HasMany
    {
        return $this->enrollments()->where('status', 'active');
    }

    /** Get completed sessions. */
    public function completedSessions(): HasMany
    {
        return $this->sessions()->where('status', 'done');
    }

    /** Get the current enrollment count. */
    public function getEnrollmentCountAttribute(): int
    {
        return $this->activeEnrollments()->count();
    }

    /** Check if this course is at capacity. */
    public function isAtCapacity(): bool
    {
        if ($this->capacity === null) {
            return false; // Unlimited capacity
        }

        return $this->enrollment_count >= $this->capacity;
    }

    /** Check if this course has available spots. */
    public function hasAvailableSpots(): bool
    {
        return ! $this->isAtCapacity();
    }

    /** Get the amount available spots. */
    public function getAvailableSpotsAttribute(): int
    {
        if ($this->capacity === null) {
            return PHP_INT_MAX; // Unlimited capacity
        }

        return max(0, $this->capacity - $this->enrollment_count);
    }

    /** Check if this course can accept enrollments. */
    public function canEnroll(): bool
    {
        return $this->status->canEnroll() && $this->hasAvailableSpots();
    }

    /** Check if the course can be published (scheduled). */
    public function canPublish(): bool
    {
        return $this->status === CourseStatusEnum::DRAFT;
    }

    /** Move course to scheduled state. */
    public function publish(): bool
    {
        abort_unless($this->canPublish(), 422, trans('course.exceptions.only_draft_courses_can_be_published'));

        return $this->update(['status' => CourseStatusEnum::SCHEDULED]);
    }

    /** Check if the course can be started. */
    public function canStart(): bool
    {
        return $this->status === CourseStatusEnum::SCHEDULED;
    }

    /** Start the course: clone sessions and set ACTIVE.
     * @throws Throwable
     */
    public function start(): bool
    {
        abort_unless($this->canStart(), 422, trans('course.exceptions.course_is_not_in_a_state_that_can_be_started'));

        DB::transaction(function () {
            $this->cloneSessions();
            $this->update(['status' => CourseStatusEnum::ACTIVE]);
        });

        return true;
    }

    /** Check if the course can be finished. */
    public function canFinish(): bool
    {
        return $this->status === CourseStatusEnum::ACTIVE;
    }

    /** Finish the course. */
    public function finish(): bool
    {
        abort_unless($this->canFinish(), 400, trans('course.exceptions.only_active_courses_can_be_finished'));

        return $this->update(['status' => CourseStatusEnum::FINISHED]);
    }

    /** Cancel the course from any non-finished state. */
    public function cancel(): bool
    {
        abort_if($this->status === CourseStatusEnum::FINISHED, 422, trans('course.exceptions.finished_courses_cannot_be_cancelled'));

        return $this->update(['status' => CourseStatusEnum::CANCELLED]);
    }

    /** Get the course type from template. */
    public function getTypeAttribute(): ?CourseTypeEnum
    {
        return $this->template?->type;
    }

    /** Check if this course is self-paced. */
    public function isSelfPaced(): bool
    {
        return $this->type === CourseTypeEnum::SELF_PACED;
    }

    /** Check if this course requires scheduling. */
    public function requiresSchedule(): bool
    {
        if ( ! $this->type) {
            return false;
        }

        return $this->type->requiresSchedule();
    }

    /** Check if this course requires a room. */
    public function requiresRoom(): bool
    {
        if ( ! $this->type) {
            return false;
        }

        return $this->type->requiresRoom();
    }

    /** Get the formatted price. */
    public function getFormattedPriceAttribute(): string
    {
        return '$' . number_format($this->price, 2);
    }

    /** Get the duration of each session. */
    public function getSessionDurationAttribute(): int
    {
        if ( ! $this->start_time || ! $this->end_time) {
            return 0;
        }

        return (int) $this->start_time->diffInMinutes($this->end_time);
    }

    /** Get the formatted session duration. */
    public function getFormattedSessionDurationAttribute(): string
    {
        $duration = $this->session_duration;
        $hours = floor($duration / 60);
        $minutes = $duration % 60;

        if ($hours > 0) {
            return $minutes > 0 ? "{$hours}h {$minutes}m" : "{$hours}h";
        }

        return "{$minutes}m";
    }

    /** Get the days of week as readable format. */
    public function getDaysOfWeekReadableAttribute(): string
    {
        if ( ! $this->days_of_week) {
            return 'Not scheduled';
        }

        $dayNames = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
        ];

        $days = array_map(fn ($day) => $dayNames[$day] ?? $day, $this->days_of_week);

        return implode(', ', $days);
    }

    /** Enroll a student in this course. */
    public function enrollStudent(int $userId, ?int $orderItemId = null): Enrollment
    {
        return DB::transaction(function () use ($userId, $orderItemId) {
            // Lock the course row to prevent race conditions
            $course = static::lockForUpdate()->findOrFail($this->id);

            if ( ! $course->canEnroll()) {
                abort(422, 'Course is at capacity or not accepting enrollments');
            }

            // Check if user is already enrolled
            $existingEnrollment = $course->enrollments()
                ->where('user_id', $userId)
                ->whereIn('status', ['pending', 'paid', 'active'])
                ->first();

            if ($existingEnrollment) {
                abort(422, 'User is already enrolled in this course');
            }

            return $course->enrollments()->create([
                'user_id' => $userId,
                'order_item_id' => $orderItemId,
                'status' => 'pending',
                'enrolled_at' => now(),
            ]);
        });
    }

    /** Reserve a seat for a student (temporary reservation). */
    public function reserveSeat(int $userId, int $timeoutMinutes = 15): bool
    {
        return DB::transaction(function () {
            $course = static::lockForUpdate()->findOrFail($this->id);

            return ! ( ! $course->hasAvailableSpots());
            // Implementation would depend on your reservation system
            // This is a placeholder for the actual seat reservation logic
        });
    }

    /** Clone sessions from the course template. */
    public function cloneSessions(): void
    {
        if ($this->isSelfPaced()) {
            // For self-paced courses, create virtual sessions
            $this->createVirtualSessions();
        } else {
            // For scheduled courses, create real sessions based on schedule
            $this->createScheduledSessions();
        }
    }

    /** Create virtual sessions for self-paced courses. */
    protected function createVirtualSessions(): void
    {
        $sessionTemplates = $this->template->sessionTemplates;

        foreach ($sessionTemplates as $template) {
            $this->sessions()->create([
                'session_template_id' => $template->id,
                'date' => null, // No specific date for self-paced
                'start_time' => null,
                'end_time' => null,
                'room_id' => null,
                'meeting_link' => null,
                'status' => 'planned',
                'session_type' => 'online',
            ]);
        }
    }

    /** Create scheduled sessions based on course schedule. */
    protected function createScheduledSessions(): void
    {
        $sessionTemplates = $this->template->sessionTemplates;
        $currentDate = $this->term->start_date->copy();
        $endDate = $this->term->end_date;

        foreach ($sessionTemplates as $template) {
            // Find the next available date based on days_of_week
            $sessionDate = $this->findNextAvailableDate($currentDate, $endDate);

            if ($sessionDate) {
                $this->sessions()->create([
                    'session_template_id' => $template->id,
                    'date' => $sessionDate,
                    'start_time' => $this->start_time,
                    'end_time' => $this->end_time,
                    'room_id' => $this->room_id,
                    'meeting_link' => $this->meeting_link,
                    'status' => 'planned',
                    'session_type' => $this->type?->value ?? 'online',
                ]);

                $currentDate = $sessionDate->addDay();
            }
        }
    }

    /** Find the next available date based on days of week. */
    protected function findNextAvailableDate(Carbon $startDate, Carbon $endDate): ?Carbon
    {
        $current = $startDate->copy();

        while ($current->lte($endDate)) {
            if (in_array($current->dayOfWeek, $this->days_of_week ?? [], true)) {
                return $current->copy();
            }
            $current->addDay();
        }

        return null;
    }

    /** Scope for active courses. */
    public function scopeActive($query)
    {
        return $query->where('status', CourseStatusEnum::ACTIVE->value);
    }

    /** Scope for courses by type. */
    public function scopeByType($query, CourseTypeEnum $type)
    {
        return $query->whereHas('template', function ($q) use ($type) {
            $q->where('type', $type);
        });
    }

    /** Scope for self-paced courses. */
    public function scopeSelfPaced($query)
    {
        return $query->whereHas('template', function ($q) {
            $q->where('type', CourseTypeEnum::SELF_PACED);
        });
    }

    /** Scope for instructor-led courses. */
    public function scopeInstructorLed($query)
    {
        return $query->whereHas('template', function ($q) {
            $q->where('type', '!=', CourseTypeEnum::SELF_PACED);
        });
    }

    /** Scope for courses with available spots. */
    public function scopeWithAvailableSpots($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('capacity')
                ->orWhereRaw('capacity > (SELECT COUNT(*) FROM enrollments WHERE enrollments.course_id = courses.id AND enrollments.status = "active")');
        });
    }

    /** Scope for courses by teacher. */
    public function scopeByTeacher($query, int $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    /** Scope for courses by term. */
    public function scopeByTerm($query, int $termId)
    {
        return $query->where('term_id', $termId);
    }

    /** Scope for courses by price range. */
    public function scopeByPriceRange($query, float $minPrice, float $maxPrice)
    {
        return $query->whereBetween('price', [$minPrice, $maxPrice]);
    }

    public static function latestCourses()
    {
        return SmartCache::for(__CLASS__)
            ->key('latest_courses')
            ->remember(function () {
                return self::active()
                    ->orderBy('id', 'desc')
                    ->limit(5)
                    ->get();
            }, 3600);
    }
}
