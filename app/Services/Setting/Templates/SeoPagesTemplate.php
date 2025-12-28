<?php

declare(strict_types=1);

namespace App\Services\Setting\Templates;

use App\Enums\SettingEnum;
use App\Exceptions\ValidationException;
use App\Models\Setting;

class SeoPagesTemplate extends BaseTemplate
{
    public function __construct()
    {
        $this->settingEnum = SettingEnum::SEO_PAGES;
    }

    public function template(Setting $setting): array
    {
        $this->setting = $setting;

        $robotsOptions = [
            $this->option(trans('core.index'), 'index'),
            $this->option(trans('core.noindex'), 'noindex'),
        ];

        $locales = config('app.supported_locales', ['fa', 'en']);

        $defaultLocalized = function (string $key) use ($setting, $locales) {
            $value = $setting->extra_attributes->get($key, []);

            if ( ! is_array($value)) {
                // If stored as string previously, convert to all locales the same string
                $filled = array_fill_keys($locales, (string) $value);

                return $filled;
            }

            // Ensure all supported locales exist
            foreach ($locales as $locale) {
                if ( ! array_key_exists($locale, $value)) {
                    $value[$locale] = '';
                }
            }

            return $value;
        };

        return [
            $this->recordComplex('blog', [
                $this->record('title', self::TEXT, default_value: array_fill_keys($locales, ''), value: $defaultLocalized('blog.title')),
                $this->record('meta_description', self::TEXT, default_value: array_fill_keys($locales, ''), value: $defaultLocalized('blog.meta_description')),
                $this->record('meta_keywords', self::TEXT, default_value: array_fill_keys($locales, ''), value: $defaultLocalized('blog.meta_keywords')),
                $this->record('robots', self::SELECT, default_value: 'index', options: $robotsOptions, value: $setting->extra_attributes->get('blog.robots', 'index')),
                $this->record('canonical', self::TEXT, default_value: '', value: $setting->extra_attributes->get('blog.canonical')),
            ]),

            $this->recordComplex('news', [
                $this->record('title', self::TEXT, default_value: array_fill_keys($locales, ''), value: $defaultLocalized('news.title')),
                $this->record('meta_description', self::TEXT, default_value: array_fill_keys($locales, ''), value: $defaultLocalized('news.meta_description')),
                $this->record('meta_keywords', self::TEXT, default_value: array_fill_keys($locales, ''), value: $defaultLocalized('news.meta_keywords')),
                $this->record('robots', self::SELECT, default_value: 'index', options: $robotsOptions, value: $setting->extra_attributes->get('news.robots', 'index')),
                $this->record('canonical', self::TEXT, default_value: '', value: $setting->extra_attributes->get('news.canonical')),
            ]),
        ];
    }

    /** @throws ValidationException */
    public function validate(Setting $setting, array $payload = []): array
    {
        $locales = config('app.supported_locales', ['fa', 'en']);

        $rules = [
            'blog' => ['required', 'array'],
            'blog.title' => ['nullable', 'array'],
            'blog.title.*' => ['nullable', 'string', 'max:120'],
            'blog.meta_description' => ['nullable', 'array'],
            'blog.meta_description.*' => ['nullable', 'string', 'max:320'],
            'blog.meta_keywords' => ['nullable', 'array'],
            'blog.meta_keywords.*' => ['nullable', 'string', 'max:255'],
            'blog.robots' => ['nullable', 'in:index,noindex'],
            'blog.canonical' => ['nullable', 'string', 'max:255'],

            'news' => ['required', 'array'],
            'news.title' => ['nullable', 'array'],
            'news.title.*' => ['nullable', 'string', 'max:120'],
            'news.meta_description' => ['nullable', 'array'],
            'news.meta_description.*' => ['nullable', 'string', 'max:320'],
            'news.meta_keywords' => ['nullable', 'array'],
            'news.meta_keywords.*' => ['nullable', 'string', 'max:255'],
            'news.robots' => ['nullable', 'in:index,noindex'],
            'news.canonical' => ['nullable', 'string', 'max:255'],
        ];

        // Ensure payload arrays include all supported locales (fill missing with empty strings)
        foreach (['blog', 'news'] as $group) {
            foreach (['title', 'meta_description', 'meta_keywords'] as $field) {
                if (isset($payload[$group][$field]) && is_array($payload[$group][$field])) {
                    foreach ($locales as $locale) {
                        $payload[$group][$field][$locale] ??= '';
                    }
                }
            }
        }

        return $this->makeValidator($payload, $rules, [], [
            'blog.title' => trans('setting.configs.seo_pages.items.blog_title.label'),
            'news.title' => trans('setting.configs.seo_pages.items.news_title.label'),
        ]);
    }

    public function seed(SettingEnum $enum): void
    {
        $setting = Setting::firstOrCreate([
            'key' => $enum->value,
        ], [
            'permissions' => ['Shared.Admin'],
        ]);

        $locales = config('app.supported_locales', ['fa', 'en']);

        $defaults = [
            'blog' => [
                'title' => array_fill_keys($locales, ''),
                'meta_description' => array_fill_keys($locales, ''),
                'meta_keywords' => array_fill_keys($locales, ''),
                'robots' => 'index',
                'canonical' => '',
            ],
            'news' => [
                'title' => array_fill_keys($locales, ''),
                'meta_description' => array_fill_keys($locales, ''),
                'meta_keywords' => array_fill_keys($locales, ''),
                'robots' => 'index',
                'canonical' => '',
            ],
        ];

        foreach ($defaults as $group => $values) {
            foreach ($values as $key => $value) {
                $existing = $setting->extra_attributes->get("{$group}.{$key}", null);

                if (is_array($value)) {
                    // If expecting an array per-locale, overwrite if missing or not an array
                    if (is_null($existing) || ! is_array($existing)) {
                        $setting->extra_attributes->set("{$group}.{$key}", $value);
                    }
                } else {
                    // For scalar defaults, set only if not present
                    if (is_null($existing)) {
                        $setting->extra_attributes->set("{$group}.{$key}", $value);
                    }
                }
            }
        }

        $setting->save();
    }
}
