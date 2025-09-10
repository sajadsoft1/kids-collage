<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasSchemalessAttributes;
use App\Traits\HasUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Profile extends Model
{
    use HasFactory, HasSchemalessAttributes, HasUser, LogsActivity;

    protected $table = 'profiles';

    protected $fillable = [
        'user_id',
        'bio',
        'birth_date',
        'register_at',
        'extra_attributes',
    ];

    protected $casts = [
        'birth_date'  => 'date',
        'register_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['user_id', 'bio', 'birth_date', 'national_code'])
            ->useLogName('system')
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
