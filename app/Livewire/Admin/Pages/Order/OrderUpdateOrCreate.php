<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Order;

use App\Actions\Order\StoreOrderAction;
use App\Actions\Order\UpdateOrderAction;
use App\Enums\CourseStatusEnum;
use App\Enums\OrderStatusEnum;
use App\Enums\PaymentTypeEnum;
use App\Enums\UserTypeEnum;
use App\Models\Course;
use App\Models\Order;
use App\Models\User;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Mary\Traits\Toast;

class OrderUpdateOrCreate extends Component
{
    use Toast;

    public Order  $model;
    public string $accordion_group;
    public int    $user_id   = 0;
    public int    $course_id = 0;
    public string $status    = OrderStatusEnum::PENDING->value;
    public array  $payments  = [];

    public function mount(Order $order): void
    {
        $this->model = $order;
        if ($this->model->id) {
            $this->user_id = $this->model->user_id;
            $this->course_id = $this->model->course_id;
            $this->status = $this->model->status->value;
            $this->payments = $this->model->payments->toArray();
        } else {
            $this->payments = [
                [
                    'id'           => null,
                    'amount'       => 0,
                    'payment_type' => PaymentTypeEnum::ONLINE,
                    'paid_at'      => now()->toDateString(),
                    'note'         => ''
                ],
            ];
        }
    }

    #[Computed]
    public function user(): User|null
    {
        return User::find($this->user_id);
    }

    #[Computed]
    public function course(): Course|null
    {
        return Course::find($this->course_id);
    }

    public function addPayment(): void
    {
        $this->payments[] = [
            'id'           => null,
            'amount'       => 0,
            'payment_type' => PaymentTypeEnum::ONLINE,
            'paid_at'      => now()->toDateString(),
            'note'         => ''
        ];
    }

    protected function rules(): array
    {
        return [
            'user_id'       => ['required', 'exists:users,id'],
            'course_id'     => ['required', 'exists:courses,id'],
            'status'        => ['required', 'in:' . implode(',', OrderStatusEnum::values())],
            'payments'      => ['array'],
            'payments.*.id' => ['nullable', 'array'],
        ];
    }

    public function submit(): void
    {
        $payload = $this->validate();
        if ($this->model->id) {
            UpdateOrderAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('order.model')]),
                redirectTo: route('admin.order.index')
            );
        } else {
            StoreOrderAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('order.model')]),
                redirectTo: route('admin.order.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.order.order-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.order.index'), 'label' => trans('general.page.index.title', ['model' => trans('order.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('order.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.order.index'), 'icon' => 's-arrow-left'],
            ],
            'users'              => User::where('type', UserTypeEnum::USER->value)->get()->map(fn(User $user) => ['label' => $user->full_name, 'value' => $user->id]),
            'courses'            => Course::query()->get()->map(fn(Course $course) => ['label' => $course->template->title, 'value' => $course->id]),
        ]);
    }
}
