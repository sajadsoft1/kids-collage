<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BooleanEnum;
use App\Enums\SmsSendStatusEnum;
use App\Traits\HasTranslationAuto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $title
 * @property string $description
 */
class Sms extends Model
{
    use HasFactory;
    use HasTranslationAuto;

    public array $translatable = [
        'title', 'description',
    ];

    protected $fillable = [
        'published',
        'languages',
        'driver',
        'template',
        'inputs',
        'phone',
        'message',
        'provider_message_id',
        'notification_template_id',
        'status',
        'error',
    ];

    protected $casts = [
        'published' => BooleanEnum::class,
        'languages' => 'array',
        'inputs' => 'array',
        'status' => SmsSendStatusEnum::class,
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
