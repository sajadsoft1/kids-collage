<?php

declare(strict_types=1);

namespace App\Services\Sms\Drivers;

use App\Services\Sms\Contracts\AbstractSmsDriver;
use App\Services\Sms\Contracts\DeliveryReportFetcher;
use App\Services\Sms\Contracts\PingableSmsDriver;
use App\Services\Sms\Contracts\SmsDriver;
use App\Services\Sms\Exceptions\DriverConnectionException;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * KavenegarDriver - SMS driver implementation for Kavenegar API.
 *
 * @see https://kavenegar.com/rest.html
 */
class KavenegarDriver extends AbstractSmsDriver implements DeliveryReportFetcher, PingableSmsDriver, SmsDriver
{
    /** @var array{token?:string,sender?:string} */
    public array $config;

    protected Client $client;

    public function __construct(array $config = [])
    {
        $this->config = $config;
        $this->client = new Client([
            'base_uri' => 'https://api.kavenegar.com/v1/',
            'timeout' => 30.0,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
        ]);
    }

    /**
     * Send SMS to a single recipient.
     *
     * @param string $phoneNumber Recipient phone number
     * @param string $message     Message content
     *
     * @throws DriverConnectionException
     */
    public function send(string $phoneNumber, string $message): void
    {
        if (empty($this->config['token'])) {
            throw new DriverConnectionException('Kavenegar token not set.');
        }

        $url = $this->config['token'] . '/sms/send.json';

        try {
            $response = $this->client->post($url, [
                'form_params' => [
                    'receptor' => $phoneNumber,
                    'message' => $message,
                    'sender' => $this->config['sender'] ?? null,
                ],
            ]);

            $statusCode = $response->getStatusCode();
            $body = (string) $response->getBody();
            $data = json_decode($body, true);

            if ($statusCode !== 200) {
                $errorMessage = $this->parseErrorResponse($data);
                Log::error('Kavenegar send failed', [
                    'phone' => $phoneNumber,
                    'status' => $statusCode,
                    'response' => $body,
                ]);

                throw new DriverConnectionException("Kavenegar API error: {$errorMessage}");
            }

            // Check if return status is successful (200)
            if (isset($data['return']['status']) && $data['return']['status'] !== 200) {
                $errorMessage = $data['return']['message'] ?? 'Unknown error';

                throw new DriverConnectionException("Kavenegar API error: {$errorMessage}");
            }
        } catch (DriverConnectionException $e) {
            throw $e;
        } catch (Throwable $e) {
            Log::error('Kavenegar connection error', [
                'phone' => $phoneNumber,
                'exception' => $e->getMessage(),
            ]);

            throw new DriverConnectionException('Failed to connect to Kavenegar API: ' . $e->getMessage(), previous: $e);
        }
    }

    public function sendTo(string $phoneNumber, string $message): void
    {
        $this->send($phoneNumber, $message);
    }

    /**
     * Send SMS to multiple recipients (array method).
     *
     * @param array<string> $phoneNumbers Array of recipient phone numbers
     * @param string        $message      Message content
     */
    public function sendToGroup(array $phoneNumbers, string $message): void
    {
        if (empty($this->config['token'])) {
            throw new DriverConnectionException('Kavenegar token not set.');
        }

        $url = $this->config['token'] . '/sms/sendarray.json';

        try {
            $response = $this->client->post($url, [
                'form_params' => [
                    'receptor' => $phoneNumbers,
                    'message' => array_fill(0, count($phoneNumbers), $message),
                    'sender' => array_fill(0, count($phoneNumbers), $this->config['sender'] ?? ''),
                ],
            ]);

            $statusCode = $response->getStatusCode();
            $body = (string) $response->getBody();
            $data = json_decode($body, true);

            if ($statusCode !== 200) {
                $errorMessage = $this->parseErrorResponse($data);
                Log::error('Kavenegar sendToGroup failed', [
                    'phones' => $phoneNumbers,
                    'status' => $statusCode,
                    'response' => $body,
                ]);

                throw new DriverConnectionException("Kavenegar API error: {$errorMessage}");
            }

            if (isset($data['return']['status']) && $data['return']['status'] !== 200) {
                $errorMessage = $data['return']['message'] ?? 'Unknown error';

                throw new DriverConnectionException("Kavenegar API error: {$errorMessage}");
            }
        } catch (DriverConnectionException $e) {
            throw $e;
        } catch (Throwable $e) {
            Log::error('Kavenegar group connection error', [
                'phones' => $phoneNumbers,
                'exception' => $e->getMessage(),
            ]);

            throw new DriverConnectionException('Failed to connect to Kavenegar API: ' . $e->getMessage(), previous: $e);
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

    /**
     * Check if the driver can connect to Kavenegar API.
     *
     * @return bool True if credentials are valid
     */
    public function ping(): bool
    {
        if (empty($this->config['token'])) {
            return false;
        }

        try {
            $url = $this->config['token'] . '/account/info.json';

            $response = $this->client->get($url, ['timeout' => 10]);

            $statusCode = $response->getStatusCode();
            $body = (string) $response->getBody();
            $data = json_decode($body, true);

            return $statusCode === 200
                && isset($data['return']['status'])
                && $data['return']['status'] === 200;
        } catch (Exception $e) {
            Log::warning('Kavenegar ping failed', ['exception' => $e->getMessage()]);

            return false;
        }
    }

    /**
     * Fetch delivery report for a specific message.
     *
     * @param string $providerMessageId The message ID from Kavenegar
     *
     * @return array{status:string,delivered_at:?string,raw:?array}
     */
    public function fetchDeliveryReport(string $providerMessageId): array
    {
        if (empty($this->config['token'])) {
            return ['status' => 'unknown', 'delivered_at' => null, 'raw' => null];
        }

        try {
            $url = $this->config['token'] . '/sms/select.json';

            $response = $this->client->post($url, [
                'form_params' => [
                    'messageid' => $providerMessageId,
                ],
                'timeout' => 10,
            ]);

            $statusCode = $response->getStatusCode();
            $body = (string) $response->getBody();
            $data = json_decode($body, true);

            if ($statusCode !== 200) {
                Log::warning('Kavenegar delivery report failed', [
                    'message_id' => $providerMessageId,
                    'status' => $statusCode,
                ]);

                return ['status' => 'unknown', 'delivered_at' => null, 'raw' => null];
            }

            if (isset($data['entries'][0])) {
                $entry = $data['entries'][0];
                $status = $this->mapKavenegarStatus((int) ($entry['status'] ?? 0));

                return [
                    'status' => $status,
                    'delivered_at' => $status === 'delivered' ? now()->toIso8601String() : null,
                    'raw' => $entry,
                ];
            }

            return ['status' => 'unknown', 'delivered_at' => null, 'raw' => $data];
        } catch (Exception $e) {
            Log::warning('Kavenegar delivery report exception', [
                'message_id' => $providerMessageId,
                'exception' => $e->getMessage(),
            ]);

            return ['status' => 'unknown', 'delivered_at' => null, 'raw' => null];
        }
    }

    /**
     * Map Kavenegar status codes to standard statuses.
     *
     * @param int $statusCode Kavenegar status code
     *
     * @return string Standard status: pending, sent, delivered, failed
     */
    protected function mapKavenegarStatus(int $statusCode): string
    {
        return match ($statusCode) {
            1, 2 => 'pending',    // 1: در صف ارسال, 2: زمان‌بندی شده
            4, 5 => 'sent',       // 4: ارسال شده به مخابرات, 5: ارسال شده به مخابرات
            10 => 'delivered',  // 10: رسیده به گیرنده
            default => 'failed',  // Other codes indicate various failure states
        };
    }

    /**
     * Parse error response from Kavenegar API.
     *
     * @param array|null $data Response data
     *
     * @return string Error message
     */
    protected function parseErrorResponse(?array $data): string
    {
        if ( ! $data) {
            return 'Unknown error';
        }

        if (isset($data['return']['message'])) {
            return (string) $data['return']['message'];
        }

        if (isset($data['message'])) {
            return (string) $data['message'];
        }

        return 'Unknown error';
    }
}
