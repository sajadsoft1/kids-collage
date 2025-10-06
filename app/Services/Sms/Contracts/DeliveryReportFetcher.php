<?php

declare(strict_types=1);

namespace App\Services\Sms\Contracts;

interface DeliveryReportFetcher
{
    /**
     * Fetch delivery report by provider message id. Return associative array with at least 'status'.
     *
     * @return array{status:string,delivered_at?:string,raw?:mixed}
     */
    public function fetchDeliveryReport(string $providerMessageId): array;
}
