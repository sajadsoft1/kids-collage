<?php

declare(strict_types=1);

namespace App\Services\Sms;

use App\Enums\SmsSendStatusEnum;
use App\Enums\SmsTemplateEnum;
use App\Models\Sms as SmsModel;
use App\Services\Sms\Contracts\SmsDriver;
use App\Services\Sms\Exceptions\DriverConnectionException;
use App\Services\Sms\Exceptions\DriverNotAvailableException;
use App\Services\Sms\Exceptions\InvalidDriverConfigurationException;
use App\Services\Sms\Usage\SmsUsageHandler;
use Illuminate\Contracts\Container\Container;

/**
 * SmsManager is responsible for resolving SMS drivers, validating their configuration,
 * and providing failover when sending messages.
 */
class SmsManager
{
    public function __construct(
        protected Container $container,
        protected SmsUsageHandler $usageHandler
    ) {}

    /**
     * Send an SMS with automatic validation and failover.
     *
     * @throws DriverNotAvailableException
     */
    public function send(string $phoneNumber, string $message): void
    {
        $driverOrder = $this->getDriverOrder();

        $lastException = null;

        foreach ($driverOrder as $driverName) {
            try {
                $driver = $this->resolveDriver($driverName);

                // Validate configuration and connectivity before attempting send
                $this->usageHandler->ensureUsable($driverName, $driver);

                // create pending row before actual send
                $record = SmsModel::create([
                    'driver'   => $driverName,
                    'template' => null,
                    'inputs'   => null,
                    'phone'    => $phoneNumber,
                    'message'  => $message,
                    'status'   => SmsSendStatusEnum::PENDING,
                ]);

                $driver->send($phoneNumber, $message);

                $record->update([
                    'status' => SmsSendStatusEnum::SENT,
                ]);

                return; // success
            } catch (InvalidDriverConfigurationException|DriverConnectionException $e) {
                $lastException = $e; // try next driver
            }
        }

        throw new DriverNotAvailableException(
            'No SMS drivers are available to send the message.',
            previous: $lastException
        );
    }

    /** Failover wrapper for sendTo (single recipient). */
    public function sendTo(string $phoneNumber, string $message): void
    {
        $this->send($phoneNumber, $message);
    }

    // Removed sendOTP in favor of template-based methods

    /** Failover wrapper for group send. */
    public function sendToGroup(array $phoneNumbers, string $message): void
    {
        $driverOrder = $this->getDriverOrder();

        $lastException = null;

        foreach ($driverOrder as $driverName) {
            try {
                $driver = $this->resolveDriver($driverName);
                $this->usageHandler->ensureUsable($driverName, $driver);

                if (method_exists($driver, 'sendToGroup')) {
                    $driver->sendToGroup($phoneNumbers, $message);
                } else {
                    foreach ($phoneNumbers as $number) {
                        $driver->send((string) $number, $message);
                    }
                }

                return;
            } catch (InvalidDriverConfigurationException|DriverConnectionException $e) {
                $lastException = $e;
            }
        }

        throw new DriverNotAvailableException('No SMS drivers are available to send group messages.', previous: $lastException);
    }

    /** Failover wrapper for templated single send. */
    public function sendTemplate(string $phoneNumber, string|SmsTemplateEnum $template, array $inputs = []): void
    {
        $driverOrder   = $this->getDriverOrder();
        $lastException = null;

        foreach ($driverOrder as $driverName) {
            try {
                $driver = $this->resolveDriver($driverName);
                $this->usageHandler->ensureUsable($driverName, $driver);

                $tpl = $template instanceof SmsTemplateEnum ? $template->value : $template;

                $record = SmsModel::create([
                    'driver'   => $driverName,
                    'template' => $tpl,
                    'inputs'   => $inputs,
                    'phone'    => $phoneNumber,
                    'message'  => $this->compileTemplate($tpl, $inputs),
                    'status'   => SmsSendStatusEnum::PENDING,
                ]);

                if (method_exists($driver, 'sendTemplate')) {
                    $driver->sendTemplate($phoneNumber, $tpl, $inputs);
                } else {
                    $driver->send($phoneNumber, $this->compileTemplate($tpl, $inputs));
                }

                $record->update([
                    'status' => SmsSendStatusEnum::SENT,
                ]);

                return;
            } catch (InvalidDriverConfigurationException|DriverConnectionException $e) {
                $lastException = $e;
            }
        }

        throw new DriverNotAvailableException('No SMS drivers are available to send templated message.', previous: $lastException);
    }

    /** Failover wrapper for templated group send. */
    public function sendTemplateToGroup(array $phoneNumbers, string|SmsTemplateEnum $template, array $inputs = []): void
    {
        $driverOrder   = $this->getDriverOrder();
        $lastException = null;

        foreach ($driverOrder as $driverName) {
            try {
                $driver = $this->resolveDriver($driverName);
                $this->usageHandler->ensureUsable($driverName, $driver);

                $tpl = $template instanceof SmsTemplateEnum ? $template->value : $template;

                foreach ($phoneNumbers as $phoneNumber) {
                    $record = SmsModel::create([
                        'driver'   => $driverName,
                        'template' => $tpl,
                        'inputs'   => $inputs,
                        'phone'    => (string) $phoneNumber,
                        'message'  => $this->compileTemplate($tpl, $inputs),
                        'status'   => SmsSendStatusEnum::PENDING,
                    ]);

                    if (method_exists($driver, 'sendTemplateToGroup')) {
                        $driver->sendTemplateToGroup([$phoneNumber], $tpl, $inputs);
                    } else {
                        $driver->send((string) $phoneNumber, $this->compileTemplate($tpl, $inputs));
                    }

                    $record->update([
                        'status' => SmsSendStatusEnum::SENT,
                    ]);
                }

                return;
            } catch (InvalidDriverConfigurationException|DriverConnectionException $e) {
                $lastException = $e;
            }
        }

        throw new DriverNotAvailableException('No SMS drivers are available to send templated group messages.', previous: $lastException);
    }

    /** Simple placeholder replacement: {key} => value */
    protected function compileTemplate(string $template, array $variables = []): string
    {
        if (empty($variables)) {
            return $template;
        }
        $search  = [];
        $replace = [];
        foreach ($variables as $key => $value) {
            $search[]  = '{' . (string) $key . '}';
            $replace[] = (string) $value;
        }

        return str_replace($search, $replace, $template);
    }

    /** Resolve a driver instance by name. */
    public function resolveDriver(string $name): SmsDriver
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
        $default  = (string) config('sms.default');
        $failover = array_values(array_filter((array) config('sms.failover', [])));

        $order = array_values(array_unique(array_filter([$default, ...$failover])));

        if (empty($order)) {
            throw new InvalidDriverConfigurationException('No SMS driver configured.');
        }

        return $order;
    }
}
