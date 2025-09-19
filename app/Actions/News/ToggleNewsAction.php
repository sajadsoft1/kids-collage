<?php

namespace App\Actions\News;

use App\Models\News;
use App\Repositories\News\NewsRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class ToggleNewsAction
{
    use AsAction;

    public function __construct(private readonly NewsRepositoryInterface $repository)
    {
    }

    public function handle(News $news): News
    {
        return DB::transaction(function () use ($news) {
            /** @var News $model */
            $model =  $this->repository->toggle($news);

            return $model->refresh();
        });
    }
}
