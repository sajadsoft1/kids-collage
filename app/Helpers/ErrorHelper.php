<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Http\Exceptions\HttpResponseException;

class ErrorHelper
{
    /** @throws HttpResponseException */
    public static function ValidationError(array $errors = [], ?string $message = null): void
    {
        // Ensure $errors is not empty
        if (empty($errors)) {
            throw new HttpResponseException(response()->json([
                'message' => $message ?? 'Validation failed.',
                'errors' => [],
            ], 422));
        }

        // Flatten the error array if it's in the format you provided.
        $flattenedErrors = [];
        foreach ($errors as $error) {
            $flattenedErrors = array_merge($flattenedErrors, $error);
        }

        // Ensure the first error exists and has a value
        $firstError = reset($flattenedErrors);
        $errorMessage = $message ?? ($firstError ?? 'Validation failed.');

        throw new HttpResponseException(response()->json([
            'message' => $errorMessage,
            'errors' => $flattenedErrors,
        ], 422));
    }

    public static function ValidationErrorIf(bool $condition, array $errors = [], ?string $message = null): void
    {
        if ($condition) {
            self::ValidationError($errors, $message);
        }
    }
}
