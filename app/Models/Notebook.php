<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasBranch;
use App\Traits\HasBranchScope;
use App\Traits\HasUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $title
 * @property string $description
 */
class Notebook extends Model
{
    use HasBranch;
    use HasBranchScope;
    use HasFactory;
    use HasUser;

    protected $fillable = [
        'user_id',
        'taxonomy_id',
        'title',
        'body',
        'tags',
        'branch_id',
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    /**
     * Model Configuration --------------------------------------------------------------------------
     */

    /** Model Relations -------------------------------------------------------------------------- */
    public function tag(): BelongsTo
    {
        return $this->belongsTo(Taxonomy::class);
    }
    /**
     * Model Scope --------------------------------------------------------------------------
     */

    /**
     * Model Attributes --------------------------------------------------------------------------
     */

    /**
     * Model Custom Methods --------------------------------------------------------------------------
     */
}
