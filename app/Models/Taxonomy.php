<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TaxonomyTypeEnum;
use App\Traits\HasUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Taxonomy extends Model
{
    use HasFactory;
    use HasUser;

    protected $fillable = [
        'user_id',
        'name',
        'type',
        'color',
    ];
    protected $casts = [
        'type' => TaxonomyTypeEnum::class,
    ];

    public function flashcards()
    {
        return self::where('type', TaxonomyTypeEnum::FLASHCARD)
            ->where('user_id', auth()->user()->id)
            ->get();
    }

    public function notebooks()
    {
        return self::where('type', TaxonomyTypeEnum::NOTEBOOK)
            ->where('user_id', auth()->user()->id)
            ->get();
    }
}
