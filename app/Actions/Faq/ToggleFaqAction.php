<?php

namespace App\Actions\Faq;

use App\Models\Faq;
use App\Repositories\Faq\FaqRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class ToggleFaqAction
{
    use AsAction;

    public function __construct(private readonly FaqRepositoryInterface $repository)
    {
    }

    public function handle(Faq $faq): Faq
    {
        return DB::transaction(function () use ($faq) {
            /** @var Faq $model */
            $model =  $this->repository->toggle($faq);

            return $model->refresh();
        });
    }
}
