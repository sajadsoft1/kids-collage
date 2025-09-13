<?php

namespace App\Actions\Client;

use App\Models\Client;
use App\Repositories\Client\ClientRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class ToggleClientAction
{
    use AsAction;

    public function __construct(private readonly ClientRepositoryInterface $repository)
    {
    }

    public function handle(Client $client): Client
    {
        return DB::transaction(function () use ($client) {
            /** @var Client $model */
            $model =  $this->repository->toggle($client);

            return $model->refresh();
        });
    }
}
