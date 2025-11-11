<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\CLogsActivity;
use App\Traits\HasSchemalessAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;

/**
 * Class CardHistory
 *
 * @property int            $id
 * @property int            $card_id
 * @property int            $user_id
 * @property int            $column_id
 * @property string         $action
 * @property string|null    $description
 * @property array|null     $extra_attributes
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read Card $card
 * @property-read User $user
 * @property-read Column $column
 */
class CardHistory extends Model
{
    use CLogsActivity, HasFactory,HasSchemalessAttributes;

    protected $table = 'card_history';

    protected $fillable = [
        'card_id',
        'user_id',
        'column_id',
        'action',
        'description',
        'extra_attributes',
    ];

    protected $casts = [
        'extra_attributes' => 'array',
    ];

    /** Get the card that owns this history record. */
    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }

    /** Get the user who performed this action. */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Get the column where this action occurred. */
    public function column(): BelongsTo
    {
        return $this->belongsTo(Column::class);
    }

    /** Get the activity log options for the model. */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
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
