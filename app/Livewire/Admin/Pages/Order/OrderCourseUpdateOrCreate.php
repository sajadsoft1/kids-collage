<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Order;

use App\Actions\Order\StoreOrderCourseAction;
use App\Actions\Order\UpdateOrderCourseAction;
use App\Enums\OrderStatusEnum;
use App\Enums\PaymentStatusEnum;
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

class OrderCourseUpdateOrCreate extends Component
{
    use Toast;

    public Order  $model;
    public string $accordion_group;
    public int    $user_id       = 0;
    public string $status        = OrderStatusEnum::PENDING->value;
    public string $note          = '';
    public string $discount_code = '';
    public ?int          $discount_id = null;
    public Discount|null $discount    = null;
    public array         $items       = [];
    public array  $payments      = [];

    public function mount(Order $order): void
    {
        $this->model = $order->load(['discount', 'items', 'payments']);
        if ($this->model->id) {
            $this->user_id = $this->model->user_id;
            $this->status = $this->model->status instanceof OrderStatusEnum
                ? $this->model->status->value
                : $this->model->status;
            $this->note = $this->model->note ?? '';
            $this->discount = $this->model->discount;
            $this->discount_code = $this->discount->code ?? '';
            $this->discount_id = $this->discount->id ?? null;
            // Load items
            $this->items = $this->model->items->map(function (Course $item) {
                return [
                    'id'            => $item->id,
                    'itemable_type' => $item->itemable_type,
                    'itemable_id'   => $item->itemable_id,
                    'price'         => $item->price,
                    'session_count' => $item->sessions()->count(),
                    'teacher'       => $item->teacher->full_name,
                    'quantity'      => $item->quantity,
                ];
            })->toArray();

            // Load payments
            $this->payments = $this->model->payments->map(function ($payment) {
                return [
                    'id'               => $payment->id,
                    'amount'           => $payment->amount,
                    'payment_type'     => $payment->type->value,
                    'scheduled_date'   => $payment->scheduled_date?->toDateString(),
                    'paid_at'          => $payment->paid_at?->toDateString(),
                    'status'           => $payment->status->value ?? PaymentStatusEnum::PENDING->value,
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
                    'session_count' => 0,
                    'teacher'       => '-',
                    'quantity'      => 1,
                ],
            ];

            // Initialize with one empty payment
            $this->payments = [
                [
                    'id'               => null,
                    'amount'           => 0,
                    'payment_type'     => PaymentTypeEnum::CASH->value,
                    'scheduled_date'   => now()->toDateString(),
                    'paid_at'          => null,
                    'status'           => PAymentStatusEnum::PENDING->value,
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

    public function addItem(): void
    {
        $this->items[] = [
            'id'            => null,
            'itemable_type' => Course::class,
            'itemable_id'   => 0,
            'price'         => 0,
            'session_count' => 0,
            'teacher'       => '-',
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
            'payment_type'     => PaymentTypeEnum::CASH->value,
            'scheduled_date'   => now()->toDateString(),
            'status'           => PAymentStatusEnum::PENDING->value,
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
            UpdateOrderCourseAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('order.model')]),
                redirectTo: route('admin.order.index')
            );
        } else {
            StoreOrderCourseAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('order.model')]),
                redirectTo: route('admin.order.index')
            );
        }
    }

    public function updatedItems($value, $field): void
    {
        // value like: 1
        // $field like: 0.itemable_id
        [$index, $name] = explode('.', $field);
        if ($name === 'itemable_id' && isset($this->items[$index]['itemable_type']) && $this->items[$index]['itemable_type'] === Course::class) {
            $course = Course::find($value);
            if ($course) {
                $this->items[$index]['price'] = $course->price;
                $this->items[$index]['session_count'] = $course->sessions()->count();
                $this->items[$index]['teacher'] = $course->teacher->full_name;
            } else {
                $this->items[$index]['price'] = 0;
                $this->items[$index]['session_count'] = 0;
                $this->items[$index]['teacher'] = '-';
            }
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
            $this->discount_id = null;
            $this->discount = null;
            $this->warning(trans('order.discount_code_required'));

            return;
        }

        // Validate required fields
        if (!$this->user_id || !collect($this->items)->where('itemable_type', Course::class)->where('itemable_id','!=',0)->count()) {
            $this->warning(trans('order.please_select_user_and_course'));

            return;
        }

        // Find discount by code
        $discount = Discount::where('code', trim($this->discount_code))->first();

        if (!$discount) {
            $this->discount_id = null;
            $this->discount = null;
            $this->error(trans('order.discount_not_found'));

            return;
        }

        // Get order amount (course price)
        $orderAmount = $this->payableAmount;

        if ($orderAmount <= 0) {
            $this->warning(trans('order.course_has_no_price'));

            return;
        }

        // Validate and calculate discount using model's method
        $result = $discount->validateAndCalculate($orderAmount, $this->user_id);

        if (!$result['success']) {
            $this->discount = null;
            $this->discount_id = null;
            $this->error($result['message']);

            return;
        }

        // Apply discount
        $this->discount_id = $discount->id;
        $this->discount = $discount;

        $this->success(trans('order.discount_applied_successfully', [
            'amount' => number_format($this->discountAmount),
        ]));
    }

    #[Computed]
    public function discountAmount(): float
    {
        return $this->discount?->calculateDiscount($this->payableAmount()) ?? 0.0;
    }

    #[Computed]
    public function payableAmount(): float
    {
        $total = (float) collect($this->items)->sum(fn($item) => $item['price'] * $item['quantity']);
        return max(0, $total - $this->discountAmount());
    }

    public function render(): View
    {
        return view('livewire.admin.pages.order.order-course-update-or-create', [
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
            'itemableTypes'      => [
                ['label' => 'Course', 'value' => Course::class],
                ['label' => 'Enrollment', 'value' => Enrollment::class],
            ],
        ]);
    }
}
