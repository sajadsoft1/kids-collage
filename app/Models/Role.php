<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasTranslationAuto;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string $title
 * @property string $description
 */
class Role extends \Spatie\Permission\Models\Role
{
    use HasFactory;
    use HasTranslationAuto;

    public array $translatable = [
        'description',
    ];

    protected $fillable = [
        'languages', 'name', 'guard_name',
    ];

    protected $casts = [
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
