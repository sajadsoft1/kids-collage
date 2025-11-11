<?php

declare(strict_types=1);

namespace App\Actions\Setting;

use App\Enums\SettingEnum;
use App\Models\Setting;
use App\Services\File\FileService;
use App\Services\Setting\SettingService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateSettingAction
{
    use AsAction;

    public function __construct(
        private readonly SettingService $settingService,
        private readonly FileService $fileService,
    ) {}

    /** @param  array{
     *     template: string,
     *     value: array,
     * } $payload
     */
    public function handle(Setting $setting, array $payload)
    {
        return DB::transaction(function () use ($setting, $payload) {
            $media = null;
            $currentSettings = $this->settingService::get(SettingEnum::from($setting->key));
            
            foreach ($currentSettings as $key => &$currentSetting) {
                $requestValueArray = collect($payload['value'])->firstWhere('key', $key);
                
                if ($requestValueArray) {
                    if (is_array($currentSetting)) {
                        foreach ($currentSetting as $subKey => &$item) {
                            if (isset($requestValueArray['value'][$subKey])) {
                                $item = $requestValueArray['value'][$subKey];
                            }
                        }
                    } else {
                        $currentSetting = $requestValueArray['value'];
                    }
                }
            }
            
            if ($logo = Arr::get($currentSettings, 'logo')) {
                $media = $setting->addMedia($logo)->toMediaCollection('logo');
            }
            
            if ($media) {
                $currentSettings['logo'] = $media->id;
            }
            
            return $this->settingService->update(SettingEnum::from($setting->key), $currentSettings);
        });
    }
}
