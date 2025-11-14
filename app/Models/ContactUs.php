<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\YesNoEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $title
 * @property string $description
 */
class ContactUs extends Model
{
    use HasFactory;

    protected $table = 'contactuses';
    protected $fillable = [
        'name',
        'subject',
        'email',
        'mobile',
        'comment',
        'admin_note',
        'follow_up',
    ];
    protected $casts = [
        'follow_up' => YesNoEnum::class,
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
