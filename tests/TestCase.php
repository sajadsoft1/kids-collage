<?php

declare(strict_types=1);

namespace Tests;

use App\Http\Middleware\CheckLicenceMiddleware;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Exclude middleware for all tests
        $this->withoutMiddleware(CheckLicenceMiddleware::class);
        Artisan::call('db:seed', [
            '--class' => 'InitSeeder',
            '--force' => true,
        ]);
    }
}
