<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SessionStatus;
use App\Enums\SessionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Session Model
 *
 * A scheduled session for a Course (instance of a SessionTemplate).
 * This represents an actual class session that students attend.
 *
 * @property int                 $id
 * @property int                 $course_id
 * @property int                 $session_template_id
 * @property \Carbon\Carbon|null $date
 * @property \Carbon\Carbon|null $start_time
 * @property \Carbon\Carbon|null $end_time
 * @property int|null            $room_id
 * @property string|null         $meeting_link
 * @property string|null         $recording_link
 * @property SessionStatus       $status
 * @property SessionType         $session_type
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 *
 * @property-read Course                                                              $course
 * @property-read CourseSessionTemplate                                               $sessionTemplate
 * @property-read Room|null                                                           $room
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Attendance>           $attendances
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Resource> $resources
 */
class CourseSession extends Model
{
    use HasFactory, SoftDeletes;

    /** Use the new physical table name for course sessions. */
    protected $table = 'course_sessions';

    protected $fillable = [
        'course_id',
        'course_session_template_id',
        'date',
        'start_time',
        'end_time',
        'room_id',
        'meeting_link',
        'recording_link',
        'status',
        'session_type',
    ];

    protected $casts = [
        'course_id' => 'integer',
        'course_session_template_id' => 'integer',
        'date' => 'date',
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s',
        'room_id' => 'integer',
        'status' => SessionStatus::class,
        'session_type' => SessionType::class,
    ];

    /** Get the course that this session belongs to. */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /** Get the session template that this session is based on. */
    public function sessionTemplate(): BelongsTo
    {
        return $this->belongsTo(CourseSessionTemplate::class);
    }

    /** Get the room for this session. */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /** Get the attendances for this session. */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /** Get the run-specific override resources for this session. */
    public function resources(): MorphMany
    {
        return $this->morphMany(Resource::class, 'resourceable');
    }

    /** Get present attendances. */
    public function presentAttendances(): HasMany
    {
        return $this->attendances()->where('present', true);
    }

    /** Get absent attendances. */
    public function absentAttendances(): HasMany
    {
        return $this->attendances()->where('present', false);
    }

    /** Get the attendance count. */
    public function getAttendanceCountAttribute(): int
    {
        return $this->attendances()->count();
    }

    /** Get the present count. */
    public function getPresentCountAttribute(): int
    {
        return $this->presentAttendances()->count();
    }

    /** Get the absent count. */
    public function getAbsentCountAttribute(): int
    {
        return $this->absentAttendances()->count();
    }

    /** Get the attendance percentage. */
    public function getAttendancePercentageAttribute(): float
    {
        if ($this->attendance_count === 0) {
            return 0.0;
        }

        return ($this->present_count / $this->attendance_count) * 100;
    }

    /** Check if this session is completed. */
    public function isCompleted(): bool
    {
        return $this->status->isCompleted();
    }

    /** Check if this session is cancelled. */
    public function isCancelled(): bool
    {
        return $this->status === SessionStatus::CANCELLED;
    }

    /** Check if this session is planned. */
    public function isPlanned(): bool
    {
        return $this->status === SessionStatus::PLANNED;
    }

    /** Check if this session is in the past. */
    public function isPast(): bool
    {
        if ( ! $this->date) {
            return false; // Self-paced sessions don't have dates
        }

        return now()->gt($this->date);
    }

    /** Check if this session is today. */
    public function isToday(): bool
    {
        if ( ! $this->date) {
            return false;
        }

        return now()->isSameDay($this->date);
    }

    /** Check if this session is in the future. */
    public function isFuture(): bool
    {
        if ( ! $this->date) {
            return true; // Self-paced sessions are always "future"
        }

        return now()->lt($this->date);
    }

    /** Get the duration of this session in minutes. */
    public function getDurationAttribute(): int
    {
        if ( ! $this->start_time || ! $this->end_time) {
            return $this->sessionTemplate->duration_minutes ?? 0;
        }

        return (int) $this->start_time->diffInMinutes($this->end_time);
    }

    /** Get the formatted duration. */
    public function getFormattedDurationAttribute(): string
    {
        $duration = $this->duration;
        $hours = floor($duration / 60);
        $minutes = $duration % 60;

        if ($hours > 0) {
            return $minutes > 0 ? "{$hours}h {$minutes}m" : "{$hours}h";
        }

        return "{$minutes}m";
    }

    /** Get the full session title. */
    public function getFullTitleAttribute(): string
    {
        $templateTitle = $this->sessionTemplate->title;

        if ($this->date) {
            return "{$templateTitle} - {$this->date->format('M j, Y')}";
        }

        return $templateTitle;
    }

    /** Get the session location. */
    public function getLocationAttribute(): string
    {
        if ($this->meeting_link) {
            return 'Online Meeting';
        }

        if ($this->room) {
            return $this->room->full_location;
        }

        return 'TBD';
    }

    /** Mark this session as completed. */
    public function markAsCompleted(): bool
    {
        return $this->update(['status' => SessionStatus::DONE]);
    }

    /** Cancel this session. */
    public function cancel(?string $reason = null): bool
    {
        return $this->update([
            'status' => SessionStatus::CANCELLED,
            'recording_link' => null, // Clear recording link if cancelled
        ]);
    }

    /** Reschedule this session to a new date and time. */
    public function reschedule(\Carbon\Carbon $newDate, ?\Carbon\Carbon $newStartTime = null, ?\Carbon\Carbon $newEndTime = null): bool
    {
        return $this->update([
            'date' => $newDate,
            'start_time' => $newStartTime ?? $this->start_time,
            'end_time' => $newEndTime ?? $this->end_time,
        ]);
    }

    /** Check if this session conflicts with another session. */
    public function conflictsWith(CourseSession $other): bool
    {
        // Can't conflict if no date/time
        if ( ! $this->date || ! $other->date) {
            return false;
        }

        // Must be on the same date
        if ( ! $this->date->isSameDay($other->date)) {
            return false;
        }

        // Check time overlap
        if ($this->start_time && $this->end_time && $other->start_time && $other->end_time) {
            return $this->start_time->lt($other->end_time) && $this->end_time->gt($other->start_time);
        }

        return false;
    }

    /** Scope for completed sessions. */
    public function scopeCompleted($query)
    {
        return $query->where('status', SessionStatus::DONE);
    }

    /** Scope for planned sessions. */
    public function scopePlanned($query)
    {
        return $query->where('status', SessionStatus::PLANNED);
    }

    /** Scope for cancelled sessions. */
    public function scopeCancelled($query)
    {
        return $query->where('status', SessionStatus::CANCELLED);
    }

    /** Scope for sessions by date range. */
    public function scopeByDateRange($query, \Carbon\Carbon $startDate, \Carbon\Carbon $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /** Scope for sessions today. */
    public function scopeToday($query)
    {
        return $query->whereDate('date', today());
    }

    /** Scope for sessions this week. */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    /** Scope for sessions by type. */
    public function scopeByType($query, SessionType $type)
    {
        return $query->where('session_type', $type);
    }

    /** Scope for online sessions. */
    public function scopeOnline($query)
    {
        return $query->where('session_type', SessionType::ONLINE);
    }

    /** Scope for in-person sessions. */
    public function scopeInPerson($query)
    {
        return $query->where('session_type', SessionType::IN_PERSON);
    }

    /** Scope for hybrid sessions. */
    public function scopeHybrid($query)
    {
        return $query->where('session_type', SessionType::HYBRID);
    }

    /** Scope for sessions by room. */
    public function scopeByRoom($query, int $roomId)
    {
        return $query->where('room_id', $roomId);
    }

    /** Scope for sessions with recordings. */
    public function scopeWithRecordings($query)
    {
        return $query->whereNotNull('recording_link');
    }
}
