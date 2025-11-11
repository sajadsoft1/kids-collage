<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Discount;

use App\Actions\Discount\StoreDiscountAction;
use App\Actions\Discount\UpdateDiscountAction;
use App\Enums\DiscountTypeEnum;
use App\Enums\UserTypeEnum;
use App\Models\Discount;
use App\Models\User;
use App\Traits\CrudHelperTrait;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;
use Throwable;

class DiscountUpdateOrCreate extends Component
{
    use CrudHelperTrait;
    use Toast;

    public Discount $model;
    public string $code = '';
    public string $type = DiscountTypeEnum::PERCENTAGE->value;
    public float $value = 0;
    public ?int $user_id = null;
    public float $min_order_amount = 0;
    public ?float $max_discount_amount = null;
    public ?int $usage_limit = null;
    public int $usage_per_user = 1;
    public ?string $starts_at = null;
    public ?string $expires_at = null;
    public bool $is_active = true;
    public string $description = '';

    public function mount(Discount $discount): void
    {
        $this->model = $discount;
        if ($this->model->id) {
            $this->code = $this->model->code;
            $type = $this->model->type;
            $this->type = $type instanceof DiscountTypeEnum ? $type->value : $type;
            $this->value = $this->model->value;
            $this->user_id = $this->model->user_id;
            $this->min_order_amount = $this->model->min_order_amount;
            $this->max_discount_amount = $this->model->max_discount_amount;
            $this->usage_limit = $this->model->usage_limit;
            $this->usage_per_user = $this->model->usage_per_user;
            $this->starts_at = $this->model->starts_at?->format('Y-m-d\TH:i');
            $this->expires_at = $this->model->expires_at?->format('Y-m-d\TH:i');
            $this->is_active = (bool) $this->model->is_active->value;
            $this->description = $this->model->description ?? '';
        }
    }

    protected function rules(): array
    {
        $codeRule = $this->model->id
            ? 'required|string|max:50|unique:discounts,code,' . $this->model->id
            : 'required|string|max:50|unique:discounts,code';

        return [
            'code' => $codeRule,
            'type' => 'required|in:' . implode(',', DiscountTypeEnum::values()),
            'value' => ['required', 'numeric', 'min:1', $this->type === DiscountTypeEnum::PERCENTAGE->value ? 'max:100' : 'max:5000000000'],
            'user_id' => 'nullable|exists:users,id',
            'min_order_amount' => 'required|numeric|min:0|max:5000000000',
            'max_discount_amount' => 'nullable|numeric|min:0|max:5000000000',
            'usage_limit' => 'nullable|integer|min:1|max:5000000',
            'usage_per_user' => 'required|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:starts_at',
            'is_active' => 'required|boolean',
            'description' => 'nullable|string|max:500',
        ];
    }

    public function submit(): void
    {
        $payload = $this->validate();

        // Convert code to uppercase
        $payload['code'] = strtoupper($payload['code']);

        if ($this->model->id) {
            try {
                UpdateDiscountAction::run($this->model, $payload);
                $this->success(
                    title: trans('general.model_has_updated_successfully', ['model' => trans('discount.model')]),
                    redirectTo: route('admin.discount.index')
                );
            } catch (Throwable $e) {
                $this->error($e->getMessage(), timeout: 5000);
            }
        } else {
            try {
                StoreDiscountAction::run($payload);
                $this->success(
                    title: trans('general.model_has_stored_successfully', ['model' => trans('discount.model')]),
                    redirectTo: route('admin.discount.index')
                );
            } catch (Throwable $e) {
                $this->error($e->getMessage(), timeout: 5000);
            }
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.discount.discount-update-or-create', [
            'edit_mode' => $this->model->id,
            'breadcrumbs' => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.discount.index'), 'label' => trans('general.page.index.title', ['model' => trans('discount.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('discount.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.discount.index'), 'icon' => 's-arrow-left'],
            ],
            'users' => User::where('type', UserTypeEnum::USER->value)->get()->map(fn (User $user) => ['label' => $user->full_name, 'value' => $user->id]),
            'discountTypes' => collect(DiscountTypeEnum::cases())->map(fn ($type) => ['label' => $type->title(), 'value' => $type->value]),
        ]);
    }
}
