<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUser;
use App\Traits\MorphAttributesTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class WishList extends Model
{
    use HasUser;
    use MorphAttributesTrait;

    /**
     * | Model Configuration ----------------------------------------------------------------------
     * |--------------------------------------------------------------------------
     */
    public const UPDATED_AT = null;
    protected $table        = 'user_wishlists';
    protected $fillable     = [
        'user_id',
        'morphable_id',
        'morphable_type',
    ];

    /**
     * | Model Relations --------------------------------------------------------------------------
     * |--------------------------------------------------------------------------
     */

    /**
     * | Model Scope ------------------------------------------------------------------------------
     * |--------------------------------------------------------------------------
     */

    /**
     * | Model Attributes -------------------------------------------------------------------------
     * |--------------------------------------------------------------------------
     */
    public function getTitleAttribute(): string
    {
        return $this->morphable->title;
    }

    public function getImageAttribute(): ?Media
    {
        return $this->morphable->getMedia('image')->first();
    }

    /**
     * | Model Custom Methods ---------------------------------------------------------------------
     * |--------------------------------------------------------------------------
     */
}
