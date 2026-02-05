<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\User\Tabs;

use App\Actions\User\UpdateUserParentsAction;
use App\Enums\UserTypeEnum;
use App\Models\User;
use App\Traits\CrudHelperTrait;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class UserTabParents extends Component
{
    use CrudHelperTrait, Toast;

    public User $user;

    public UserTypeEnum $detected_user_type;

    public string $detected_route_name = 'admin.user';

    public iterable $male_parents = [];

    public iterable $female_parents = [];

    public array $childrens = [];

    public ?string $father_name = '';

    public ?string $father_phone = '';

    public ?string $father_age = '';

    public ?string $father_education = '';

    public ?string $father_workplace = '';

    public ?string $mother_name = '';

    public ?string $mother_phone = '';

    public ?string $mother_age = '';

    public ?string $mother_education = '';

    public ?string $mother_workplace = '';

    public ?int $father_id = null;

    public ?int $mother_id = null;

    public array $children_id = [];

    public function mount(
        User $user,
        UserTypeEnum $detected_user_type,
        string $detected_route_name,
        iterable $male_parents = [],
        iterable $female_parents = [],
        array $childrens = [],
    ): void {
        $this->user = $user;
        $this->detected_user_type = $detected_user_type;
        $this->detected_route_name = $detected_route_name;
        $this->male_parents = $male_parents;
        $this->female_parents = $female_parents;
        $this->childrens = $childrens;

        if ($this->user->id) {
            $this->father_name = $this->user->profile?->father_name;
            $this->father_phone = $this->user->profile?->father_phone;
            $this->father_age = $this->user->profile?->extra_attributes->get('father_age', '');
            $this->father_education = $this->user->profile?->extra_attributes->get('father_education', '');
            $this->father_workplace = $this->user->profile?->extra_attributes->get('father_workplace', '');
            $this->mother_name = $this->user->profile?->mother_name;
            $this->mother_phone = $this->user->profile?->mother_phone;
            $this->mother_age = $this->user->profile?->extra_attributes->get('mother_age', '');
            $this->mother_education = $this->user->profile?->extra_attributes->get('mother_education', '');
            $this->mother_workplace = $this->user->profile?->extra_attributes->get('mother_workplace', '');
            if ($this->detected_user_type === UserTypeEnum::USER) {
                $this->father_id = $this->user->father()?->id;
                $this->mother_id = $this->user->mother()?->id;
            } elseif ($this->detected_user_type === UserTypeEnum::PARENT) {
                $this->children_id = $this->user->children()->pluck('id')->toArray();
            }
        }
    }

    protected function rules(): array
    {
        $rules = [
            'father_name' => 'nullable|string|max:255',
            'father_phone' => 'nullable|string|max:255',
            'father_age' => 'nullable|string|max:10',
            'father_education' => 'nullable|string|max:255',
            'father_workplace' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'mother_phone' => 'nullable|string|max:255',
            'mother_age' => 'nullable|string|max:10',
            'mother_education' => 'nullable|string|max:255',
            'mother_workplace' => 'nullable|string|max:255',
            'father_id' => [
                Rule::requiredIf(
                    $this->detected_user_type === UserTypeEnum::USER
                    && ($this->mother_id === null || $this->mother_id === 0 || $this->mother_id === '')
                ),
                'nullable',
                'exists:users,id',
            ],
            'mother_id' => [
                Rule::requiredIf(
                    $this->detected_user_type === UserTypeEnum::USER
                    && ($this->father_id === null || $this->father_id === 0 || $this->father_id === '')
                ),
                'nullable',
                'exists:users,id',
            ],
            'children_id' => 'nullable|array',
            'children_id.*' => 'exists:users,id',
        ];

        return $rules;
    }

    protected function messages(): array
    {
        return [
            'father_id.required' => trans('user.validation.at_least_one_parent_required'),
            'mother_id.required' => trans('user.validation.at_least_one_parent_required'),
        ];
    }

    public function submit(): void
    {
        $payload = $this->validate($this->rules(), $this->messages());
        $payload['type'] = $this->detected_user_type->value;
        UpdateUserParentsAction::run($this->user, $payload);
        $this->success(
            title: trans('general.model_has_updated_successfully', ['model' => $this->detected_user_type->title()]),
        );
    }

    public function render(): View
    {
        return view('livewire.admin.pages.user.tabs.user-tab-parents');
    }
}
