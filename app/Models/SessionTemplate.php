<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasTranslationAuto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * SessionTemplate Model
 *
 * Represents a lesson inside a course template (e.g. Lesson 1: Alphabet).
 * This defines the structure and content of individual sessions that will
 * be instantiated for specific course runs.
 *
 * @property int                 $id
 * @property int                 $course_template_id
 * @property int                 $order
 * @property string              $title
 * @property string              $description
 * @property string|null         $description
 * @property int                 $duration_minutes
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 *
 * @property-read CourseTemplate $courseTemplate
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Session> $sessions
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Resource> $resources
 */
class SessionTemplate extends Model
{
    use HasFactory, HasTranslationAuto, SoftDeletes;

    public array $translatable = [
        'title',
        'description',
    ];

    protected $fillable = [
        'course_template_id',
        'order',
        'duration_minutes',
    ];

    protected $casts = [
        'duration_minutes' => 'integer',
        'languages'        => 'array',
    ];

    /** Get the course template that owns this session template. */
    public function courseTemplate(): BelongsTo
    {
        return $this->belongsTo(CourseTemplate::class);
    }

    /** Get the sessions that are instances of this template. */
    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class);
    }

    /** Get the fixed lesson resources for this session template. */
    public function resources(): MorphMany
    {
        return $this->morphMany(Resource::class, 'resourceable');
    }

    /** Get the duration in hours. */
    public function getDurationHoursAttribute(): float
    {
        return round($this->duration_minutes / 60, 2);
    }

    /** Get formatted duration string. */
    public function getFormattedDurationAttribute(): string
    {
        $hours   = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;

        if ($hours > 0) {
            return $minutes > 0
                ? "{$hours}h {$minutes}m"
                : "{$hours}h";
        }

        return "{$minutes}m";
    }

    /** Check if this is the first session in the course. */
    public function isFirstSession(): bool
    {
        return $this->order === 1;
    }

    /** Get the previous session template in the course. */
    public function previousSession(): ?SessionTemplate
    {
        return $this->courseTemplate
            ->sessionTemplates()
            ->where('order', '<', $this->order)
            ->orderBy('order', 'desc')
            ->first();
    }

    /** Get the next session template in the course. */
    public function nextSession(): ?SessionTemplate
    {
        return $this->courseTemplate
            ->sessionTemplates()
            ->where('order', '>', $this->order)
            ->orderBy('order')
            ->first();
    }

    /** Scope for sessions by order. */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /** Scope for sessions within a duration range. */
    public function scopeByDurationRange($query, int $minMinutes, int $maxMinutes)
    {
        return $query->whereBetween('duration_minutes', [$minMinutes, $maxMinutes]);
    }

    /** Scope for sessions longer than specified duration. */
    public function scopeLongerThan($query, int $minutes)
    {
        return $query->where('duration_minutes', '>', $minutes);
    }
}
