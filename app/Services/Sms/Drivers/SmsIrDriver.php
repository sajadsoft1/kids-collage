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
 * SmsIrDriver - SMS driver implementation for SMS.ir API.
 *
 * @see https://sms.ir/rest-api
 */
class SmsIrDriver extends AbstractSmsDriver implements DeliveryReportFetcher, PingableSmsDriver, SmsDriver
{
    /** @var array{api_key?:string,sender?:string} */
    public array $config;

    protected Client $client;

    public function __construct(array $config = [])
    {
        $this->config = $config;
        $this->client = new Client([
            'base_uri' => 'https://api.sms.ir/v1/',
            'timeout' => 30.0,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
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
        if (empty($this->config['api_key'])) {
            throw new DriverConnectionException('SMS.ir API key not set.');
        }

        try {
            $response = $this->client->post('send/bulk', [
                'headers' => [
                    'X-API-KEY' => $this->config['api_key'],
                ],
                'json' => [
                    'lineNumber' => $this->config['sender'] ?? null,
                    'messageText' => $message,
                    'mobiles' => [$phoneNumber],
                    'sendDateTime' => null,
                ],
            ]);

            $statusCode = $response->getStatusCode();
            $body = (string) $response->getBody();
            $data = json_decode($body, true);

            if ($statusCode !== 200 && $statusCode !== 201) {
                $errorMessage = $this->parseErrorResponse($data);
                Log::error('SMS.ir send failed', [
                    'phone' => $phoneNumber,
                    'status' => $statusCode,
                    'response' => $body,
                ]);

                throw new DriverConnectionException("SMS.ir API error: {$errorMessage}");
            }

            // Check if response indicates success (status: 1)
            if (isset($data['status']) && $data['status'] !== 1) {
                $errorMessage = $this->getErrorMessage((int) $data['status']);

                throw new DriverConnectionException("SMS.ir API error: {$errorMessage}");
            }
        } catch (DriverConnectionException $e) {
            throw $e;
        } catch (Throwable $e) {
            Log::error('SMS.ir connection error', [
                'phone' => $phoneNumber,
                'exception' => $e->getMessage(),
            ]);

            throw new DriverConnectionException('Failed to connect to SMS.ir API: ' . $e->getMessage(), previous: $e);
        }
    }

    public function sendTo(string $phoneNumber, string $message): void
    {
        $this->send($phoneNumber, $message);
    }

    /**
     * Send SMS to multiple recipients (bulk method).
     *
     * @param array<string> $phoneNumbers Array of recipient phone numbers
     * @param string        $message      Message content
     */
    public function sendToGroup(array $phoneNumbers, string $message): void
    {
        if (empty($this->config['api_key'])) {
            throw new DriverConnectionException('SMS.ir API key not set.');
        }

        try {
            $response = $this->client->post('send/bulk', [
                'headers' => [
                    'X-API-KEY' => $this->config['api_key'],
                ],
                'json' => [
                    'lineNumber' => $this->config['sender'] ?? null,
                    'messageText' => $message,
                    'mobiles' => $phoneNumbers,
                    'sendDateTime' => null,
                ],
            ]);

            $statusCode = $response->getStatusCode();
            $body = (string) $response->getBody();
            $data = json_decode($body, true);

            if ($statusCode !== 200 && $statusCode !== 201) {
                $errorMessage = $this->parseErrorResponse($data);
                Log::error('SMS.ir sendToGroup failed', [
                    'phones' => $phoneNumbers,
                    'status' => $statusCode,
                    'response' => $body,
                ]);

                throw new DriverConnectionException("SMS.ir API error: {$errorMessage}");
            }

            if (isset($data['status']) && $data['status'] !== 1) {
                $errorMessage = $this->getErrorMessage((int) $data['status']);

                throw new DriverConnectionException("SMS.ir API error: {$errorMessage}");
            }
        } catch (DriverConnectionException $e) {
            throw $e;
        } catch (Throwable $e) {
            Log::error('SMS.ir group connection error', [
                'phones' => $phoneNumbers,
                'exception' => $e->getMessage(),
            ]);

            throw new DriverConnectionException('Failed to connect to SMS.ir API: ' . $e->getMessage(), previous: $e);
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
     * Check if the driver can connect to SMS.ir API.
     *
     * @return bool True if credentials are valid
     */
    public function ping(): bool
    {
        if (empty($this->config['api_key'])) {
            return false;
        }

        try {
            $response = $this->client->get('credit/get', [
                'headers' => [
                    'X-API-KEY' => $this->config['api_key'],
                ],
                'timeout' => 10,
            ]);

            $statusCode = $response->getStatusCode();
            $body = (string) $response->getBody();
            $data = json_decode($body, true);

            return $statusCode === 200
                && isset($data['status'])
                && $data['status'] === 1;
        } catch (Exception $e) {
            Log::warning('SMS.ir ping failed', ['exception' => $e->getMessage()]);

            return false;
        }
    }

    /**
     * Fetch delivery report for a specific message.
     *
     * @param string $providerMessageId The message ID from SMS.ir
     *
     * @return array{status:string,delivered_at:?string,raw:?array}
     */
    public function fetchDeliveryReport(string $providerMessageId): array
    {
        if (empty($this->config['api_key'])) {
            return ['status' => 'unknown', 'delivered_at' => null, 'raw' => null];
        }

        try {
            $response = $this->client->get('send/live/' . $providerMessageId, [
                'headers' => [
                    'X-API-KEY' => $this->config['api_key'],
                ],
                'timeout' => 10,
            ]);

            $statusCode = $response->getStatusCode();
            $body = (string) $response->getBody();
            $data = json_decode($body, true);

            if ($statusCode !== 200) {
                Log::warning('SMS.ir delivery report failed', [
                    'message_id' => $providerMessageId,
                    'status' => $statusCode,
                ]);

                return ['status' => 'unknown', 'delivered_at' => null, 'raw' => null];
            }

            if (isset($data['data']['deliveryState'])) {
                $status = $this->mapSmsIrStatus((int) $data['data']['deliveryState']);

                return [
                    'status' => $status,
                    'delivered_at' => $status === 'delivered' ? now()->toIso8601String() : null,
                    'raw' => $data['data'],
                ];
            }

            return ['status' => 'unknown', 'delivered_at' => null, 'raw' => $data];
        } catch (Exception $e) {
            Log::warning('SMS.ir delivery report exception', [
                'message_id' => $providerMessageId,
                'exception' => $e->getMessage(),
            ]);

            return ['status' => 'unknown', 'delivered_at' => null, 'raw' => null];
        }
    }

    /**
     * Map SMS.ir status codes to standard statuses.
     *
     * @param int $statusCode SMS.ir delivery status code
     *
     * @return string Standard status: pending, sent, delivered, failed
     */
    protected function mapSmsIrStatus(int $statusCode): string
    {
        return match ($statusCode) {
            1 => 'delivered',  // رسیده به گوشی
            3 => 'pending',    // پردازش در مخابرات
            5 => 'sent',       // رسیده به مخابرات
            2, 4, 6, 7 => 'failed', // نرسیده به گوشی, نرسیده به مخابرات, خطا, لیست سیاه
            default => 'unknown',
        };
    }

    /**
     * Get error message from SMS.ir status code.
     *
     * @param int $statusCode SMS.ir status code
     *
     * @return string Error message
     */
    protected function getErrorMessage(int $statusCode): string
    {
        return match ($statusCode) {
            1 => 'عملیات با موفقیت انجام شد',
            0 => 'مشکلی در سامانه رخ داده است',
            10 => 'کلیدوب سرویس نامعتبر است',
            11 => 'کلید وب سرویس غیرفعال است',
            12 => 'کلیدوب‌ سرویس محدود به IP‌های تعریف شده می‌باشد',
            13 => 'حساب کاربری غیر فعال است',
            14 => 'حساب کاربری در حالت تعلیق قرار دارد',
            20 => 'تعداد درخواست بیشتر از حد مجاز است',
            101 => 'شماره خط نامعتبر میباشد',
            102 => 'اعتبار کافی نمیباشد',
            103 => 'درخواست شما دارای متن(های) خالی است',
            104 => 'درخواست شما دارای موبایل(های) نادرست است',
            105 => 'تعداد موبایل ها بیشتر از حد مجاز (100عدد) میباشد',
            106 => 'تعداد متن ها بیشتر ازحد مجاز (100عدد) میباشد',
            107 => 'لیست موبایل ها خالی میباشد',
            108 => 'لیست متن ها خالی میباشد',
            109 => 'زمان ارسال نامعتبر میباشد',
            110 => 'تعداد شماره موبایل ها و تعداد متن ها برابر نیستند',
            111 => 'با این شناسه ارسالی ثبت نشده است',
            112 => 'رکوردی برای حذف یافت نشد',
            113 => 'قالب یافت نشد',
            114 => 'طول رشته مقدار پارامتر، بیش از حد مجاز (25 کاراکتر) می‌باشد',
            115 => 'شماره موبایل(ها) در لیست سیاه سامانه می‌باشند',
            116 => 'نام پارامتر نمی‌تواند خالی باشد',
            117 => 'متن ارسال شده مورد تایید نمی‌باشد',
            118 => 'تعداد پیام ها بیش از حد مجاز می باشد',
            119 => 'به منظور استفاده از قالب‌ شخصی سازی شده پلن خود را ارتقا دهید',
            123 => 'خط ارسال‌کننده نیاز به فعال‌سازی دارد',
            default => 'خطای ناشناخته (کد: ' . $statusCode . ')',
        };
    }

    /**
     * Parse error response from SMS.ir API.
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

        if (isset($data['status']) && is_numeric($data['status'])) {
            return $this->getErrorMessage((int) $data['status']);
        }

        if (isset($data['message'])) {
            return (string) $data['message'];
        }

        return 'Unknown error';
    }
}
