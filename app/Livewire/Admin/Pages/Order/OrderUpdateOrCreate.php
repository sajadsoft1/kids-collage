<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Order;

use App\Actions\Order\StoreOrderAction;
use App\Actions\Order\UpdateOrderAction;
use App\Enums\OrderStatusEnum;
use App\Enums\PaymentTypeEnum;
use App\Enums\UserTypeEnum;
use App\Models\Course;
use App\Models\Discount;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\User;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Mary\Traits\Toast;

class OrderUpdateOrCreate extends Component
{
    use Toast;

    public Order $model;
    public string $accordion_group;
    public int $user_id            = 0;
    public int $course_id          = 0;
    public string $status          = OrderStatusEnum::PENDING->value;
    public string $note            = '';
    public string $discount_code   = '';
    public float $discount_amount  = 0;
    public ?int $discount_id       = null;
    public array $items            = [];
    public array $payments         = [];

    public function mount(Order $order): void
    {
        $this->model = $order->load(['discount', 'items', 'payments']);
        if ($this->model->id) {
            $this->user_id       = $this->model->user_id;
            $this->status        = $this->model->status instanceof OrderStatusEnum
                ? $this->model->status->value
                : $this->model->status;
            $this->note            = $this->model->note ?? '';
            $discount              = $this->model->discount;
            $this->discount_code   = $discount ? $discount->code : '';
            $this->discount_amount = $this->model->discount_amount ?? 0;
            $this->discount_id     = $this->model->discount_id;
            // Load items
            $this->items = $this->model->items->map(function ($item) {
                return [
                    'id'            => $item->id,
                    'itemable_type' => $item->itemable_type,
                    'itemable_id'   => $item->itemable_id,
                    'price'         => $item->price,
                    'quantity'      => $item->quantity,
                ];
            })->toArray();

            // Load course_id from first item if it's a Course
            $firstItem = $this->model->items->first();
            if ($firstItem && $firstItem->itemable_type === Course::class) {
                $this->course_id = $firstItem->itemable_id;
            }

            // Load payments
            $this->payments = $this->model->payments->map(function ($payment) {
                return [
                    'id'               => $payment->id,
                    'amount'           => $payment->amount,
                    'payment_type'     => $payment->type->value,
                    'paid_at'          => $payment->paid_at?->toDateString() ?? now()->toDateString(),
                    'status'           => $payment->status->value ?? '',
                    'last_card_digits' => $payment->last_card_digits ?? '',
                    'tracking_code'    => $payment->tracking_code ?? '',
                    'note'             => $payment->note ?? '',
                ];
            })->toArray();
        } else {
            // Initialize with one empty item
            $this->items = [
                [
                    'id'            => null,
                    'itemable_type' => Course::class,
                    'itemable_id'   => 0,
                    'price'         => 0,
                    'quantity'      => 1,
                ],
            ];

            // Initialize with one empty payment
            $this->payments = [
                [
                    'id'               => null,
                    'amount'           => 0,
                    'payment_type'     => PaymentTypeEnum::ONLINE->value,
                    'paid_at'          => now()->toDateString(),
                    'status'           => '',
                    'last_card_digits' => '',
                    'tracking_code'    => '',
                    'note'             => '',
                ],
            ];
        }
    }

    #[Computed]
    public function user(): ?User
    {
        return User::find($this->user_id);
    }

    #[Computed]
    public function course(): ?Course
    {
        return Course::find($this->course_id);
    }

    public function addItem(): void
    {
        $this->items[] = [
            'id'            => null,
            'itemable_type' => Course::class,
            'itemable_id'   => 0,
            'price'         => 0,
            'quantity'      => 1,
        ];
    }

    public function removeItem(int $index): void
    {
        if (count($this->items) > 1) {
            unset($this->items[$index]);
            $this->items = array_values($this->items);
        }
    }

    public function addPayment(): void
    {
        $this->payments[] = [
            'id'               => null,
            'amount'           => 0,
            'payment_type'     => PaymentTypeEnum::ONLINE->value,
            'paid_at'          => now()->toDateString(),
            'status'           => '',
            'last_card_digits' => '',
            'tracking_code'    => '',
            'note'             => '',
        ];
    }

    public function removePayment(int $index): void
    {
        if (count($this->payments) > 1) {
            unset($this->payments[$index]);
            $this->payments = array_values($this->payments);
        }
    }

    protected function rules(): array
    {
        return [
            'user_id'                     => ['required', 'exists:users,id'],
            'course_id'                   => ['required', 'exists:courses,id'],
            'status'                      => ['required', 'in:' . implode(',', OrderStatusEnum::values())],
            'note'                        => ['nullable', 'string'],
            'discount_code'               => ['nullable', 'string', 'exists:discounts,code'],
            'discount_id'                 => ['nullable', 'integer', 'exists:discounts,id'],
            'discount_amount'             => ['nullable', 'numeric', 'min:0'],
            'items'                       => ['required', 'array', 'min:1'],
            'items.*.id'                  => ['nullable', 'integer'],
            'items.*.itemable_type'       => ['required', 'string'],
            'items.*.itemable_id'         => ['required', 'integer'],
            'items.*.price'               => ['required', 'numeric', 'min:0'],
            'items.*.quantity'            => ['required', 'integer', 'min:1'],
            'payments'                    => ['required', 'array', 'min:1'],
            'payments.*.id'               => ['nullable', 'integer'],
            'payments.*.amount'           => ['required', 'numeric', 'min:0'],
            'payments.*.payment_type'     => ['required', 'in:' . implode(',', PaymentTypeEnum::values())],
            'payments.*.paid_at'          => ['required', 'date'],
            'payments.*.status'           => ['nullable', 'string'],
            'payments.*.last_card_digits' => ['nullable', 'string', 'max:4'],
            'payments.*.tracking_code'    => ['nullable', 'string'],
            'payments.*.note'             => ['nullable', 'string'],
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

    /**
     * Apply discount code to the order
     *
     * Validates discount code and checks all conditions:
     * - Discount exists and is active
     * - User eligibility
     * - Usage limits
     * - Minimum order amount
     * - Date validity
     */
    public function applyDiscount(): void
    {
        // Reset discount if code is empty
        if (empty(trim($this->discount_code))) {
            $this->discount_amount = 0;
            $this->discount_id     = null;
            $this->warning(trans('order.discount_code_required'));

            return;
        }

        // Validate required fields
        if ( ! $this->user_id || ! $this->course_id) {
            $this->warning(trans('order.please_select_user_and_course'));

            return;
        }

        // Find discount by code
        $discount = Discount::where('code', trim($this->discount_code))->first();

        if ( ! $discount) {
            $this->discount_amount = 0;
            $this->discount_id     = null;
            $this->error(trans('order.discount_not_found'));

            return;
        }

        // Get order amount (course price)
        $orderAmount = $this->course?->price ?? 0;

        if ($orderAmount <= 0) {
            $this->warning(trans('order.course_has_no_price'));

            return;
        }

        // Validate and calculate discount using model's method
        $result = $discount->validateAndCalculate($orderAmount, $this->user_id);

        if ( ! $result['success']) {
            $this->discount_amount = 0;
            $this->discount_id     = null;
            $this->error($result['message']);

            return;
        }

        // Apply discount
        $this->discount_amount = $result['discount_amount'];
        $this->discount_id     = $discount->id;

        $this->success(trans('order.discount_applied_successfully', [
            'amount' => number_format($result['discount_amount']),
        ]));
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
            'users'              => User::where('type', UserTypeEnum::USER->value)->get()->map(fn (User $user) => ['label' => $user->full_name, 'value' => $user->id]),
            'courses'            => Course::query()->get()->map(fn (Course $course) => ['label' => $course->template->title ?? 'Course #' . $course->id, 'value' => $course->id]),
            'itemableTypes'      => [
                ['label' => 'Course', 'value' => Course::class],
                ['label' => 'Enrollment', 'value' => Enrollment::class],
            ],
        ]);
    }
}
