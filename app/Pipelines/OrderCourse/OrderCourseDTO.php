<?php

declare(strict_types=1);

namespace App\Pipelines\OrderCourse;

use App\Models\Course;
use App\Models\Discount;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\User;
use App\Traits\HasMessageBag;
use App\Traits\HasPayload;
use Illuminate\Support\Collection;

class OrderCourseDTO
{
    use HasMessageBag, HasPayload;

    private ?User       $user           = null;
    private ?Order      $order          = null;
    private ?Course     $course         = null;
    private ?Discount   $discount       = null;
    private ?Enrollment $enrollment     = null;
    private Collection  $items;
    private Collection  $payments;
    private float       $pureAmount     = 0;
    private float       $discountAmount = 0;
    private float       $totalAmount    = 0;

    public function __construct($payload = [])
    {
        $this->payload = $payload;
        $this->items = collect($payload['items'] ?? []);
        $this->payments = collect($payload['payments'] ?? []);
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): void
    {
        $this->order = $order;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): void
    {
        $this->course = $course;
    }

    public function getDiscount(): ?Discount
    {
        return $this->discount;
    }

    public function setDiscount(?Discount $discount): void
    {
        $this->discount = $discount;
    }

    public function getEnrollment(): ?Enrollment
    {
        return $this->enrollment;
    }

    public function setEnrollment(?Enrollment $enrollment): void
    {
        $this->enrollment = $enrollment;
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function setItems(Collection $items): void
    {
        $this->items = $items;
    }

    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function setPayments(Collection $payments): void
    {
        $this->payments = $payments;
    }

    public function getPureAmount(): float
    {
        return $this->pureAmount;
    }

    public function setPureAmount(float $pureAmount): void
    {
        $this->pureAmount = $pureAmount;
    }

    public function getDiscountAmount(): float
    {
        return $this->discountAmount;
    }

    public function setDiscountAmount(float $discountAmount): void
    {
        $this->discountAmount = $discountAmount;
    }

    public function getTotalAmount(): float
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(float $totalAmount): void
    {
        $this->totalAmount = $totalAmount;
    }
}
