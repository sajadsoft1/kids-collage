<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Arr;

trait HasPayload
{
    private array $payload = [];
    
    public function setPayload(array $payload): static
    {
        $this->payload = $payload;

        return $this;
    }
    
    /**
     * You can use a dot key (like: product.title)
     *
     *
     * @return mixed|null
     */
    public function getFromPayload(string|int $key, mixed $default = null): mixed
    {
        return Arr::get($this->payload, $key) ?? $default;
    }
    
    /**
     * You can set value using dot (.)
     *
     *
     * @return $this
     */
    public function setPayloadVal(string|null|int $key, mixed $val): static
    {
        Arr::set($this->payload, $key, $val);

        return $this;
    }
    
    public function getPayload(): array
    {
        return $this->payload;
    }
    
    public function addToPayload(string $key, string $value): void
    {
        $this->payload[$key] = $value;
    }
    
    public function has(string|null|int $key): bool
    {
        return $this->getFromPayload($key) !== null;
    }
}
