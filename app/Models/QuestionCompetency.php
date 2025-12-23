<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasBranch;
use App\Traits\HasBranchScope;
use App\Traits\HasTranslationAuto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $title
 * @property string $description
 */
class QuestionCompetency extends Model
{
    use HasBranch;
    use HasBranchScope;
    use HasFactory;
    use HasTranslationAuto;

    public array $translatable = [
        'title', 'description',
    ];

    protected $fillable = [
        'languages',
        'ordering',
        'branch_id',
    ];

    protected $casts = [
        'languages' => 'array',
        'ordering' => 'integer',
    ];

    /**
     * Model Configuration --------------------------------------------------------------------------
     */

    /**
     * Model Relations --------------------------------------------------------------------------
     */

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
