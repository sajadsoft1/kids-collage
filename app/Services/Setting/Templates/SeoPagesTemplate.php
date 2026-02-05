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
                return array_fill_keys($locales, (string) $value);
            }

            // Ensure all supported locales exist
            foreach ($locales as $locale) {
                if ( ! array_key_exists($locale, $value)) {
                    $value[$locale] = '';
                }
            }

            return $value;
        };

        // Get default values from translations
        $pageDefaults = $this->getPageDefaults();

        return [
            $this->recordComplex('home', [
                $this->record('title', self::TEXT, $pageDefaults['home']['title'], value: $defaultLocalized('home.title'), ratio: $this->ratio(12, 12, 12)),
                $this->record('meta_description', self::TEXTAREA, $pageDefaults['home']['meta_description'], value: $defaultLocalized('home.meta_description'), ratio: $this->ratio(12, 12, 12)),
                $this->record('meta_keywords', self::TEXT, $pageDefaults['home']['meta_keywords'], value: $defaultLocalized('home.meta_keywords'), ratio: $this->ratio(12, 12, 12)),
                $this->record('robots', self::SELECT, 'index', options: $robotsOptions, value: $setting->extra_attributes->get('home.robots', 'index'), ratio: $this->ratio(12, 12, 12)),
                $this->record('canonical', self::TEXT, $pageDefaults['home']['canonical'], value: $setting->extra_attributes->get('home.canonical'), ratio: $this->ratio(12, 12, 12)),
            ]),

            $this->recordComplex('blog', [
                $this->record('title', self::TEXT, $pageDefaults['blog']['title'], value: $defaultLocalized('blog.title'), ratio: $this->ratio(12, 12, 12)),
                $this->record('meta_description', self::TEXTAREA, $pageDefaults['blog']['meta_description'], value: $defaultLocalized('blog.meta_description'), ratio: $this->ratio(12, 12, 12)),
                $this->record('meta_keywords', self::TEXT, $pageDefaults['blog']['meta_keywords'], value: $defaultLocalized('blog.meta_keywords'), ratio: $this->ratio(12, 12, 12)),
                $this->record('robots', self::SELECT, 'index', options: $robotsOptions, value: $setting->extra_attributes->get('blog.robots', 'index'), ratio: $this->ratio(12, 12, 12)),
                $this->record('canonical', self::TEXT, $pageDefaults['blog']['canonical'], value: $setting->extra_attributes->get('blog.canonical'), ratio: $this->ratio(12, 12, 12)),
            ]),

            $this->recordComplex('news', [
                $this->record('title', self::TEXT, $pageDefaults['news']['title'], value: $defaultLocalized('news.title'), ratio: $this->ratio(12, 12, 12)),
                $this->record('meta_description', self::TEXTAREA, $pageDefaults['news']['meta_description'], value: $defaultLocalized('news.meta_description'), ratio: $this->ratio(12, 12, 12)),
                $this->record('meta_keywords', self::TEXT, $pageDefaults['news']['meta_keywords'], value: $defaultLocalized('news.meta_keywords'), ratio: $this->ratio(12, 12, 12)),
                $this->record('robots', self::SELECT, 'index', options: $robotsOptions, value: $setting->extra_attributes->get('news.robots', 'index'), ratio: $this->ratio(12, 12, 12)),
                $this->record('canonical', self::TEXT, $pageDefaults['news']['canonical'], value: $setting->extra_attributes->get('news.canonical'), ratio: $this->ratio(12, 12, 12)),
            ]),

            $this->recordComplex('event', [
                $this->record('title', self::TEXT, $pageDefaults['event']['title'], value: $defaultLocalized('event.title'), ratio: $this->ratio(12, 12, 12)),
                $this->record('meta_description', self::TEXTAREA, $pageDefaults['event']['meta_description'], value: $defaultLocalized('event.meta_description'), ratio: $this->ratio(12, 12, 12)),
                $this->record('meta_keywords', self::TEXT, $pageDefaults['event']['meta_keywords'], value: $defaultLocalized('event.meta_keywords'), ratio: $this->ratio(12, 12, 12)),
                $this->record('robots', self::SELECT, 'index', options: $robotsOptions, value: $setting->extra_attributes->get('event.robots', 'index'), ratio: $this->ratio(12, 12, 12)),
                $this->record('canonical', self::TEXT, $pageDefaults['event']['canonical'], value: $setting->extra_attributes->get('event.canonical'), ratio: $this->ratio(12, 12, 12)),
            ]),

            $this->recordComplex('portfolio', [
                $this->record('title', self::TEXT, $pageDefaults['portfolio']['title'], value: $defaultLocalized('portfolio.title'), ratio: $this->ratio(12, 12, 12)),
                $this->record('meta_description', self::TEXTAREA, $pageDefaults['portfolio']['meta_description'], value: $defaultLocalized('portfolio.meta_description'), ratio: $this->ratio(12, 12, 12)),
                $this->record('meta_keywords', self::TEXT, $pageDefaults['portfolio']['meta_keywords'], value: $defaultLocalized('portfolio.meta_keywords'), ratio: $this->ratio(12, 12, 12)),
                $this->record('robots', self::SELECT, 'index', options: $robotsOptions, value: $setting->extra_attributes->get('portfolio.robots', 'index'), ratio: $this->ratio(12, 12, 12)),
                $this->record('canonical', self::TEXT, $pageDefaults['portfolio']['canonical'], value: $setting->extra_attributes->get('portfolio.canonical'), ratio: $this->ratio(12, 12, 12)),
            ]),

            $this->recordComplex('search', [
                $this->record('title', self::TEXT, $pageDefaults['search']['title'], value: $defaultLocalized('search.title'), ratio: $this->ratio(12, 12, 12)),
                $this->record('meta_description', self::TEXTAREA, $pageDefaults['search']['meta_description'], value: $defaultLocalized('search.meta_description'), ratio: $this->ratio(12, 12, 12)),
                $this->record('meta_keywords', self::TEXT, $pageDefaults['search']['meta_keywords'], value: $defaultLocalized('search.meta_keywords'), ratio: $this->ratio(12, 12, 12)),
                $this->record('robots', self::SELECT, 'index', options: $robotsOptions, value: $setting->extra_attributes->get('search.robots', 'index'), ratio: $this->ratio(12, 12, 12)),
                $this->record('canonical', self::TEXT, $pageDefaults['search']['canonical'], value: $setting->extra_attributes->get('search.canonical'), ratio: $this->ratio(12, 12, 12)),
            ]),

            $this->recordComplex('faq', [
                $this->record('title', self::TEXT, $pageDefaults['faq']['title'], value: $defaultLocalized('faq.title'), ratio: $this->ratio(12, 12, 12)),
                $this->record('meta_description', self::TEXTAREA, $pageDefaults['faq']['meta_description'], value: $defaultLocalized('faq.meta_description'), ratio: $this->ratio(12, 12, 12)),
                $this->record('meta_keywords', self::TEXT, $pageDefaults['faq']['meta_keywords'], value: $defaultLocalized('faq.meta_keywords'), ratio: $this->ratio(12, 12, 12)),
                $this->record('robots', self::SELECT, 'index', options: $robotsOptions, value: $setting->extra_attributes->get('faq.robots', 'index'), ratio: $this->ratio(12, 12, 12)),
                $this->record('canonical', self::TEXT, $pageDefaults['faq']['canonical'], value: $setting->extra_attributes->get('faq.canonical'), ratio: $this->ratio(12, 12, 12)),
            ]),

            $this->recordComplex('contact', [
                $this->record('title', self::TEXT, $pageDefaults['contact']['title'], value: $defaultLocalized('contact.title'), ratio: $this->ratio(12, 12, 12)),
                $this->record('meta_description', self::TEXTAREA, $pageDefaults['contact']['meta_description'], value: $defaultLocalized('contact.meta_description'), ratio: $this->ratio(12, 12, 12)),
                $this->record('meta_keywords', self::TEXT, $pageDefaults['contact']['meta_keywords'], value: $defaultLocalized('contact.meta_keywords'), ratio: $this->ratio(12, 12, 12)),
                $this->record('robots', self::SELECT, 'index', options: $robotsOptions, value: $setting->extra_attributes->get('contact.robots', 'index'), ratio: $this->ratio(12, 12, 12)),
                $this->record('canonical', self::TEXT, $pageDefaults['contact']['canonical'], value: $setting->extra_attributes->get('contact.canonical'), ratio: $this->ratio(12, 12, 12)),
            ]),

            $this->recordComplex('about', [
                $this->record('title', self::TEXT, $pageDefaults['about']['title'], value: $defaultLocalized('about.title'), ratio: $this->ratio(12, 12, 12)),
                $this->record('meta_description', self::TEXTAREA, $pageDefaults['about']['meta_description'], value: $defaultLocalized('about.meta_description'), ratio: $this->ratio(12, 12, 12)),
                $this->record('meta_keywords', self::TEXT, $pageDefaults['about']['meta_keywords'], value: $defaultLocalized('about.meta_keywords'), ratio: $this->ratio(12, 12, 12)),
                $this->record('robots', self::SELECT, 'index', options: $robotsOptions, value: $setting->extra_attributes->get('about.robots', 'index'), ratio: $this->ratio(12, 12, 12)),
                $this->record('canonical', self::TEXT, $pageDefaults['about']['canonical'], value: $setting->extra_attributes->get('about.canonical'), ratio: $this->ratio(12, 12, 12)),
            ]),
        ];
    }

    /**
     * Get default values for SEO pages from seoWebsite translations.
     *
     * @return array<string, array<string, mixed>>
     */
    private function getPageDefaults(): array
    {
        $locales = config('app.supported_locales', ['fa', 'en']);

        $defaults = [];
        foreach (['home', 'blog', 'news', 'event', 'portfolio', 'search', 'faq', 'contact', 'about'] as $page) {
            $defaults[$page] = [
                'title' => [],
                'meta_description' => [],
                'meta_keywords' => [],
                'canonical' => '',
            ];

            // Get translations for each locale
            foreach ($locales as $locale) {
                $seoDefaults = trans('seoWebsite', [], $locale);
                $defaults[$page]['title'][$locale] = $seoDefaults[$page]['title'] ?? '';
                $defaults[$page]['meta_description'][$locale] = $seoDefaults[$page]['meta_description'] ?? '';
                $defaults[$page]['meta_keywords'][$locale] = $seoDefaults[$page]['meta_keywords'] ?? '';
                if (empty($defaults[$page]['canonical'])) {
                    $defaults[$page]['canonical'] = $seoDefaults[$page]['canonical'] ?? '';
                }
            }
        }

        return $defaults;
    }

    /** @throws ValidationException */
    public function validate(Setting $setting, array $payload = []): array
    {
        $locales = config('app.supported_locales', ['fa', 'en']);

        $rules = [
            'home' => ['required', 'array'],
            'home.title' => ['nullable', 'array'],
            'home.title.*' => ['nullable', 'string', 'max:120'],
            'home.meta_description' => ['nullable', 'array'],
            'home.meta_description.*' => ['nullable', 'string', 'max:320'],
            'home.meta_keywords' => ['nullable', 'array'],
            'home.meta_keywords.*' => ['nullable', 'string', 'max:255'],
            'home.robots' => ['nullable', 'in:index,noindex'],
            'home.canonical' => ['nullable', 'string', 'max:255'],

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

            'event' => ['required', 'array'],
            'event.title' => ['nullable', 'array'],
            'event.title.*' => ['nullable', 'string', 'max:120'],
            'event.meta_description' => ['nullable', 'array'],
            'event.meta_description.*' => ['nullable', 'string', 'max:320'],
            'event.meta_keywords' => ['nullable', 'array'],
            'event.meta_keywords.*' => ['nullable', 'string', 'max:255'],
            'event.robots' => ['nullable', 'in:index,noindex'],
            'event.canonical' => ['nullable', 'string', 'max:255'],

            'portfolio' => ['required', 'array'],
            'portfolio.title' => ['nullable', 'array'],
            'portfolio.title.*' => ['nullable', 'string', 'max:120'],
            'portfolio.meta_description' => ['nullable', 'array'],
            'portfolio.meta_description.*' => ['nullable', 'string', 'max:320'],
            'portfolio.meta_keywords' => ['nullable', 'array'],
            'portfolio.meta_keywords.*' => ['nullable', 'string', 'max:255'],
            'portfolio.robots' => ['nullable', 'in:index,noindex'],
            'portfolio.canonical' => ['nullable', 'string', 'max:255'],

            'search' => ['required', 'array'],
            'search.title' => ['nullable', 'array'],
            'search.title.*' => ['nullable', 'string', 'max:120'],
            'search.meta_description' => ['nullable', 'array'],
            'search.meta_description.*' => ['nullable', 'string', 'max:320'],
            'search.meta_keywords' => ['nullable', 'array'],
            'search.meta_keywords.*' => ['nullable', 'string', 'max:255'],
            'search.robots' => ['nullable', 'in:index,noindex'],
            'search.canonical' => ['nullable', 'string', 'max:255'],

            'faq' => ['required', 'array'],
            'faq.title' => ['nullable', 'array'],
            'faq.title.*' => ['nullable', 'string', 'max:120'],
            'faq.meta_description' => ['nullable', 'array'],
            'faq.meta_description.*' => ['nullable', 'string', 'max:320'],
            'faq.meta_keywords' => ['nullable', 'array'],
            'faq.meta_keywords.*' => ['nullable', 'string', 'max:255'],
            'faq.robots' => ['nullable', 'in:index,noindex'],
            'faq.canonical' => ['nullable', 'string', 'max:255'],

            'contact' => ['required', 'array'],
            'contact.title' => ['nullable', 'array'],
            'contact.title.*' => ['nullable', 'string', 'max:120'],
            'contact.meta_description' => ['nullable', 'array'],
            'contact.meta_description.*' => ['nullable', 'string', 'max:320'],
            'contact.meta_keywords' => ['nullable', 'array'],
            'contact.meta_keywords.*' => ['nullable', 'string', 'max:255'],
            'contact.robots' => ['nullable', 'in:index,noindex'],
            'contact.canonical' => ['nullable', 'string', 'max:255'],

            'about' => ['required', 'array'],
            'about.title' => ['nullable', 'array'],
            'about.title.*' => ['nullable', 'string', 'max:120'],
            'about.meta_description' => ['nullable', 'array'],
            'about.meta_description.*' => ['nullable', 'string', 'max:320'],
            'about.meta_keywords' => ['nullable', 'array'],
            'about.meta_keywords.*' => ['nullable', 'string', 'max:255'],
            'about.robots' => ['nullable', 'in:index,noindex'],
            'about.canonical' => ['nullable', 'string', 'max:255'],
        ];

        // Ensure payload arrays include all supported locales (fill missing with empty strings)
        foreach (['home', 'blog', 'news', 'event', 'portfolio', 'search', 'faq', 'contact', 'about'] as $group) {
            foreach (['title', 'meta_description', 'meta_keywords'] as $field) {
                if (isset($payload[$group][$field]) && is_array($payload[$group][$field])) {
                    foreach ($locales as $locale) {
                        $payload[$group][$field][$locale] ??= '';
                    }
                }
            }
        }

        return $this->makeValidator($payload, $rules, [], [
            'home.title' => trans('setting.configs.seo_pages.items.home_title.label'),
            'blog.title' => trans('setting.configs.seo_pages.items.blog_title.label'),
            'news.title' => trans('setting.configs.seo_pages.items.news_title.label'),
            'event.title' => trans('setting.configs.seo_pages.items.event_title.label'),
            'portfolio.title' => trans('setting.configs.seo_pages.items.portfolio_title.label'),
            'search.title' => trans('setting.configs.seo_pages.items.search_title.label'),
            'faq.title' => trans('setting.configs.seo_pages.items.faq_title.label'),
            'contact.title' => trans('setting.configs.seo_pages.items.contact_title.label'),
            'about.title' => trans('setting.configs.seo_pages.items.about_title.label'),
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

        // Get default values from seo-website translations and add robots/canonical
        $pageDefaults = $this->getPageDefaults();
        $defaults = [];

        foreach ($pageDefaults as $page => $values) {
            $defaults[$page] = $values;
            $defaults[$page]['robots'] = 'index';
        }

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
