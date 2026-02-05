<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\User\Tabs;

use App\Actions\User\UpdateUserImagesAction;
use App\Enums\UserTypeEnum;
use App\Models\User;
use App\Traits\CrudHelperTrait;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class UserTabImages extends Component
{
    use CrudHelperTrait, Toast, WithFileUploads;

    public User $user;

    public UserTypeEnum $detected_user_type;

    public string $detected_route_name = 'admin.user';

    public $avatar;

    public $national_card;

    public $birth_certificate;

    public function mount(User $user, UserTypeEnum $detected_user_type, string $detected_route_name): void
    {
        $this->user = $user;
        $this->detected_user_type = $detected_user_type;
        $this->detected_route_name = $detected_route_name;
    }

    protected function rules(): array
    {
        return [
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'national_card' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'birth_certificate' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ];
    }

    public function submit(): void
    {
        $payload = $this->validate();
        UpdateUserImagesAction::run($this->user, $payload);
        $this->success(
            title: trans('general.model_has_updated_successfully', ['model' => $this->detected_user_type->title()]),
        );
    }

    public function render(): View
    {
        return view('livewire.admin.pages.user.tabs.user-tab-images');
    }
}
