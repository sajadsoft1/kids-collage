<?php

declare(strict_types=1);

namespace App\Actions\Category;

use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteCategoryAction
{
    use AsAction;

    /** @throws Throwable */
    public function handle(Category $category): bool
    {
        return DB::transaction(function () use ($category) {
            abort_if(
                $category->blogs()->exists(),
                403,
                message: trans('category.exceptions.not_allowed_to_delete_category_due_to_blogs')
            );

            abort_if(
                $category->faqs()->exists(),
                403,
                message: trans('category.exceptions.not_allowed_to_delete_category_due_to_faqs')
            );

            return $category->delete();
        });
    }
}
