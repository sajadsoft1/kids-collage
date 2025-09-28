<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUser;
use App\Traits\MorphAttributesTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserView extends Model
{
    use HasUser, MorphAttributesTrait;

    public const UPDATED_AT = null;
    protected $table = 'user_views';

    protected $fillable = [
        'user_id',
        'morphable_id',
        'morphable_type',
        'ip',
        'collection',
        'visitor',
    ];
}
