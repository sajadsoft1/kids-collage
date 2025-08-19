<?php

declare(strict_types=1);

namespace App\Actions\Tag;

use App\Models\Tag;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteTagAction
{
    use AsAction;

    /** @throws Throwable */
    public function handle(Tag $tag): bool
    {
        return DB::transaction(function () use ($tag) {
            abort_if(
                $tag->isInUse(),
                403,
                message: trans('tag.exceptions.not_allowed_to_delete')
            );

            return $tag->delete();
        });
    }
}
