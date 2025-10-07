<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Order;

use App\Actions\Order\StoreOrderAction;
use App\Actions\Order\UpdateOrderAction;
use App\Enums\OrderStatusEnum;
use App\Models\Order;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class OrderUpdateOrCreate extends Component
{
    use Toast;

    public Order $model;
    public int $user_id;
    public int $course_id;
    public string $status                       = OrderStatusEnum::PENDING->value;
    public array $payments                      = [];

    public function mount(Order $order): void
    {
        $this->model = $order;
        if ($this->model->id) {
            $this->user_id           = $this->model->user_id;
            $this->course_id         = $this->model->course_id;
            $this->status            = $this->model->status->value;
            $this->payments          = $this->model->payments->toArray();
        }
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
                ['link'  => route('admin.order.index'), 'label' => trans('general.page.index.title', ['model' => trans('order.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('order.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.order.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
