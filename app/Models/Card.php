<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\CardStatusEnum;
use App\Enums\CardTypeEnum;
use App\Enums\PriorityEnum;
use App\Traits\CLogsActivity;
use App\Traits\HasBranch;
use App\Traits\HasBranchScope;
use App\Traits\HasSchemalessAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;

/**
 * Class Card
 *
 * @property int                 $id
 * @property int                 $board_id
 * @property int                 $column_id
 * @property string              $title
 * @property string|null         $description
 * @property CardTypeEnum        $card_type
 * @property PriorityEnum        $priority
 * @property CardStatusEnum      $status
 * @property \Carbon\Carbon|null $due_date
 * @property int                 $order
 * @property array|null          $extra_attributes
 * @property \Carbon\Carbon      $created_at
 * @property \Carbon\Carbon      $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 *
 * @property-read Board $board
 * @property-read Column $column
 * @property-read \Illuminate\Database\Eloquent\Collection|User[] $users
 * @property-read \Illuminate\Database\Eloquent\Collection|CardHistory[] $history
 */
class Card extends Model
{
    use CLogsActivity, HasBranch, HasBranchScope, HasFactory, HasSchemalessAttributes, SoftDeletes;

    protected $fillable = [
        'board_id',
        'column_id',
        'title',
        'description',
        'card_type',
        'priority',
        'status',
        'due_date',
        'order',
        'extra_attributes',
        'branch_id',
    ];

    protected $casts = [
        'card_type' => CardTypeEnum::class,
        'priority' => PriorityEnum::class,
        'status' => CardStatusEnum::class,
        'due_date' => 'date',
        'extra_attributes' => 'array',
    ];

    /** Get the board that owns this card. */
    public function board(): BelongsTo
    {
        return $this->belongsTo(Board::class);
    }

    /** Get the column that contains this card. */
    public function column(): BelongsTo
    {
        return $this->belongsTo(Column::class);
    }

    /** Get the users associated with this card. */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'card_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    /** Get the history for this card. */
    public function history(): HasMany
    {
        return $this->hasMany(CardHistory::class)->orderBy('created_at', 'desc');
    }

    /** Get the activity log options for the model. */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /** Get assignees for this card. */
    public function assignees(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'card_user')
            ->wherePivot('role', 'assignee')
            ->withTimestamps();
    }

    /** Get reviewers for this card. */
    public function reviewers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'card_user')
            ->wherePivot('role', 'reviewer')
            ->withTimestamps();
    }

    /** Get watchers for this card. */
    public function watchers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'card_user')
            ->wherePivot('role', 'watcher')
            ->withTimestamps();
    }

    /** Check if the card is overdue. */
    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date->isPast() && $this->status !== CardStatusEnum::COMPLETED;
    }

    /** Get the days until due date. */
    public function getDaysUntilDue(): ?int
    {
        if ( ! $this->due_date) {
            return null;
        }

        return (int) now()->diffInDays($this->due_date, false);
    }

    /** Get a specific extra attribute. */
    public function getExtraAttribute(string $key, mixed $default = null): mixed
    {
        return $this->extra_attributes[$key] ?? $default;
    }

    /** Set a specific extra attribute. */
    public function setExtraAttribute(string $key, mixed $value): void
    {
        $attributes = $this->extra_attributes ?? [];
        $attributes[$key] = $value;
        $this->extra_attributes = $attributes;
    }
}
