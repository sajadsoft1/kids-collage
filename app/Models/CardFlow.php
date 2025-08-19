<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\CLogsActivity;
use App\Traits\HasSchemalessAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;

/**
 * Class CardFlow
 *
 * @property int                 $id
 * @property int                 $board_id
 * @property int                 $from_column_id
 * @property int                 $to_column_id
 * @property string              $name
 * @property string|null         $description
 * @property bool                $is_active
 * @property array|null          $condition_json
 * @property \Carbon\Carbon      $created_at
 * @property \Carbon\Carbon      $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 *
 * @property-read Board $board
 * @property-read Column $fromColumn
 * @property-read Column $toColumn
 */
class CardFlow extends Model
{
    use CLogsActivity, HasFactory, HasSchemalessAttributes,SoftDeletes;

    protected $fillable = [
        'board_id',
        'from_column_id',
        'to_column_id',
        'name',
        'description',
        'is_active',
        'extra_attributes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /** Get the board that owns this flow. */
    public function board(): BelongsTo
    {
        return $this->belongsTo(Board::class);
    }

    /** Get the source column for this flow. */
    public function fromColumn(): BelongsTo
    {
        return $this->belongsTo(Column::class, 'from_column_id');
    }

    /** Get the destination column for this flow. */
    public function toColumn(): BelongsTo
    {
        return $this->belongsTo(Column::class, 'to_column_id');
    }

    /** Get the activity log options for the model. */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /** Check if the flow conditions are met for a given card. */
    public function checkConditions(Card $card): bool
    {
        if ( ! $this->condition_json || empty($this->condition_json)) {
            return true; // No conditions means always allowed
        }

        $conditions = $this->condition_json;

        // Check each condition
        foreach ($conditions as $condition) {
            if ( ! $this->evaluateCondition($condition, $card)) {
                return false;
            }
        }

        return true;
    }

    /** Evaluate a single condition against a card. */
    private function evaluateCondition(array $condition, Card $card): bool
    {
        $field    = $condition['field'] ?? '';
        $operator = $condition['operator'] ?? '';
        $value    = $condition['value'] ?? '';

        return match ($field) {
            'priority'         => $this->compareValues($card->priority->value, $operator, $value),
            'card_type'        => $this->compareValues($card->card_type->value, $operator, $value),
            'status'           => $this->compareValues($card->status->value, $operator, $value),
            'due_date'         => $this->compareDates($card->due_date, $operator, $value),
            'assignees_count'  => $this->compareValues($card->assignees()->count(), $operator, $value),
            'extra_attributes' => $this->compareExtraAttributes($card, $condition),
            default            => true,
        };
    }

    /** Compare two values based on the operator. */
    private function compareValues(mixed $cardValue, string $operator, mixed $conditionValue): bool
    {
        return match ($operator) {
            'equals'                => $cardValue == $conditionValue,
            'not_equals'            => $cardValue != $conditionValue,
            'contains'              => str_contains((string) $cardValue, (string) $conditionValue),
            'greater_than'          => $cardValue > $conditionValue,
            'less_than'             => $cardValue < $conditionValue,
            'greater_than_or_equal' => $cardValue >= $conditionValue,
            'less_than_or_equal'    => $cardValue <= $conditionValue,
            default                 => true,
        };
    }

    /** Compare dates based on the operator. */
    private function compareDates(?string $cardDate, string $operator, string $conditionValue): bool
    {
        if ( ! $cardDate) {
            return $operator === 'is_null';
        }

        $cardDateTime      = \Carbon\Carbon::parse($cardDate);
        $conditionDateTime = \Carbon\Carbon::parse($conditionValue);

        return match ($operator) {
            'before'       => $cardDateTime->lt($conditionDateTime),
            'after'        => $cardDateTime->gt($conditionDateTime),
            'on'           => $cardDateTime->isSameDay($conditionDateTime),
            'before_or_on' => $cardDateTime->lte($conditionDateTime),
            'after_or_on'  => $cardDateTime->gte($conditionDateTime),
            default        => true,
        };
    }

    /** Compare extra attributes. */
    private function compareExtraAttributes(Card $card, array $condition): bool
    {
        $key      = $condition['key'] ?? '';
        $operator = $condition['operator'] ?? '';
        $value    = $condition['value'] ?? '';

        $cardValue = $card->getExtraAttribute($key);

        return $this->compareValues($cardValue, $operator, $value);
    }
}
