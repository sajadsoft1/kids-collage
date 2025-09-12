<?php

namespace App\Actions\Category;

use App\Models\Category;
use App\Repositories\Category\CategoryRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class ToggleCategoryAction
{
    use AsAction;

    public function __construct(private readonly CategoryRepositoryInterface $repository)
    {
    }

    public function handle(Category $category): Category
    {
        return DB::transaction(function () use ($category) {
            /** @var Category $model */
            $model =  $this->repository->toggle($category);

            return $model->refresh();
        });
    }
}
