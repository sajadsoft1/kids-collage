<?php

declare(strict_types=1);

namespace App\Services\Sms;

use App\Enums\SmsSendStatusEnum;
use App\Enums\SmsTemplateEnum;
use Karnoweb\LaravelNotification\Models\NotificationTemplate;
use App\Models\Sms as SmsModel;
use App\Models\User;
use App\Services\Sms\Contracts\DeliveryReportFetcher;
use App\Services\Sms\Contracts\SmsDriver;
use App\Services\Sms\Exceptions\DriverConnectionException;
use App\Services\Sms\Exceptions\DriverNotAvailableException;
use App\Services\Sms\Exceptions\InvalidDriverConfigurationException;
use App\Services\Sms\Usage\SmsUsageHandler;
use Illuminate\Contracts\Container\Container;
use Throwable;

/**
 * SmsManager - Fluent builder for sending SMS with failover support.
 *
 * Usage examples:
 * - SmsManager::instance()->otp(SmsTemplateEnum::LOGIN_OTP)->input('code', '1234')->number('09120000000')->send()
 * - SmsManager::instance()->message('Hello')->number('09120000000')->send()
 * - SmsManager::instance()->template(NotificationTemplate::find(1))->numbers(['09120000000'])->send()
 */
class SmsManager
{
    /** @var array<string> */
    protected array $toNumbers = [];

    /** @var array<int> */
    protected array $toUserIds = [];

    protected ?string $messageText = null;

    protected ?string $templateText = null;

    protected array $inputs = [];

    protected ?int $notificationTemplateId = null;

    public function __construct(
        protected Container $container,
        protected SmsUsageHandler $usageHandler
    ) {}

    /** Create a new fluent instance. */
    public static function instance(): self
    {
        return app(self::class);
    }

    /** Set OTP template with enum. */
    public function otp(SmsTemplateEnum $template): self
    {
        $this->templateText = $template->value;

        return $this;
    }

    /** Set simple message text. */
    public function message(string $message): self
    {
        $this->messageText = $message;

        return $this;
    }

    /** Set template from NotificationTemplate model (package: body as message). */
    public function template(NotificationTemplate $template): self
    {
        $this->notificationTemplateId = $template->id;
        $this->templateText = (string) $template->body;

        return $this;
    }

    /** Set template by NotificationTemplate ID. */
    public function templateId(int $templateId): self
    {
        $template = NotificationTemplate::findOrFail($templateId);

        return $this->template($template);
    }

    /** Add a single input (key-value). */
    public function input(string $key, string $value): self
    {
        $this->inputs[$key] = $value;

        return $this;
    }

    /** Merge multiple inputs. */
    public function inputs(array $inputs): self
    {
        $this->inputs = array_merge($this->inputs, $inputs);

        return $this;
    }

    /** Add a single phone number. */
    public function number(string $phone): self
    {
        $this->toNumbers[] = $phone;

        return $this;
    }

    /** Add multiple phone numbers. */
    public function numbers(array $phones): self
    {
        foreach ($phones as $phone) {
            $this->toNumbers[] = (string) $phone;
        }

        return $this;
    }

    /** Add a single user by ID. */
    public function user(int $userId): self
    {
        $this->toUserIds[] = $userId;

        return $this;
    }

    /** Add multiple users by IDs. */
    public function users(array $userIds): self
    {
        foreach ($userIds as $id) {
            $this->toUserIds[] = (int) $id;
        }

        return $this;
    }

    /** Send SMS to all configured recipients with failover. */
    public function send(): void
    {
        $targets = $this->resolveTargets();
        if (empty($targets)) {
            throw new InvalidDriverConfigurationException('No recipients provided.');
        }

        $message = $this->resolveMessage();
        if ($message === null) {
            throw new InvalidDriverConfigurationException('No message or template provided.');
        }

        $this->sendToTargets($targets, $message);
        $this->reset();
    }

    /** Check delivery status for pending SMS records (stub for integration). */
    public function checkStatus(): array
    {
        $targets = $this->resolveTargets();
        if (empty($targets)) {
            throw new InvalidDriverConfigurationException('No recipients provided to check status.');
        }

        $results = [];
        foreach ($targets as $phone) {
            // Find pending/sent SMS for this phone
            $smsRecords = SmsModel::where('phone', $phone)
                ->whereIn('status', [SmsSendStatusEnum::PENDING->value, SmsSendStatusEnum::SENT->value])
                ->get();

            foreach ($smsRecords as $record) {
                if (empty($record->provider_message_id)) {
                    continue;
                }

                try {
                    $driver = $this->resolveDriver((string) $record->driver);
                    if ($driver instanceof DeliveryReportFetcher) {
                        $report = $driver->fetchDeliveryReport((string) $record->provider_message_id);
                        $results[] = [
                            'phone' => $phone,
                            'provider_message_id' => $record->provider_message_id,
                            'status' => $report['status'] ?? 'unknown',
                        ];
                    }
                } catch (Throwable $e) {
                    $results[] = [
                        'phone' => $phone,
                        'provider_message_id' => $record->provider_message_id,
                        'error' => $e->getMessage(),
                    ];
                }
            }
        }

        $this->reset();

        return $results;
    }

    /**
     * Resolve all target phone numbers from numbers and user IDs.
     *
     * @return array<string>
     */
    protected function resolveTargets(): array
    {
        $phones = $this->toNumbers;

        if ( ! empty($this->toUserIds)) {
            $users = User::whereIn('id', $this->toUserIds)->get();
            foreach ($users as $user) {
                if ( ! empty($user->phone)) {
                    $phones[] = (string) $user->phone;
                }
            }
        }

        return array_values(array_unique($phones));
    }

    /** Resolve the final message text. */
    protected function resolveMessage(): ?string
    {
        if ($this->messageText !== null) {
            return $this->messageText;
        }

        if ($this->templateText !== null) {
            return $this->compileTemplate($this->templateText, $this->inputs);
        }

        return null;
    }

    /** Send to all targets with failover and record tracking. */
    protected function sendToTargets(array $phoneNumbers, string $message): void
    {
        $driverOrder = $this->getDriverOrder();
        $lastException = null;

        foreach ($driverOrder as $driverName) {
            try {
                $driver = $this->resolveDriver($driverName);
                $this->usageHandler->ensureUsable($driverName, $driver);

                foreach ($phoneNumbers as $phoneNumber) {
                    $record = SmsModel::create([
                        'driver' => $driverName,
                        'template' => $this->templateText,
                        'inputs' => $this->inputs,
                        'phone' => $phoneNumber,
                        'message' => $message,
                        'notification_template_id' => $this->notificationTemplateId,
                        'status' => SmsSendStatusEnum::PENDING,
                    ]);

                    $driver->send($phoneNumber, $message);

                    $record->update([
                        'status' => SmsSendStatusEnum::SENT,
                    ]);
                }

                return;
            } catch (InvalidDriverConfigurationException|DriverConnectionException $e) {
                $lastException = $e;
            }
        }

        throw new DriverNotAvailableException('No SMS drivers are available to send messages.', previous: $lastException);
    }

    /** Compile template by replacing {key} placeholders. */
    protected function compileTemplate(string $template, array $inputs = []): string
    {
        if (empty($inputs)) {
            return $template;
        }

        $search = [];
        $replace = [];
        foreach ($inputs as $key => $value) {
            $search[] = '{' . (string) $key . '}';
            $replace[] = (string) $value;
        }

        return str_replace($search, $replace, $template);
    }

    /** Resolve a driver instance by name. */
    protected function resolveDriver(string $name): SmsDriver
    {
        $driverConfig = (array) config('sms.drivers.' . $name);

        $class = $driverConfig['class'] ?? null;
        if ( ! is_string($class) || $class === '') {
            throw new InvalidDriverConfigurationException("Driver [{$name}] is missing a valid class.");
        }

        $credentials = (array) ($driverConfig['credentials'] ?? []);

        /** @var SmsDriver $driver */
        $driver = $this->container->make($class, ['config' => $credentials]);

        return $driver;
    }

    /**
     * Determine the driver order (default + failover list).
     *
     * @return list<string>
     */
    protected function getDriverOrder(): array
    {
        $default = (string) config('sms.default');
        $failover = array_values(array_filter((array) config('sms.failover', [])));

        $order = array_values(array_unique(array_filter([$default, ...$failover])));

        if (empty($order)) {
            throw new InvalidDriverConfigurationException('No SMS driver configured.');
        }

        return $order;
    }

    /** Reset builder state for reuse. */
    protected function reset(): void
    {
        $this->toNumbers = [];
        $this->toUserIds = [];
        $this->messageText = null;
        $this->templateText = null;
        $this->inputs = [];
        $this->notificationTemplateId = null;
    }
}
