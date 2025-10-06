<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BooleanEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'published',
        'languages',
        'name',
        'channel',
        'message_template',
        'inputs',
    ];

    protected $casts = [
        'published' => BooleanEnum::class,
        'languages' => 'array',
        'inputs'    => 'array',
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
