<?php

namespace App\Actions\Banner;

use App\Models\Banner;
use App\Repositories\Banner\BannerRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class ToggleBannerAction
{
    use AsAction;

    public function __construct(private readonly BannerRepositoryInterface $repository)
    {
    }

    public function handle(Banner $banner): Banner
    {
        return DB::transaction(function () use ($banner) {
            /** @var Banner $model */
            $model =  $this->repository->toggle($banner);

            return $model->refresh();
        });
    }
}
