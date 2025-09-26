<?php

namespace App\Actions\Session;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Session;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateSessionAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}


    /**
     * @param Session $session
     * @param array{
     *     title:string,
     *     description:string
     * }               $payload
     * @return Session
     * @throws Throwable
     */
    public function handle(Session $session, array $payload): Session
    {
        return DB::transaction(function () use ($session, $payload) {
            $session->update($payload);
            $this->syncTranslationAction->handle($session, Arr::only($payload, ['title', 'description']));

            return $session->refresh();
        });
    }
}
