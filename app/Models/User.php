<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\BooleanEnum;
use App\Enums\GenderEnum;
use App\Helpers\Constants;
use App\Traits\CLogsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens,CLogsActivity, HasFactory, HasRoles, InteractsWithMedia, Notifiable;

    protected $fillable = [
        'name',
        'family',
        'email',
        'mobile',
        'gender',
        'password',
        'status',
    ];

    protected $hidden = [
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at'  => 'datetime',
        'mobile_verified_at' => 'datetime',
        'gender'             => GenderEnum::class,
        'status'             => BooleanEnum::class,
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->singleFile()
            ->useFallbackUrl('/assets/images/default/user-avatar.png')
            ->registerMediaConversions(function () {
                $this->addMediaConversion(Constants::RESOLUTION_50_SQUARE)->fit(Fit::Crop, 50, 50);
                $this->addMediaConversion(Constants::RESOLUTION_100_SQUARE)->fit(Fit::Crop, 100, 100);
            });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function getFullNameAttribute(): string
    {
        return $this->name . ' ' . $this->family;
    }

    /** Get the blogs for the user. */
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }
    public function blogs(): User|Builder|HasMany
    {
        return $this->hasMany(Blog::class);
    }

    /** Get the boards that the user has access to. */
    public function boards(): BelongsToMany
    {
        return $this->belongsToMany(Board::class, 'board_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    /** Get the cards assigned to the user. */
    public function assignedCards(): BelongsToMany
    {
        return $this->belongsToMany(Card::class, 'card_user')
            ->wherePivot('role', 'assignee')
            ->withTimestamps();
    }

    /** Get the cards the user is reviewing. */
    public function reviewingCards(): BelongsToMany
    {
        return $this->belongsToMany(Card::class, 'card_user')
            ->wherePivot('role', 'reviewer')
            ->withTimestamps();
    }

    /** Get the cards the user is watching. */
    public function watchingCards(): BelongsToMany
    {
        return $this->belongsToMany(Card::class, 'card_user')
            ->wherePivot('role', 'watcher')
            ->withTimestamps();
    }

    /** Get the card history records created by the user. */
    public function cardHistory(): HasMany
    {
        return $this->hasMany(CardHistory::class);
    }
}
