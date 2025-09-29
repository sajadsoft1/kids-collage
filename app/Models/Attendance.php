<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Attendance Model
 *
 * Tracks student presence in a session (or progress in self-paced).
 * Records attendance data for both scheduled and self-paced courses.
 *
 * @property int                 $id
 * @property int                 $enrollment_id
 * @property int                 $session_id
 * @property bool                $present
 * @property \Carbon\Carbon|null $arrival_time
 * @property \Carbon\Carbon|null $leave_time
 * @property string|null         $excuse_note
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 *
 * @property-read Enrollment $enrollment
 * @property-read Session $session
 */
class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'enrollment_id',
        'session_id',
        'present',
        'arrival_time',
        'leave_time',
        'excuse_note',
    ];

    protected $casts = [
        'present'      => 'boolean',
        'arrival_time' => 'datetime',
        'leave_time'   => 'datetime',
    ];

    /** Get the enrollment for this attendance. */
    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    /** Get the session for this attendance. */
    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class);
    }

    /** Get the duration of attendance in minutes. */
    public function getDurationAttribute(): ?int
    {
        if ( ! $this->arrival_time || ! $this->leave_time) {
            return null;
        }

        return $this->arrival_time->diffInMinutes($this->leave_time);
    }

    /** Get the formatted duration. */
    public function getFormattedDurationAttribute(): ?string
    {
        $duration = $this->duration;
        
        if ( ! $duration) {
            return null;
        }

        $hours   = floor($duration / 60);
        $minutes = $duration % 60;

        if ($hours > 0) {
            return $minutes > 0 ? "{$hours}h {$minutes}m" : "{$hours}h";
        }

        return "{$minutes}m";
    }

    /** Check if this attendance is late. */
    public function isLate(): bool
    {
        if ( ! $this->present || ! $this->arrival_time || ! $this->session->start_time) {
            return false;
        }

        // Consider late if arrival is more than 5 minutes after session start
        return $this->arrival_time->gt($this->session->start_time->addMinutes(5));
    }

    /** Check if this attendance left early. */
    public function leftEarly(): bool
    {
        if ( ! $this->present || ! $this->leave_time || ! $this->session->end_time) {
            return false;
        }

        // Consider early departure if left more than 10 minutes before session end
        return $this->leave_time->lt($this->session->end_time->subMinutes(10));
    }

    /** Get the lateness in minutes. */
    public function getLatenessMinutesAttribute(): int
    {
        if ( ! $this->isLate()) {
            return 0;
        }

        return $this->arrival_time->diffInMinutes($this->session->start_time);
    }

    /** Get the early departure in minutes. */
    public function getEarlyDepartureMinutesAttribute(): int
    {
        if ( ! $this->leftEarly()) {
            return 0;
        }

        return $this->session->end_time->diffInMinutes($this->leave_time);
    }

    /** Check if this attendance has an excuse. */
    public function hasExcuse(): bool
    {
        return ! empty($this->excuse_note);
    }

    /** Check if this attendance is excused absence. */
    public function isExcusedAbsence(): bool
    {
        return ! $this->present && $this->hasExcuse();
    }

    /** Check if this attendance is unexcused absence. */
    public function isUnexcusedAbsence(): bool
    {
        return ! $this->present && ! $this->hasExcuse();
    }

    /** Get the attendance status as a string. */
    public function getStatusAttribute(): string
    {
        if ($this->present) {
            if ($this->isLate()) {
                return 'Late';
            }
            if ($this->leftEarly()) {
                return 'Left Early';
            }

            return 'Present';
        }

        if ($this->isExcusedAbsence()) {
            return 'Excused Absence';
        }

        return 'Absent';
    }

    /** Mark attendance as present. */
    public function markPresent(?\Carbon\Carbon $arrivalTime = null, ?\Carbon\Carbon $leaveTime = null): bool
    {
        return $this->update([
            'present'      => true,
            'arrival_time' => $arrivalTime ?? now(),
            'leave_time'   => $leaveTime,
            'excuse_note'  => null,
        ]);
    }

    /** Mark attendance as absent with optional excuse. */
    public function markAbsent(?string $excuse = null): bool
    {
        return $this->update([
            'present'      => false,
            'arrival_time' => null,
            'leave_time'   => null,
            'excuse_note'  => $excuse,
        ]);
    }

    /** Record departure time. */
    public function recordDeparture(?\Carbon\Carbon $leaveTime = null): bool
    {
        if ( ! $this->present) {
            return false;
        }

        return $this->update(['leave_time' => $leaveTime ?? now()]);
    }

    /** Add or update excuse note. */
    public function addExcuse(string $excuse): bool
    {
        return $this->update(['excuse_note' => $excuse]);
    }

    /** Get the attendance quality score (0-100). */
    public function getQualityScoreAttribute(): int
    {
        if ( ! $this->present) {
            return $this->hasExcuse() ? 50 : 0; // Excused absence gets partial credit
        }

        $score = 100;

        // Deduct points for being late
        if ($this->isLate()) {
            $score -= min(20, $this->lateness_minutes); // Max 20 point deduction for lateness
        }

        // Deduct points for leaving early
        if ($this->leftEarly()) {
            $score -= min(20, $this->early_departure_minutes); // Max 20 point deduction for early departure
        }

        return max(0, $score);
    }

    /** Scope for present attendances. */
    public function scopePresent($query)
    {
        return $query->where('present', true);
    }

    /** Scope for absent attendances. */
    public function scopeAbsent($query)
    {
        return $query->where('present', false);
    }

    /** Scope for late attendances. */
    public function scopeLate($query)
    {
        return $query->where('present', true)
            ->whereColumn('arrival_time', '>', 'session.start_time');
    }

    /** Scope for attendances with excuses. */
    public function scopeWithExcuse($query)
    {
        return $query->whereNotNull('excuse_note');
    }

    /** Scope for attendances without excuses. */
    public function scopeWithoutExcuse($query)
    {
        return $query->whereNull('excuse_note');
    }

    /** Scope for attendances by enrollment. */
    public function scopeByEnrollment($query, int $enrollmentId)
    {
        return $query->where('enrollment_id', $enrollmentId);
    }

    /** Scope for attendances by session. */
    public function scopeBySession($query, int $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    /** Scope for attendances by date range. */
    public function scopeByDateRange($query, \Carbon\Carbon $startDate, \Carbon\Carbon $endDate)
    {
        return $query->whereHas('session', function ($q) use ($startDate, $endDate) {
            $q->whereBetween('date', [$startDate, $endDate]);
        });
    }

    /** Scope for attendances today. */
    public function scopeToday($query)
    {
        return $query->whereHas('session', function ($q) {
            $q->whereDate('date', today());
        });
    }

    /** Scope for attendances this week. */
    public function scopeThisWeek($query)
    {
        return $query->whereHas('session', function ($q) {
            $q->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()]);
        });
    }
}
