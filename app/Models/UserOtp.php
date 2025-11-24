<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUser;
use Illuminate\Database\Eloquent\Model;

class UserOtp extends Model
{
    use HasUser;
    protected $fillable = [
        'user_id',
        'otp',
        'ip_address',
        'try_count',
        'used_at',
    ];
}
