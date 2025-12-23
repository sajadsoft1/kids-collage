<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasBranch;
use App\Traits\HasBranchScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionOption extends Model
{
    use HasBranch;
    use HasBranchScope;
    use HasFactory;

    protected $fillable = [
        'question_id',
        'content',
        'type',
        'is_correct',
        'order',
        'metadata',
        'branch_id',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'order' => 'integer',
        'metadata' => 'array',
    ];

    /** Model Relations -------------------------------------------------------------------------- */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /** Model Custom Methods -------------------------------------------------------------------------- */
    public function isImage(): bool
    {
        return $this->type === 'image';
    }

    public function isText(): bool
    {
        return $this->type === 'text';
    }

    public function isHtml(): bool
    {
        return $this->type === 'html';
    }
}
