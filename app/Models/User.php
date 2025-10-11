<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\BooleanEnum;
use App\Enums\GenderEnum;
use App\Enums\UserTypeEnum;
use App\Facades\SmartCache;
use App\Helpers\Constants;
use App\Traits\CLogsActivity;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
    use CLogsActivity, HasApiTokens, HasFactory, HasRoles, InteractsWithMedia,Notifiable;

    protected $fillable = [
        'profile_id',
        'name',
        'family',
        'email',
        'mobile',
        'password',
        'status',
        'type',
    ];

    protected $hidden = [
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at'  => 'datetime',
        'mobile_verified_at' => 'datetime',
        'status'             => BooleanEnum::class,
        'type'               => UserTypeEnum::class,
    ];

    /** Model Configuration -------------------------------------------------------------------------- */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->singleFile()
            ->useFallbackUrl(url('/assets/images/default/user-avatar.png'))
            ->registerMediaConversions(function () {
                $this->addMediaConversion(Constants::RESOLUTION_512_SQUARE)->fit(Fit::Crop, 512, 512);
            });

        $this->addMediaCollection('national_card')
            ->singleFile()
            ->useFallbackUrl('/assets/images/default/user-avatar.png')
            ->registerMediaConversions(function () {
                $this->addMediaConversion(Constants::RESOLUTION_512_SQUARE)->fit(Fit::Crop, 512, 512);
            });

        $this->addMediaCollection('birth_certificate')
            ->singleFile()
            ->useFallbackUrl('/assets/images/default/user-avatar.png')
            ->registerMediaConversions(function () {
                $this->addMediaConversion(Constants::RESOLUTION_512_SQUARE)->fit(Fit::Crop, 512, 512);
            });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /** Model Relations -------------------------------------------------------------------------- */
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    /** Get the parent of the user (if the user is a child). */
    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(__CLASS__, ParentChild::class, 'child_id', 'parent_id');
    }

    /** Get the children of the user (if the user is a parent). */
    public function children(): BelongsToMany
    {
        return $this->belongsToMany(__CLASS__, ParentChild::class, 'parent_id', 'child_id');
    }

    public function blogs(): HasMany
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

    // classes taught by the user (if the user is a teacher)
    public function taughtClasses(): HasMany
    {
        return $this->hasMany(CourseSession::class, 'teacher_id');
    }

    /** Get the enrollments for the user (if the user is a student). */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Model Scope --------------------------------------------------------------------------
     */

    /** Model Attributes -------------------------------------------------------------------------- */
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => trim(($this->name ?? '') . ' ' . ($this->family ?? '')),
        );
    }

    /** Model Custom Methods -------------------------------------------------------------------------- */
    public static function teachers()
    {
        return SmartCache::for(__CLASS__)
            ->key('teachers')
            ->remember(function () {
                return self::where('status', BooleanEnum::ENABLE->value)
                    ->where('type', UserTypeEnum::TEACHER->value)
                    ->get();
            }, 3600);
    }

    public static function studentCont()
    {
        return SmartCache::for(__CLASS__)
            ->key('student')
            ->remember(function () {
                return self::where('type', UserTypeEnum::USER->value)
                    ->count();
            }, 3600);
    }

    public function father(): ?User
    {
        return $this->parents()->whereHas('profile', fn ($q) => $q->where('gender', GenderEnum::MALE))->first();
    }

    public function mother(): ?User
    {
        return $this->parents()->whereHas('profile', fn ($q) => $q->where('gender', GenderEnum::FEMALE))->first();
    }
}
