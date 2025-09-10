<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait FillAttributes
{
    public function attributes(): array
    {
        return $this->generateAttributes();
    }

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
     * When mediaType is multipart/form-data array of string or integer will be converted to string.
     *
     * @param array $rules like ['categories_id' => '1,2,3']
     */
    public function convertStringToArray(array $rules): void
    {
        foreach ($rules as $key => $value) {
            if (is_string($value)) {
                $this->merge([$key => explode(',', $value)]);
            }
        }
    }

    /** When 'mediaType' is 'multipart/form-data' array of an object will be converted to string.*/
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

    /** Convert multiple string fields to booleans.
     * @param array<string, mixed> $fields
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
