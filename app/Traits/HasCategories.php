<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Category;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasCategories
{
    public function categories(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'categoryable');
    }
    
    public function getCategoriesTitleAttribute(): string
    {
        return $this->categories()->with('translations')
            ->whereHas('translations', function ($q) {
                $q->where('key', 'title')
                    ->where('locale', app()->getLocale());
            })
            ->get()
            ->map(function (Category $category) {
                return $category->translations->pluck('value');
            })->flatten()->implode(',');
    }
}
