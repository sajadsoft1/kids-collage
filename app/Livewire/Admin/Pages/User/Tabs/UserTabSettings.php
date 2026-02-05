<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\User\Tabs;

use App\Actions\User\UpdateUserSettingsAction;
use App\Enums\UserTypeEnum;
use App\Models\User;
use App\Traits\CrudHelperTrait;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class UserTabSettings extends Component
{
    use CrudHelperTrait, Toast;

    public User $user;

    public UserTypeEnum $detected_user_type;

    public string $detected_route_name = 'admin.user';

    public array $rules = [];

    public bool $status = true;

    public array $selected_rules = [];

    public function mount(User $user, UserTypeEnum $detected_user_type, string $detected_route_name, array $rules = []): void
    {
        $this->user = $user;
        $this->detected_user_type = $detected_user_type;
        $this->detected_route_name = $detected_route_name;
        $this->rules = $rules;

        if ($this->user->id) {
            $this->status = (bool) $this->user->status->value;
            $this->selected_rules = $this->user->roles->pluck('id')->toArray();
        }
    }

    protected function rules(): array
    {
        return [
            'status' => 'required|boolean',
            'selected_rules' => 'nullable|array',
            'selected_rules.*' => 'exists:roles,id',
        ];
    }

    public function submit(): void
    {
        $payload = $this->validate();
        $payload['rules'] = $payload['selected_rules'];
        UpdateUserSettingsAction::run($this->user, $payload);
        $this->success(
            title: trans('general.model_has_updated_successfully', ['model' => $this->detected_user_type->title()]),
        );
    }

    public function render(): View
    {
        return view('livewire.admin.pages.user.tabs.user-tab-settings');
    }
}
