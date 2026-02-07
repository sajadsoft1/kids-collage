<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * ActivityLog Model
 *
 * Tracks user activities and events throughout the system.
 * Used for progress tracking, auditing, and analytics.
 *
 * @property int                 $id
 * @property string|null         $log_name
 * @property string              $description
 * @property string|null         $subject_type
 * @property int|null            $subject_id
 * @property string|null         $causer_type
 * @property int|null            $causer_id
 * @property string|null         $event
 * @property array|null          $properties
 * @property string|null         $batch_uuid
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 *
 * @property-read Model|null $subject
 * @property-read Model|null $causer
 */
class ActivityLog extends Model
{
    use HasFactory;

    /** Table name from config so it matches the migration (default: activity_log). */
    protected $table;

    protected $fillable = [
        'log_name',
        'description',
        'subject_type',
        'subject_id',
        'causer_type',
        'causer_id',
        'event',
        'properties',
        'batch_uuid',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = config('activitylog.table_name', 'activity_log');
        parent::__construct($attributes);
    }

    /** Get the subject of the activity (what the activity is about). */
    public function subject(): MorphTo
    {
        return $this->morphTo('subject');
    }

    /** Get the causer of the activity (who performed the activity). */
    public function causer(): MorphTo
    {
        return $this->morphTo('causer');
    }

    /** Get the user who caused this activity. */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'causer_id');
    }

    /** Get the formatted description with context. */
    public function getFormattedDescriptionAttribute(): string
    {
        $description = $this->description;

        if ($this->causer) {
            $causerName = $this->causer->name ?? 'Unknown User';
            $description = "{$causerName}: {$description}";
        }

        return $description;
    }

    /** Get the time elapsed since this activity. */
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    /** Check if this activity is related to a course. */
    public function isCourseRelated(): bool
    {
        return in_array($this->subject_type, [
            Course::class,
            Enrollment::class,
            CourseSession::class,
            Attendance::class,
            Certificate::class,
        ]);
    }

    /** Check if this activity is related to progress. */
    public function isProgressRelated(): bool
    {
        return in_array($this->event, [
            'progress.updated',
            'enrollment.completed',
            'session.attended',
            'video.watched',
            'resource.accessed',
        ]);
    }

    /** Check if this activity is related to enrollment. */
    public function isEnrollmentRelated(): bool
    {
        return in_array($this->event, [
            'enrollment.created',
            'enrollment.completed',
            'enrollment.dropped',
            'enrollment.reactivated',
        ]);
    }

    /** Get the course associated with this activity. */
    public function getCourseAttribute(): ?Course
    {
        if ($this->subject_type === Course::class) {
            return $this->subject;
        }

        if ($this->subject_type === Enrollment::class) {
            return $this->subject->course ?? null;
        }

        if ($this->subject_type === CourseSession::class) {
            return $this->subject->course ?? null;
        }

        if ($this->subject_type === Attendance::class) {
            return $this->subject->enrollment->course ?? null;
        }

        if ($this->subject_type === Certificate::class) {
            return $this->subject->enrollment->course ?? null;
        }

        return null;
    }

    /** Get the enrollment associated with this activity. */
    public function getEnrollmentAttribute(): ?Enrollment
    {
        if ($this->subject_type === Enrollment::class) {
            return $this->subject;
        }

        if ($this->subject_type === Attendance::class) {
            return $this->subject->enrollment ?? null;
        }

        if ($this->subject_type === Certificate::class) {
            return $this->subject->enrollment ?? null;
        }

        return null;
    }

    /** Get the session associated with this activity. */
    public function getSessionAttribute(): ?CourseSession
    {
        if ($this->subject_type === CourseSession::class) {
            return $this->subject;
        }

        if ($this->subject_type === Attendance::class) {
            return $this->subject->session ?? null;
        }

        return null;
    }

    /** Get the activity icon based on event type. */
    public function getIconAttribute(): string
    {
        return match ($this->event) {
            'enrollment.created' => 'user-plus',
            'enrollment.completed' => 'check-circle',
            'enrollment.dropped' => 'user-minus',
            'progress.updated' => 'trending-up',
            'session.attended' => 'calendar-check',
            'session.cancelled' => 'calendar-x',
            'video.watched' => 'play-circle',
            'resource.accessed' => 'download',
            'certificate.issued' => 'award',
            'payment.completed' => 'credit-card',
            default => 'activity',
        };
    }

    /** Get the activity color based on event type. */
    public function getColorAttribute(): string
    {
        return match ($this->event) {
            'enrollment.created', 'enrollment.completed', 'certificate.issued' => 'green',
            'enrollment.dropped', 'session.cancelled' => 'red',
            'progress.updated', 'session.attended' => 'blue',
            'video.watched', 'resource.accessed' => 'purple',
            'payment.completed' => 'yellow',
            default => 'gray',
        };
    }

    /** Scope for activities by log name. */
    public function scopeByLogName($query, string $logName)
    {
        return $query->where('log_name', $logName);
    }

    /** Scope for activities by event. */
    public function scopeByEvent($query, string $event)
    {
        return $query->where('event', $event);
    }

    /** Scope for activities by causer. */
    public function scopeByCauser($query, string $causerType, int $causerId)
    {
        return $query->where('causer_type', $causerType)
            ->where('causer_id', $causerId);
    }

    /** Scope for activities by subject. */
    public function scopeBySubject($query, string $subjectType, int $subjectId)
    {
        return $query->where('subject_type', $subjectType)
            ->where('subject_id', $subjectId);
    }

    /** Scope for course-related activities. */
    public function scopeCourseRelated($query)
    {
        return $query->whereIn('subject_type', [
            Course::class,
            Enrollment::class,
            CourseSession::class,
            Attendance::class,
            Certificate::class,
        ]);
    }

    /** Scope for enrollment-related activities. */
    public function scopeEnrollmentRelated($query)
    {
        return $query->whereIn('event', [
            'enrollment.created',
            'enrollment.completed',
            'enrollment.dropped',
            'enrollment.reactivated',
        ]);
    }

    /** Scope for progress-related activities. */
    public function scopeProgressRelated($query)
    {
        return $query->whereIn('event', [
            'progress.updated',
            'enrollment.completed',
            'session.attended',
            'video.watched',
            'resource.accessed',
        ]);
    }

    /** Scope for activities by date range. */
    public function scopeByDateRange($query, \Carbon\Carbon $startDate, \Carbon\Carbon $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /** Scope for recent activities. */
    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /** Scope for activities by batch. */
    public function scopeByBatch($query, string $batchUuid)
    {
        return $query->where('batch_uuid', $batchUuid);
    }

    /** Get activity statistics for a user. */
    public static function getUserStatistics(int $userId, int $days = 30): array
    {
        $query = static::byCauser(User::class, $userId)
            ->where('created_at', '>=', now()->subDays($days));

        return [
            'total_activities' => $query->count(),
            'enrollment_activities' => $query->enrollmentRelated()->count(),
            'progress_activities' => $query->progressRelated()->count(),
            'course_activities' => $query->courseRelated()->count(),
            'recent_activities' => $query->recent(7)->count(),
            'events' => $query->selectRaw('event, COUNT(*) as count')
                ->groupBy('event')
                ->pluck('count', 'event')
                ->toArray(),
        ];
    }

    /** Get course activity statistics. */
    public static function getCourseStatistics(int $courseId, int $days = 30): array
    {
        $query = static::courseRelated()
            ->where('created_at', '>=', now()->subDays($days))
            ->where(function ($q) use ($courseId) {
                $q->bySubject(Course::class, $courseId)
                    ->orWhereHasMorph('subject', [Enrollment::class, CourseSession::class, Attendance::class, Certificate::class], function ($subQ) use ($courseId) {
                        $subQ->where('course_id', $courseId);
                    });
            });

        return [
            'total_activities' => $query->count(),
            'enrollment_activities' => $query->enrollmentRelated()->count(),
            'progress_activities' => $query->progressRelated()->count(),
            'recent_activities' => $query->recent(7)->count(),
            'events' => $query->selectRaw('event, COUNT(*) as count')
                ->groupBy('event')
                ->pluck('count', 'event')
                ->toArray(),
        ];
    }

    /** Create a batch of activities. */
    public static function createBatch(array $activities, ?string $batchUuid = null): void
    {
        $batchUuid ??= \Illuminate\Support\Str::uuid()->toString();

        foreach ($activities as $activity) {
            $activity['batch_uuid'] = $batchUuid;
            static::create($activity);
        }
    }

    /** Log a user activity. */
    public static function logActivity(
        string $event,
        string $description,
        $subject = null,
        $causer = null,
        array $properties = [],
        ?string $logName = null
    ): static {
        return static::create([
            'log_name' => $logName,
            'description' => $description,
            'event' => $event,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject?->id,
            'causer_type' => $causer ? get_class($causer) : null,
            'causer_id' => $causer?->id,
            'properties' => $properties,
        ]);
    }
}
