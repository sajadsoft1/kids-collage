<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\GenderEnum;
use App\Enums\ReligionEnum;
use App\Traits\HasUser;
use Illuminate\Database\Eloquent\Model;

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
        'salary',
        'benefit',
        'cooperation_start_date',
        'cooperation_end_date',
    ];

    protected $casts = [
        'gender'                 => GenderEnum::class,
        'religion'               => ReligionEnum::class,
        'id_number'              => 'integer',
        'birth_date'             => 'date',
        'cooperation_start_date' => 'date',
        'cooperation_end_date'   => 'date',
    ];

}
