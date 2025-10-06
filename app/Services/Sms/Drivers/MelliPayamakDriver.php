<?php

declare(strict_types=1);

namespace App\Services\Sms\Drivers;

use App\Services\Sms\Contracts\AbstractSmsDriver;
use App\Services\Sms\Contracts\DeliveryReportFetcher;
use App\Services\Sms\Contracts\PingableSmsDriver;
use App\Services\Sms\Contracts\SmsDriver;
use App\Services\Sms\Exceptions\DriverConnectionException;

class MelliPayamakDriver extends AbstractSmsDriver implements DeliveryReportFetcher, PingableSmsDriver, SmsDriver
{
    /** @var array{username?:string,password?:string,sender?:string} */
    public array $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    public function send(string $phoneNumber, string $message): void
    {
        if (empty($this->config['username']) || empty($this->config['password'])) {
            throw new DriverConnectionException('MelliPayamak credentials not set.');
        }

        // no-op: integrate actual SDK/client here
    }

    public function sendTo(string $phoneNumber, string $message): void
    {
        $this->send($phoneNumber, $message);
    }

    public function sendToGroup(array $phoneNumbers, string $message): void
    {
        foreach ($phoneNumbers as $number) {
            $this->send((string) $number, $message);
        }
    }

    public function sendTemplate(string $phoneNumber, string $template, array $inputs = []): void
    {
        $message = $this->compileTemplate($template, $inputs);
        $this->send($phoneNumber, $message);
    }

    public function sendTemplateToGroup(array $phoneNumbers, string $template, array $inputs = []): void
    {
        $message = $this->compileTemplate($template, $inputs);
        $this->sendToGroup($phoneNumbers, $message);
    }

    // compileTemplate provided by AbstractSmsDriver

    public function fetchDeliveryReport(string $providerMessageId): array
    {
        return ['status' => 'delivered', 'raw' => null];
    }

    public function ping(): bool
    {
        return ! empty($this->config['username']) && ! empty($this->config['password']);
    }
}
