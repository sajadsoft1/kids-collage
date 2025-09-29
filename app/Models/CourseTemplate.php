<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasTranslationAuto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * CourseTemplate Model
 *
 * Represents the definition of a course (e.g. English Level 1).
 * This is the template that defines the structure and content of a course,
 * which can be instantiated multiple times in different terms.
 *
 * @property int                 $id
 * @property string              $title
 * @property string              $description
 * @property int|null                                                                  $category_id
 * @property string|null                                                               $level
 * @property array|null                                                                $prerequisites
 * @property bool                                                                      $is_self_paced
 * @property array|null                                                                $languages
 * @property array|null                                                                $syllabus
 * @property \Carbon\Carbon|null                                                       $created_at
 * @property \Carbon\Carbon|null                                                       $updated_at
 * @property \Carbon\Carbon|null                                                       $deleted_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, CourseSessionTemplate> $sessionTemplates
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Course>                $courses
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Resource>  $resources
 */
class CourseTemplate extends Model
{
    use HasFactory, HasTranslationAuto, SoftDeletes;

    public array $translatable = [
        'title',
        'description',
        'body',
    ];

    protected $fillable = [
        'slug',
        'category_id',
        'level',
        'prerequisites',
        'is_self_paced',
        'type',
        'view_count',
        'comment_count',
        'wish_count',
        'languages',
    ];

    protected $casts = [
        'prerequisites' => 'array',
        'is_self_paced' => 'boolean',
        'view_count' => 'integer',
        'comment_count' => 'integer',
        'wish_count' => 'integer',
        'languages' => 'array',
    ];

    /** Get the session templates for this course template. */
    public function sessionTemplates(): HasMany
    {
        return $this->hasMany(CourseSessionTemplate::class)->orderBy('order');
    }

    /** Get the courses that are instances of this template. */
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    /** Get the general resources for this course template. */
    public function resources(): MorphMany
    {
        return $this->morphMany(Resource::class, 'resourceable');
    }

    /** Get active courses for this template. */
    public function activeCourses(): HasMany
    {
        return $this->courses()->where('status', 'active');
    }

    /** Get the total duration of all sessions in minutes. */
    public function getTotalDurationAttribute(): int
    {
        return $this->sessionTemplates()->sum('duration_minutes');
    }

    /** Get the number of sessions in this template. */
    public function getSessionCountAttribute(): int
    {
        return $this->sessionTemplates()->count();
    }

    /** Check if this template has prerequisites. */
    public function hasPrerequisites(): bool
    {
        return ! empty($this->prerequisites);
    }

    /** Check if this template supports multiple languages. */
    public function isMultilingual(): bool
    {
        return is_array($this->languages) && count($this->languages) > 1;
    }

    /** Get the primary language for this template. */
    public function getPrimaryLanguage(): ?string
    {
        return is_array($this->languages) && ! empty($this->languages)
            ? $this->languages[0]
            : null;
    }

    /** Scope for self-paced course templates. */
    public function scopeSelfPaced($query)
    {
        return $query->where('is_self_paced', true);
    }

    /** Scope for instructor-led course templates. */
    public function scopeInstructorLed($query)
    {
        return $query->where('is_self_paced', false);
    }

    /** Scope for templates by category. */
    public function scopeByCategory($query, int $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /** Scope for templates by level. */
    public function scopeByLevel($query, string $level)
    {
        return $query->where('level', $level);
    }
}
