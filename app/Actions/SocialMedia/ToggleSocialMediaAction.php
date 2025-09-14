<?php

namespace App\Actions\SocialMedia;

use App\Models\SocialMedia;
use App\Repositories\SocialMedia\SocialMediaRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class ToggleSocialMediaAction
{
    use AsAction;

    public function __construct(private readonly SocialMediaRepositoryInterface $repository)
    {
    }

    public function handle(SocialMedia $socialMedia): SocialMedia
    {
        return DB::transaction(function () use ($socialMedia) {
            /** @var SocialMedia $model */
            $model =  $this->repository->toggle($socialMedia);

            return $model->refresh();
        });
    }
}
