<?php

declare(strict_types=1);

namespace App\Helpers;

class StringHelper
{
    public static function convertToClassName($input): string
    {
        $names = preg_split('/[_-]/', $input);
        $names = array_map('ucwords', $names);

        return implode('', $names);
    }

    public static function enNum($string): array|string
    {
        static $fromChar = [
            '۰',
            '۱',
            '۲',
            '۳',
            '۴',
            '۵',
            '۶',
            '۷',
            '۸',
            '۹',
            '٠',
            '١',
            '٢',
            '٣',
            '٤',
            '٥',
            '٦',
            '٧',
            '٨',
            '٩',
        ];
        static $num = [
            '0',
            '1',
            '2',
            '3',
            '4',
            '5',
            '6',
            '7',
            '8',
            '9',
            '0',
            '1',
            '2',
            '3',
            '4',
            '5',
            '6',
            '7',
            '8',
            '9',
        ];

        return str_replace($fromChar, $num, $string);
    }

    public static function slug($title, $separator = '-'): string
    {
        $title = trim($title);
        $title = mb_strtolower($title, 'UTF-8');

        $title = str_replace('‌', $separator, $title);

        $title = preg_replace(
            '/[^a-z0-9_\s\-اآؤئبپتثجچحخدذرزژسشصضطظعغفقكکگلمنوةيإأۀءهی۰۱۲۳۴۵۶۷۸۹٠١٢٣٤٥٦٧٨٩]/u',
            '',
            $title
        );

        $title = preg_replace('/[\s\-_]+/', ' ', $title);
        $title = preg_replace('/[\s_]/', $separator, $title);
        $title = trim($title, $separator);

        return $title;
    }

    public static function basename(string $path): string
    {
        // Normalize directory separators
        $normalizedPath = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $path);

        // Get the basename
        return basename($normalizedPath);
    }
}
