<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TermStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Term Model
 *
 * Represents an academic period (Fall 2025, Spring 2026, or long-term for self-paced).
 * Terms define the time boundaries for course runs and help organize
 * academic scheduling.
 *
 * @property int                 $id
 * @property string              $title
 * @property \Carbon\Carbon      $start_date
 * @property \Carbon\Carbon      $end_date
 * @property TermStatus          $status
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Course> $courses
 */
class Term extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'status'     => TermStatus::class,
    ];

    /** Get the courses for this term. */
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    /** Get active courses for this term. */
    public function activeCourses(): HasMany
    {
        return $this->courses()->where('status', 'active');
    }

    /** Get the duration of this term in days. */
    public function getDurationDaysAttribute(): int
    {
        return $this->start_date->diffInDays($this->end_date);
    }

    /** Get the duration of this term in weeks. */
    public function getDurationWeeksAttribute(): int
    {
        return $this->start_date->diffInWeeks($this->end_date);
    }

    /** Check if this term is currently active. */
    public function isActive(): bool
    {
        $now = now();

        return $this->status->isActive() &&
               $now->between($this->start_date, $this->end_date);
    }

    /** Check if this term has started. */
    public function hasStarted(): bool
    {
        return now()->gte($this->start_date);
    }

    /** Check if this term has ended. */
    public function hasEnded(): bool
    {
        return now()->gt($this->end_date);
    }

    /** Check if this term is in the future. */
    public function isFuture(): bool
    {
        return now()->lt($this->start_date);
    }

    /** Get the number of days remaining in this term. */
    public function getDaysRemainingAttribute(): int
    {
        if ($this->hasEnded()) {
            return 0;
        }

        return now()->diffInDays($this->end_date, false);
    }

    /** Get the progress percentage of this term. */
    public function getProgressPercentageAttribute(): float
    {
        if ( ! $this->hasStarted()) {
            return 0.0;
        }

        if ($this->hasEnded()) {
            return 100.0;
        }

        $totalDays   = $this->duration_days;
        $elapsedDays = now()->diffInDays($this->start_date);

        return min(100.0, ($elapsedDays / $totalDays) * 100);
    }

    /** Check if this is a long-term (suitable for self-paced courses). */
    public function isLongTerm(): bool
    {
        return $this->duration_days > 365; // More than a year
    }

    /** Get the academic year for this term. */
    public function getAcademicYearAttribute(): string
    {
        $startYear = $this->start_date->year;
        $endYear   = $this->end_date->year;

        if ($startYear === $endYear) {
            return (string) $startYear;
        }

        return "{$startYear}-{$endYear}";
    }

    /** Scope for active terms. */
    public function scopeActive($query)
    {
        return $query->where('status', TermStatus::ACTIVE);
    }

    /** Scope for current terms (currently running). */
    public function scopeCurrent($query)
    {
        $now = now();

        return $query->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->where('status', TermStatus::ACTIVE);
    }

    /** Scope for future terms. */
    public function scopeFuture($query)
    {
        return $query->where('start_date', '>', now());
    }

    /** Scope for past terms. */
    public function scopePast($query)
    {
        return $query->where('end_date', '<', now());
    }

    /** Scope for terms by academic year. */
    public function scopeByAcademicYear($query, string $academicYear)
    {
        return $query->whereYear('start_date', substr($academicYear, 0, 4))
            ->orWhereYear('end_date', substr($academicYear, -4));
    }

    /** Scope for long-term periods. */
    public function scopeLongTerm($query)
    {
        return $query->whereRaw('DATEDIFF(end_date, start_date) > 365');
    }

    /** Get the next available term. */
    public static function getNextAvailableTerm(): ?Term
    {
        return static::where('start_date', '>', now())
            ->where('status', TermStatus::ACTIVE)
            ->orderBy('start_date')
            ->first();
    }

    /** Get the current term. */
    public static function getCurrentTerm(): ?Term
    {
        return static::current()->first();
    }
}
