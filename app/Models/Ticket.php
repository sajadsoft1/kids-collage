<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TicketDepartmentEnum;
use App\Enums\TicketPriorityEnum;
use App\Enums\TicketStatusEnum;
use App\Helpers\Constants;
use App\Traits\CLogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Image\Enums\Fit;

class Ticket extends Model
{
    use CLogsActivity, HasFactory;

    protected $fillable = [
        'subject',
        'department',
        'user_id',  // Who created the ticket?
        'closed_by',
        'status',
        'key',
        'priority',
    ];

    protected $casts = [
        'status' => TicketStatusEnum::class,
        'department' => TicketDepartmentEnum::class,
        'priority' => TicketPriorityEnum::class,
    ];

    public static function boot(): void
    {
        parent::boot();
        static::creating(function (Ticket $ticket) {
            $ticket->key = 'TICKET-' . floor(now()->microsecond);
        });
    }

    /** Model Configuration -------------------------------------------------------------------------- */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile()
            ->registerMediaConversions(
                function () {
                    $this->addMediaConversion(Constants::RESOLUTION_720_SQUARE)
                        ->fit(Fit::Crop, 720, 720);
                }
            );
    }

    /** Model Relations -------------------------------------------------------------------------- */
    public function messages(): HasMany
    {
        return $this->hasMany(TicketMessage::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function closeBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    /**
     * Model Scope --------------------------------------------------------------------------
     */

    /** Model Attributes -------------------------------------------------------------------------- */
    public function getUnreadMessagesCountAttribute(): int
    {
        return $this->messages()->whereNull('read_by')->where('user_id', '!=', auth()->id())->count();
    }

    /**
     * Model Custom Methods --------------------------------------------------------------------------
     */
}
