<?php

declare(strict_types=1);

namespace App\Actions\Notebook;

use App\Models\Notebook;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreNotebookAction
{
    use AsAction;

    public function __construct(
    ) {}

    /**
     * @param array{
     *     title:string,
     *     body:string,
     *     tags:array
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): Notebook
    {
        return DB::transaction(function () use ($payload) {
            $payload['user_id'] = auth()->user()->id;
            $model = Notebook::create($payload);

            return $model;
        });
    }
}
