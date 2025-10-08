<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\DiscountTypeEnum;
use App\Models\Discount;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DiscountFactory extends Factory
{
    protected $model = Discount::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement(DiscountTypeEnum::cases());

        return [
            'code'                => strtoupper($this->faker->unique()->bothify('????##')),
            'type'                => $type->value,
            'value'               => $type === DiscountTypeEnum::PERCENTAGE
                ? $this->faker->numberBetween(5, 50)
                : $this->faker->numberBetween(10, 100),
            'user_id'             => $this->faker->boolean(20) ? User::factory() : null,
            'min_order_amount'    => $this->faker->randomElement([0, 50, 100, 200]),
            'max_discount_amount' => $type === DiscountTypeEnum::PERCENTAGE
                ? $this->faker->randomElement([null, 50, 100, 200])
                : null,
            'usage_limit'         => $this->faker->randomElement([null, 10, 50, 100]),
            'usage_per_user'      => $this->faker->randomElement([1, 2, 3, 5]),
            'used_count'          => 0,
            'starts_at'           => $this->faker->boolean(70) ? now()->subDays($this->faker->numberBetween(1, 30)) : null,
            'expires_at'          => $this->faker->boolean(70) ? now()->addDays($this->faker->numberBetween(30, 90)) : null,
            'is_active'           => $this->faker->boolean(80),
            'description'         => $this->faker->boolean(70) ? $this->faker->sentence() : null,
        ];
    }

    /** Create an active discount */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active'  => true,
            'starts_at'  => now()->subDays(5),
            'expires_at' => now()->addDays(30),
        ]);
    }

    /** Create an expired discount */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'starts_at'  => now()->subDays(30),
            'expires_at' => now()->subDays(1),
        ]);
    }

    /** Create a percentage discount */
    public function percentage(): static
    {
        return $this->state(fn (array $attributes) => [
            'type'  => DiscountTypeEnum::PERCENTAGE->value,
            'value' => $this->faker->numberBetween(10, 30),
        ]);
    }

    /** Create a fixed amount discount */
    public function fixedAmount(): static
    {
        return $this->state(fn (array $attributes) => [
            'type'                => DiscountTypeEnum::AMOUNT->value,
            'value'               => $this->faker->numberBetween(20, 100),
            'max_discount_amount' => null,
        ]);
    }

    /** Create a user-specific discount */
    public function forUser(?int $userId = null): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $userId ?? User::factory(),
        ]);
    }

    /** Create a global discount */
    public function global(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => null,
        ]);
    }
}
