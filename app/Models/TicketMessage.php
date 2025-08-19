<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\CLogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class TicketMessage extends Model implements HasMedia
{
    use CLogsActivity, InteractsWithMedia;

    protected $fillable = [
        'ticket_id',
        'user_id',  // Who participated in the conversation?
        'message',
        'read_by',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

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
        $this->addMediaCollection('gallery');
    }

    /** Model Relations -------------------------------------------------------------------------- */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function readBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'read_by');
    }

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
