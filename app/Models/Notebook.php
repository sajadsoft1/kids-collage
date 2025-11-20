<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $title
 * @property string $description
 */
class Notebook extends Model
{
    use HasFactory;
    use HasUser;

    protected $fillable = [
        'user_id',
        'title',
        'body',
        'tags',
        'languages',
    ];

    protected $casts = [
        'languages' => 'array',
        'tags' => 'array',
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
