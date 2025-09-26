<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\EnrollmentStatusEnum;
use App\Traits\HasUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    use HasFactory;
    use HasUser;

    protected $fillable = [
        'user_id',
        'course_id',
        'enroll_date',
        'status',
    ];

    protected $casts = [
        'status'      => EnrollmentStatusEnum::class,
        'enroll_date' => 'date',
    ];

    /**
     * Model Configuration --------------------------------------------------------------------------
     */

    /** Model Relations -------------------------------------------------------------------------- */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function attendances(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Attendance::class);
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
