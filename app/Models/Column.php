<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\CLogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;

/**
 * Class Column
 *
 * @property int                 $id
 * @property int                 $board_id
 * @property string              $name
 * @property string|null         $description
 * @property string              $color
 * @property int                 $order
 * @property int|null            $wip_limit
 * @property bool                $is_active
 * @property \Carbon\Carbon      $created_at
 * @property \Carbon\Carbon      $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 *
 * @property-read Board $board
 * @property-read \Illuminate\Database\Eloquent\Collection|Card[] $cards
 * @property-read \Illuminate\Database\Eloquent\Collection|CardFlow[] $fromFlows
 * @property-read \Illuminate\Database\Eloquent\Collection|CardFlow[] $toFlows
 */
class Column extends Model
{
    use CLogsActivity, HasFactory, SoftDeletes;

    protected $fillable = [
        'board_id',
        'name',
        'description',
        'color',
        'order',
        'wip_limit',
        'is_active',
    ];

    protected $casts = [
        'wip_limit' => 'integer',
        'is_active' => 'boolean',
    ];

    /** Get the board that owns this column. */
    public function board(): BelongsTo
    {
        return $this->belongsTo(Board::class);
    }

    /** Get the cards for this column. */
    public function cards(): HasMany
    {
        return $this->hasMany(Card::class)->orderBy('order');
    }

    /** Get the flows that start from this column. */
    public function fromFlows(): HasMany
    {
        return $this->hasMany(CardFlow::class, 'from_column_id');
    }

    /** Get the flows that end at this column. */
    public function toFlows(): HasMany
    {
        return $this->hasMany(CardFlow::class, 'to_column_id');
    }

    /** Get the activity log options for the model. */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /** Check if the column has reached its WIP limit. */
    public function hasReachedWipLimit(): bool
    {
        if ( ! $this->wip_limit) {
            return false;
        }

        return $this->cards()->count() >= $this->wip_limit;
    }

    /** Get the remaining WIP capacity. */
    public function getRemainingWipCapacity(): int
    {
        if ( ! $this->wip_limit) {
            return PHP_INT_MAX;
        }

        return max(0, $this->wip_limit - $this->cards()->count());
    }
}
