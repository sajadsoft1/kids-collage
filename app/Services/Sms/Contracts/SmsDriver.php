<?php

declare(strict_types=1);

namespace App\Services\Sms\Contracts;

/**
 * Contract for SMS drivers.
 */
interface SmsDriver
{
    /** @param array{token?:string,username?:string,password?:string,sender?:string}|array $config */
    public function __construct(array $config = []);

    /** Send an SMS message to a single phone number. */
    public function send(string $phoneNumber, string $message): void;

    /** Send to a single recipient (alias for send, but explicit). */
    public function sendTo(string $phoneNumber, string $message): void;

    /** Send the same message to multiple recipients. */
    public function sendToGroup(array $phoneNumbers, string $message): void;

    /** Send using a template with inputs (e.g., "Hello {name}"). */
    public function sendTemplate(string $phoneNumber, string $template, array $inputs = []): void;

    /** Send to many recipients using a template with inputs. */
    public function sendTemplateToGroup(array $phoneNumbers, string $template, array $inputs = []): void;
}
