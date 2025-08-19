<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Exceptions\Trait\JsonRenderTrait;
use Exception;
use Illuminate\Contracts\Support\MessageBag;
use JsonException;
use Throwable;

class ValidationException extends Exception
{
    use JsonRenderTrait;

    /** @throws JsonException */
    public function __construct(MessageBag $messages, int $code = 422, ?Throwable $previous = null)
    {
        $message = json_encode($messages->getMessages(), JSON_THROW_ON_ERROR);
        parent::__construct($message, $code, $previous);
    }
}
