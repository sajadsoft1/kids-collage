<?php

declare(strict_types=1);

namespace App\Traits;

trait HasMessageBag
{
    private array $messages = []; // template ['message' => 'message', 'order' => 1]

    public function setMessages(array $messages): static
    {
        $this->messages = $messages;

        return $this;
    }

    public function addMessage(string $message, int $order = 1): static
    {
        $this->messages[] = ['message' => $message, 'order' => $order];

        return $this;
    }

    public function getMessages(): array
    {
        return $this->messages;
    }

    public function hasMessages(): bool
    {
        return count($this->messages) > 0;
    }

    public function clearMessages(): void
    {
        $this->messages = [];
    }

    public function getHighOrderedMessage(): string
    {
        $message = '';
        $order   = 0;
        foreach ($this->messages as $msg) {
            if ($msg['order'] > $order) {
                $message = $msg['message'];
                $order   = $msg['order'];
            }
        }

        return $message;
    }
}
