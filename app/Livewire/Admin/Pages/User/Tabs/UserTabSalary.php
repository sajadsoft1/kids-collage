<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\User\Tabs;

use App\Actions\User\UpdateUserSalaryAction;
use App\Enums\UserTypeEnum;
use App\Models\User;
use App\Traits\CrudHelperTrait;
use Carbon\Carbon;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class UserTabSalary extends Component
{
    use CrudHelperTrait, Toast;

    public User $user;

    public UserTypeEnum $detected_user_type;

    public string $detected_route_name = 'admin.user';

    public ?float $salary = 0;

    public ?float $benefit = 0;

    public ?Carbon $cooperation_start_date = null;

    public ?Carbon $cooperation_end_date = null;

    public function mount(User $user, UserTypeEnum $detected_user_type, string $detected_route_name): void
    {
        $this->user = $user;
        $this->detected_user_type = $detected_user_type;
        $this->detected_route_name = $detected_route_name;

        if ($this->user->id && $this->user->profile) {
            $this->salary = $this->user->profile->salary;
            $this->benefit = $this->user->profile->benefit;
            $this->cooperation_start_date = $this->user->profile->cooperation_start_date;
            $this->cooperation_end_date = $this->user->profile->cooperation_end_date;
        }
    }

    protected function rules(): array
    {
        return [
            'salary' => 'nullable|decimal:0,2|min:0',
            'benefit' => 'nullable|decimal:0,2|min:0',
            'cooperation_start_date' => 'nullable|date',
            'cooperation_end_date' => 'nullable|date|after_or_equal:cooperation_start_date',
        ];
    }

    public function submit(): void
    {
        $payload = $this->validate();
        UpdateUserSalaryAction::run($this->user, $payload);
        $this->success(
            title: trans('general.model_has_updated_successfully', ['model' => $this->detected_user_type->title()]),
        );
    }

    public function render(): View
    {
        return view('livewire.admin.pages.user.tabs.user-tab-salary');
    }
}
