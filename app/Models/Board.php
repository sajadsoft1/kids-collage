<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\CLogsActivity;
use App\Traits\HasSchemalessAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;

/**
 * Class Board
 *
 * @property int                 $id
 * @property string              $name
 * @property string|null         $description
 * @property string              $color
 * @property bool                $is_active
 * @property \Carbon\Carbon      $created_at
 * @property \Carbon\Carbon      $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|User[] $users
 * @property-read \Illuminate\Database\Eloquent\Collection|Column[] $columns
 * @property-read \Illuminate\Database\Eloquent\Collection|Card[] $cards
 * @property-read \Illuminate\Database\Eloquent\Collection|CardFlow[] $cardFlows
 */
class Board extends Model
{
    use CLogsActivity, HasFactory, HasSchemalessAttributes,SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'color',
        'is_active',
        'system_protected',
        'extra_attributes',
    ];

    protected $casts = [
        'is_active'        => 'boolean',
        'system_protected' => 'boolean',
    ];

    /** Get the users that belong to this board. */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'board_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    /** Get the columns for this board. */
    public function columns(): HasMany
    {
        return $this->hasMany(Column::class)->orderBy('order');
    }

    /** Get the cards for this board. */
    public function cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }

    /** Get the card flows for this board. */
    public function cardFlows(): HasMany
    {
        return $this->hasMany(CardFlow::class);
    }

    /** Get the activity log options for the model. */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /** Check if a user has access to this board. */
    public function hasUserAccess(User $user, ?string $role = null): bool
    {
        $query = $this->users()->where('user_id', $user->id);

        if ($role) {
            $query->where('role', $role);
        }

        return $query->exists();
    }

    /** Get the user's role on this board. */
    public function getUserRole(User $user): ?string
    {
        $pivot = $this->users()->where('user_id', $user->id)->first()?->pivot;

        return $pivot?->role;
    }
}
