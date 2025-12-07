<?php

declare(strict_types=1);

namespace App\Actions\Taxonomy;

use App\Models\Taxonomy;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateTaxonomyAction
{
    use AsAction;

    public function __construct(
    ) {}

    /**
     * @param array{
     *     name:string,
     *     type:string,
     *     color:string,
     * }               $payload
     * @throws Throwable
     */
    public function handle(Taxonomy $taxonomy, array $payload): Taxonomy
    {
        return DB::transaction(function () use ($taxonomy, $payload) {
            $taxonomy->update($payload);

            return $taxonomy->refresh();
        });
    }
}
