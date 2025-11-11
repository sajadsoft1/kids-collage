<?php

declare(strict_types=1);

namespace App\Services\Sms\Contracts;

abstract class AbstractSmsDriver implements SmsDriver
{
    /** @var array<string,mixed> */
    public array $config = [];

    public function __construct(array $config = [])
    {
        $this->config = $config;
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
}
