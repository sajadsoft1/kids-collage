<?php

declare(strict_types=1);

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Room Model
 *
 * Physical location for in-person/hybrid courses.
 * Represents classrooms, labs, or other physical spaces where courses can be held.
 *
 * @property int                 $id
 * @property string              $name
 * @property int                 $capacity
 * @property string|null         $location
 * @property array|null          $languages
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Course>        $courses
 * @property-read \Illuminate\Database\Eloquent\Collection<int, CourseSession> $sessions
 */
class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'capacity',
        'location',
    ];

    protected $casts = [
        'capacity' => 'integer',
    ];

    /** Get the courses that use this room. */
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    /** Get the sessions that are held in this room. */
    public function sessions(): HasMany
    {
        return $this->hasMany(CourseSession::class);
    }

    /** Check if this room supports a specific language. */
    public function supportsLanguage(string $language): bool
    {
        return is_array($this->languages) && in_array($language, $this->languages);
    }

    /** Get the primary language for this room. */
    public function getPrimaryLanguage(): ?string
    {
        return is_array($this->languages) && ! empty($this->languages)
            ? $this->languages[0]
            : null;
    }

    /** Check if this room is multilingual. */
    public function isMultilingual(): bool
    {
        return is_array($this->languages) && count($this->languages) > 1;
    }

    /** Get the full location description. */
    public function getFullLocationAttribute(): string
    {
        return $this->location ? "{$this->name}, {$this->location}" : $this->name;
    }

    /** Check if this room has sufficient capacity for a course. */
    public function hasCapacityFor(int $requiredCapacity): bool
    {
        return $this->capacity >= $requiredCapacity;
    }

    /** Get the utilization percentage for this room. */
    public function getUtilizationPercentage(): float
    {
        $activeSessions = $this->sessions()
            ->whereHas('course', function ($query) {
                $query->whereIn('status', ['active', 'scheduled']);
            })
            ->count();

        // Calculate based on weekly sessions (assuming 5 days per week)
        $maxPossibleSessions = 5 * 4; // 5 days * 4 weeks per month

        return min(100.0, ($activeSessions / $maxPossibleSessions) * 100);
    }

    /** Check if this room is available at a specific time. */
    public function isAvailableAt(DateTime $date, DateTime $startTime, DateTime $endTime): bool
    {
        $conflictingSessions = $this->sessions()
            ->whereDate('date', $date)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            })
            ->where('status', '!=', 'cancelled')
            ->exists();

        return ! $conflictingSessions;
    }

    /** Scope for rooms by capacity range. */
    public function scopeByCapacityRange($query, int $minCapacity, int $maxCapacity)
    {
        return $query->whereBetween('capacity', [$minCapacity, $maxCapacity]);
    }

    /** Scope for rooms with minimum capacity. */
    public function scopeWithMinimumCapacity($query, int $capacity)
    {
        return $query->where('capacity', '>=', $capacity);
    }

    /** Scope for rooms that support a specific language. */
    public function scopeSupportingLanguage($query, string $language)
    {
        return $query->whereJsonContains('languages', $language);
    }

    /** Scope for multilingual rooms. */
    public function scopeMultilingual($query)
    {
        return $query->whereRaw('JSON_LENGTH(languages) > 1');
    }

    /** Scope for rooms by location. */
    public function scopeByLocation($query, string $location)
    {
        return $query->where('location', 'like', "%{$location}%");
    }

    /** Get available rooms for a specific time slot. */
    public static function getAvailableRooms(DateTime $date, DateTime $startTime, DateTime $endTime, int $requiredCapacity = 1): \Illuminate\Database\Eloquent\Collection
    {
        return static::where('capacity', '>=', $requiredCapacity)
            ->whereDoesntHave('sessions', function ($query) use ($date, $startTime, $endTime) {
                $query->whereDate('date', $date)
                    ->where(function ($q) use ($startTime, $endTime) {
                        $q->whereBetween('start_time', [$startTime, $endTime])
                            ->orWhereBetween('end_time', [$startTime, $endTime])
                            ->orWhere(function ($subQ) use ($startTime, $endTime) {
                                $subQ->where('start_time', '<=', $startTime)
                                    ->where('end_time', '>=', $endTime);
                            });
                    })
                    ->where('status', '!=', 'cancelled');
            })
            ->get();
    }
}
