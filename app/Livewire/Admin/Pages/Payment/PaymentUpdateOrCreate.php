<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Payment;

use App\Actions\Payment\StorePaymentAction;
use App\Actions\Payment\UpdatePaymentAction;
use App\Models\Payment;
use App\Traits\CrudHelperTrait;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;
use Throwable;

class PaymentUpdateOrCreate extends Component
{
    use CrudHelperTrait;
    use Toast;

    public Payment $model;
    public string $title       = '';
    public string $description = '';
    public bool $published     = false;

    public function mount(Payment $payment): void
    {
        $this->model = $payment;
        if ($this->model->id) {
            $this->title       = $this->model->title;
            $this->description = $this->model->description;
            $this->published   = $this->model->published->value;
        }
    }

    protected function rules(): array
    {
        return [
            'title' => 'required|string',
            'description' => 'required|string',
            'published' => 'required',
        ];
    }

    public function submit(): void
    {
        $payload = $this->validate();
        if ($this->model->id) {
            try {
                UpdatePaymentAction::run($this->model, $payload);
                $this->success(
                    title: trans('general.model_has_updated_successfully', ['model' => trans('payment.model')]),
                    redirectTo: route('admin.payment.index')
                );
            } catch (Throwable $e) {
                $this->error($e->getMessage(), timeout: 5000);
            }
        } else {
            try {
                StorePaymentAction::run($payload);
                $this->success(
                    title: trans('general.model_has_stored_successfully', ['model' => trans('payment.model')]),
                    redirectTo: route('admin.payment.index')
                );
            } catch (Throwable $e) {
                $this->error($e->getMessage(), timeout: 5000);
            }
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.payment.payment-update-or-create', [
            'edit_mode' => $this->model->id,
            'breadcrumbs' => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.payment.index'), 'label' => trans('general.page.index.title', ['model' => trans('payment.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('payment.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.payment.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
