<?php

declare(strict_types=1);

namespace App\Services\Sms\Usage;

use App\Services\Sms\Contracts\PingableSmsDriver;
use App\Services\Sms\Contracts\SmsDriver;
use App\Services\Sms\Exceptions\DriverConnectionException;
use App\Services\Sms\Exceptions\InvalidDriverConfigurationException;
use Throwable;

/**
 * Validates driver configuration and optionally performs a lightweight connectivity check.
 */
class SmsUsageHandler
{
    /**
     * Ensure driver has the required credentials and is reachable.
     *
     * @throws InvalidDriverConfigurationException
     * @throws DriverConnectionException
     */
    public function ensureUsable(string $driverName, SmsDriver $driver): void
    {
        $requiredByDriver = match ($driverName) {
            'kavenegar' => ['token'],
            'smsir' => ['api_key'],
            'mellipayamac' => ['username', 'password'],
            default => [],
        };

        // Use reflection to read the config property if exposed by drivers in a conventional way
        $config = [];
        if (property_exists($driver, 'config')) {
            /** @var array $cfg */
            $cfg    = $driver->config;
            $config = is_array($cfg) ? $cfg : [];
        }

        foreach ($requiredByDriver as $key) {
            if ( ! array_key_exists($key, $config) || empty((string) $config[$key])) {
                throw new InvalidDriverConfigurationException("[{$driverName}] missing required credential: {$key}");
            }
        }

        // Optionally perform ping if driver supports it
        if ($driver instanceof PingableSmsDriver) {
            try {
                $ok = (bool) $driver->ping();
                if ($ok !== true) {
                    throw new DriverConnectionException("[{$driverName}] connectivity check failed.");
                }
            } catch (Throwable $e) {
                throw new DriverConnectionException("[{$driverName}] connectivity check error: " . $e->getMessage(), previous: $e);
            }
        }
    }
}
