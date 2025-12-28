<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Setting;

use App\Actions\Setting\UpdateSettingAction;
use App\Enums\SettingEnum;
use App\Exceptions\ValidationException;
use App\Models\Setting;
use App\Services\Setting\SettingService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class SettingList extends Component
{
    use Toast, WithFileUploads;

    public Collection $settings;

    public $detail = [];
    public array $data = [];
    private SettingService $settingService;

    public function boot(SettingService $settingService): void
    {
        $this->settingService = $settingService;

        $customOrder = ['general', 'integration_sync', 'notification', 'sale'];
        $cases = collect($customOrder)
            ->map(fn ($key, $i) => "WHEN `key` = ? THEN {$i}")
            ->implode(' ');
        $orderByRaw = "CASE {$cases} ELSE " . count($customOrder) . ' END';

        $this->settings = Setting::orderByRaw($orderByRaw, $customOrder)
            ->orderBy('key')
            ->get();
    }

    public function mount(): void
    {
        $this->detail = $this->settingService->show(Setting::where('key', SettingEnum::GENERAL->value)->first());
        $this->updateData();
    }

    public function show(Setting $setting): void
    {
        $this->detail = $this->settingService->show($setting);
        $this->updateData();
    }

    public function update(): void
    {
        $sendData = [];
        collect($this->data)->each(function ($item, $key) use (&$sendData) {
            if (is_array($item)) {
                $values = [];
                collect($item)->each(function ($value, $k) use (&$values) {
                    $values[$k] = $value == '-1' ? false : $value;
                });
                $sendData[] = ['key' => $key, 'value' => $values];
            } else {
                $sendData[] = ['key' => $key, 'value' => $item == '-1' ? false : $item];
            }
        });
        $sendData = [
            'template' => $this->detail['key'],
            'value' => $sendData,
        ];

        try {
            UpdateSettingAction::run(Setting::where('key', $this->detail['key'])->first(), $sendData);
            $this->success(
                title: trans('setting.setting_store_succesfully'),
            );
        } catch (ValidationException $e) {
            foreach (json_decode($e->getMessage(), true) as $key => $errorMessage) {
                $this->addError($this->findDetailKey($key), $errorMessage[0]); // Add the error to the error bag
            }
        }
    }

    private function findDetailKey(string $errorKey): string
    {
        $parts = explode('.', $errorKey); // Split the key into parts
        $rowKey = $parts[0];              // First part (e.g., "company")
        $itemKey = $parts[1] ?? null;     // Second part (e.g., "postal_code")

        foreach ($this->detail['rows'] as $rowIndex => $row) {
            if ($row['key'] === $rowKey && isset($row['items'])) {
                foreach ($row['items'] as $itemIndex => $item) {
                    if ($item['key'] === $itemKey) {
                        // Return the correct key format for Livewire
                        return "data.{$rowKey}.{$itemKey}";
                    }
                }
            }
        }

        return $errorKey; // Fallback to the original key if not found
    }

    private function updateData(): void
    {
        $this->data = [];
        collect($this->detail['rows'])->map(function ($row) {
            if (Arr::get($row, 'complex')) {
                collect($row['items'])->map(function ($item) use ($row) {
                    $this->data[$row['key']][$item['key']] = $item['value']['value'] == '0' ? '-1' : $item['value']['value'];
                });
            } else {
                $this->data[$row['key']] = $row['value']['value'] == '0' ? '-1' : $row['value']['value'];
            }
        });
    }

    public function render(): View
    {
        return view('livewire.admin.pages.setting.setting-list', [
            'breadcrumbs' => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['label' => trans('datatable.setting')],
            ],
            'breadcrumbsActions' => [
            ],
            'iconMap' => [
                'general' => 'lucide.sidebar',
                'product' => 'lucide.box',
                'security' => 'lucide.lock',
                'integration_sync' => 'lucide.folder-sync',
                'notification' => 'lucide.message-square-share',
                'sale' => 'lucide.shopping-cart',
                'seo_pages' => 'lucide.search',
                'site_data' => 'lucide.layout',
            ],
        ]);
    }
}
