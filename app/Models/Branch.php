<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BranchStatusEnum;
use App\Traits\HasTranslationAuto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Branch Model
 *
 * Represents a branch/location in the multi-branch system.
 * Each branch can have its own data separated from other branches.
 *
 * @property int                             $id
 * @property string                          $name
 * @property string                          $code
 * @property BranchStatusEnum                $status
 * @property bool                            $is_default
 * @property array|null                      $settings
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Branch extends Model
{
    use HasFactory, HasTranslationAuto;

    public array $translatable = ['name'];

    protected $fillable = [
        'name',
        'code',
        'status',
        'is_default',
        'settings',
        'languages',
    ];

    protected $casts = [
        'status' => BranchStatusEnum::class,
        'is_default' => 'boolean',
        'settings' => 'array',
        'languages' => 'array',
    ];

    /** Model Relations -------------------------------------------------------------------------- */

    /** Get all users that have access to this branch. */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'branch_user')
            ->withPivot('role', 'is_default')
            ->withTimestamps();
    }

    /** Get users that have this branch as their default branch. */
    public function defaultUsers(): HasMany
    {
        return $this->hasMany(User::class, 'branch_id');
    }

    /** Model Scopes -------------------------------------------------------------------------- */

    /** Scope a query to only include active branches. */
    public function scopeActive($query)
    {
        return $query->where('status', BranchStatusEnum::ACTIVE);
    }

    /** Scope a query to only include the default branch. */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /** Model Custom Methods -------------------------------------------------------------------------- */

    /** Get the default branch. */
    public static function getDefault(): ?self
    {
        return static::where('is_default', true)->first();
    }

    /** Check if this branch is active. */
    public function isActive(): bool
    {
        return $this->status === BranchStatusEnum::ACTIVE;
    }
}
