<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BooleanEnum;
use App\Traits\HasTranslationAuto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $title
 * @property string $description
 */
class FlashCard extends Model
{
    use HasFactory;
    use HasTranslationAuto;

    public array $translatable = [
        'title',
    ];

    protected $fillable = [
        'favorite',
        'languages',
        'front',
        'back',
    ];

    protected $casts = [
        'favorite' => BooleanEnum::class,
        'languages' => 'array',
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
