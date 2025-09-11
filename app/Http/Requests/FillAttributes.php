<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Trait FillAttributes
 *
 * Provides utility methods for form request handling, including:
 * - Automatic attribute generation for validation messages
 * - String to array conversion for multipart/form-data handling
 * - Boolean conversion for form inputs
 * - JSON string to object conversion
 *
 * @package App\Http\Requests
 */

trait FillAttributes
{
    /**
     * Get custom attribute names for validation messages
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return $this->generateAttributes();
    }

    /**
     * Generate custom attribute names for nested validation rules
     *
     * @return array<string, string>
     */
    public function generateAttributes(): array
    {
        return collect($this->rules())->filter(function ($item, $key) {
            return Str::contains($key, '.');
        })->map(function ($item, $key) {
            $key = 'validation.attributes.' . last(explode('.', $key));

            return trans($key);
        })->toArray();
    }

    /**
     * Convert comma-separated string values to arrays
     * Useful when mediaType is multipart/form-data where arrays become strings
     *
     * @param array<string, string> $rules Array with field names as keys and comma-separated values
     */
    public function convertStringToArray(array $rules): void
    {
        foreach ($rules as $key => $value) {
            if (is_string($value)) {
                $this->merge([$key => explode(',', $value)]);
            }
        }
    }

    /**
     * Convert JSON string arrays to arrays of objects
     * Handles nested JSON decoding for complex form data
     *
     * @param array<string, string> $rules Array with field names as keys and JSON string values
     */
    public function convertStringToArrayOfObject(array $rules): void
    {
        foreach ($rules as $key => $value) {
            if (is_string($value)) {
                try {
                    $rows = json_decode($value, true, 512, JSON_THROW_ON_ERROR);

                    // Iterate through the array and decode any string elements
                    foreach ($rows as $index => $val) {
                        if (is_string($val)) {
                            $rows[$index] = json_decode($val, true, 512, JSON_THROW_ON_ERROR);
                        }
                    }

                    $this->merge([$key => $rows]);
                } catch (Exception $e) {
                    Log::log('error', $e->getMessage());
                }
            }
        }
    }

    /**
     * Convert JSON string to object/array
     *
     * @param array<string, string> $rules Array with field names as keys and JSON string values
     */
    public function convertStringToObject(array $rules): void
    {
        foreach ($rules as $key => $value) {
            if (is_string($value)) {
                try {
                    $rows = json_decode($value, true, 512, JSON_THROW_ON_ERROR);
                    $this->merge([$key => $rows]);
                } catch (Exception $e) {
                    Log::log('error', $e->getMessage());
                }
            }
        }
    }

    /**
     * Convert various string representations to boolean values
     * Handles 'on'/'off', 'true'/'false', '1'/'0' values from forms
     *
     * @param array<string, mixed> $fields Array of field names and their values to convert
     */
    protected function convertOnToBoolean(array $fields): void
    {
        foreach ($fields as $key => $value) {
            if (in_array($value, ['on', 'true', '1'], true)) {
                $this->merge([$key => true]);
            } elseif (in_array($value, ['off', 'false', '0'], true) || empty($value)) {
                $this->merge([$key => false]);
            }
        }
    }
}
