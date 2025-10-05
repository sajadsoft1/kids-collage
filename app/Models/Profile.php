<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\GenderEnum;
use App\Enums\ReligionEnum;
use App\Traits\HasUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    use HasUser;

    protected $fillable = [
        'user_id',
        'id_number',
        'national_code',
        'birth_date',
        'gender',
        'address',
        'phone',
        'father_name',
        'father_phone',
        'mother_name',
        'mother_phone',
        'religion',
    ];

    protected $casts = [
        'gender'    => GenderEnum::class,
        'religion'  => ReligionEnum::class,
        'id_number' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
