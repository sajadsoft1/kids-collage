<?php

namespace App\Models;

use App\Traits\HasUser;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use  HasUser;

    protected $fillable = [
        'user_id',
        'national_code',
        'birth_date',
    ];

}
