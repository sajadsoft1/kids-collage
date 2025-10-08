<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\CourseLevelEnum;
use App\Enums\CourseTypeEnum;
use App\Helpers\Constants;
use App\Traits\CLogsActivity;
use App\Traits\HasCategory;
use App\Traits\HasComment;
use App\Traits\HasModelCache;
use App\Traits\HasSeoOption;
use App\Traits\HasSlugFromTranslation;
use App\Traits\HasTranslationAuto;
use App\Traits\HasView;
use App\Traits\HasWishList;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;

/**
 * CourseTemplate Model
 *
 * Represents the definition of a course (e.g., English Level 1).
 * This is the template that defines the structure and content of a course,
 * which can be instantiated multiple times in different terms.
 *
 * @property int         $id
 * @property string      $title
 * @property string      $description
 * @property int|null    $category_id
 * @property string|null $level
 * @property array|null  $prerequisites
 * @property bool        $is_self_paced
 * @property array|null  $languages
 * @property array|null  $syllabus
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @property-read Collection<int, CourseSessionTemplate> $sessionTemplates
 * @property-read Collection<int, Course>                $courses
 * @property-read Collection<int, resource>              $resources
 */
class CourseTemplate extends Model implements HasMedia
{
    use CLogsActivity;
    use HasCategory;
    use HasComment;
    use HasFactory;
    use HasModelCache;
    use HasSeoOption;
    use HasSlugFromTranslation;
    use HasTags;
    use HasTranslationAuto;
    use HasView;
    use HasWishList;
    use InteractsWithMedia;
    use SoftDeletes;

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
        'view_count'    => 'integer',
        'comment_count' => 'integer',
        'wish_count'    => 'integer',
        'languages'     => 'array',
        'level'         => CourseLevelEnum::class,
        'type'          => CourseTypeEnum::class,
    ];

    /** Model Configuration -------------------------------------------------------------------------- */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile()
            ->useFallbackUrl('/assets/images/default/user-avatar.png')
            ->registerMediaConversions(function () {
                $this->addMediaConversion(Constants::RESOLUTION_100_SQUARE)->fit(Fit::Crop, 100, 100);
                $this->addMediaConversion(Constants::RESOLUTION_854_480)->fit(Fit::Crop, 854, 480);
                $this->addMediaConversion(Constants::RESOLUTION_1280_720)->fit(Fit::Crop, 1280, 720);
            });
    }

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

    /** Get the amount sessions in this template. */
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

    /** Scope for templates by a category. */
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
