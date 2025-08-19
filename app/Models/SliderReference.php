<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\MorphAttributesTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SliderReference extends Model
{
    use MorphAttributesTrait;
    public $timestamps = false;

    protected $table = 'slider_references';

    /**
     * | Model Configuration ----------------------------------------------------------------------
     * |--------------------------------------------------------------------------
     */
    protected $fillable = [
        'slider_id',
        'morphable_type',
        'morphable_id',
    ];

    /**
     * | Model Relations --------------------------------------------------------------------------
     * |--------------------------------------------------------------------------
     */
    public function slider(): BelongsTo
    {
        return $this->belongsTo(Slider::class, 'slider_id');
    }
}
