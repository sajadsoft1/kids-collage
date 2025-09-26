<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\CourseTypeEnum;
use App\Traits\HasCategory;
use App\Traits\HasTranslationAuto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property string $title
 * @property string $description
 * @property string $body
 */
class Course extends Model
{
    use HasCategory;
    use HasFactory;
    use HasTranslationAuto;

    public array $translatable = [
        'title', 'description', 'body',
    ];

    protected $fillable = [
        'teacher_id',
        'category_id',
        'price',
        'type',
        'start_date',
        'end_date',
        'languages',
    ];

    protected $casts = [
        'type'       => CourseTypeEnum::class,
        'price'      => 'float',
        'start_date' => 'date',
        'end_date'   => 'date',
        'languages'  => 'array',
    ];

    /**
     * Model Configuration --------------------------------------------------------------------------
     */

    /** Model Relations -------------------------------------------------------------------------- */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class, 'course_id');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'course_id');
    }

    public function orderItems(): MorphMany
    {
        return $this->morphMany(OrderItem::class, 'itemable');
    }
    /**
     * Model Scope --------------------------------------------------------------------------
     */

    /**
     * Model Attributes --------------------------------------------------------------------------
     */

    /**
     * Model Custom Methods --------------------------------------------------------------------------
     */
}
