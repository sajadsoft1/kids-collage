<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BooleanEnum;
use App\Traits\HasUser;
use Illuminate\Database\Eloquent\Model;

class LeitnerBox extends Model
{
    use HasUser;

    protected $fillable = [
        'user_id',
        'flash_card_id',
        'box',
        'next_review_at',
        'last_review_at',
        'finished',
    ];
    protected $casts = [
        'next_review_at' => 'datetime',
        'last_review_at' => 'datetime',
        'finished' => BooleanEnum::class,
    ];
}
