<?php

declare(strict_types=1);

namespace App\Actions\Translation;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class SyncTranslationAction
{
    use AsAction;

    public function __construct() {}

    /** @throws Throwable */
    public function handle($model, array $payload = []): void
    {
        DB::transaction(function () use ($model, $payload) {
            $locale = Arr::get($payload, 'locale', app()->getLocale());
            foreach ($model->translatable as $column) {
                $value = Arr::get($payload, $column, request()->input($column));
                if ( ! empty($value)) {
                    $model->translations()->updateOrCreate([
                        'key'    => $column,
                        'locale' => $locale,
                    ], [
                        'value' => $value,
                    ]);
                    $translatedLanguages = $model->languages ?? [];
                    if ( ! in_array($locale, $translatedLanguages, true)) {
                        $translatedLanguages[] = $locale;
                        $model->update([
                            'languages' => $translatedLanguages,
                        ]);
                    }
                    $cacheName = generateCacheKey($model::class, $model->id, $column, $locale);
                    cache()->forget($cacheName);
                    cache()->rememberForever($cacheName, function () use ($value) {
                        return $value;
                    });
                }
            }
        });
    }
}
