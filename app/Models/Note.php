<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    use HasUser;

    protected $fillable = [
        'user_id',
        'question_id',
        'body',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
