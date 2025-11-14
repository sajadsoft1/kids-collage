<?php

declare(strict_types=1);

namespace App\Pipelines\Auth;

use App\Models\User;
use App\Traits\HasMessageBag;
use App\Traits\HasPayload;

class AuthDTO
{
    use HasMessageBag, HasPayload;

    private ?User $user = null;
    private ?string $token = null;

    public function __construct($payload = [])
    {
        $this->payload = $payload;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): void
    {
        $this->token = $token;
    }
}
