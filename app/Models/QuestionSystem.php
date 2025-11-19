<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BooleanEnum;
use App\Traits\HasCategory;
use App\Traits\HasTranslationAuto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $title
 * @property string $description
 */
class QuestionSystem extends Model
{
    use HasCategory;
    use HasFactory;
    use HasTranslationAuto;

    public array $translatable = [
        'title', 'description',
    ];

    protected $fillable = [
        'published',
        'languages',
        'ordering',
        'category_id',
    ];

    protected $casts = [
        'published' => BooleanEnum::class,
        'languages' => 'array',
        'ordering' => 'integer',
    ];

    /**
     * Model Configuration --------------------------------------------------------------------------
     */

    /**
     * Model Relations --------------------------------------------------------------------------
     */

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
