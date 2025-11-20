<?php

declare(strict_types=1);

namespace App\Actions\Notebook;

use App\Models\Notebook;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateNotebookAction
{
    use AsAction;

    public function __construct(
    ) {}

    /**
     * @param array{
     *     title:string,
     *     description:string
     * }               $payload
     * @throws Throwable
     */
    public function handle(Notebook $notebook, array $payload): Notebook
    {
        return DB::transaction(function () use ($notebook, $payload) {
            $notebook->update($payload);

            return $notebook->refresh();
        });
    }
}
