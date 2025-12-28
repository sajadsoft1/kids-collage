<?php

declare(strict_types=1);

namespace App\Services\Setting\Templates;

use App\Enums\BooleanEnum;
use App\Enums\SettingEnum;
use App\Exceptions\ValidationException;
use App\Models\Setting;

class SiteDataTemplate extends BaseTemplate
{
    public function __construct()
    {
        $this->settingEnum = SettingEnum::tryFrom('site_data');
    }

    public function template(Setting $setting): array
    {
        $this->setting = $setting;
        $booleanOptions = BooleanEnum::options();

        return [
            $this->recordComplex('homepage', [
                $this->record('featured_count', self::NUMBER, 5, null, $setting->extra_attributes->get('homepage.featured_count', 5)),
                $this->record('show_featured', self::SELECT, 1, $booleanOptions, $setting->extra_attributes->get('homepage.show_featured', 1)),
            ]),

            $this->recordComplex('lists', [
                $this->record('products_per_page', self::NUMBER, 12, null, $setting->extra_attributes->get('lists.products_per_page', 12)),
                $this->record('news_per_page', self::NUMBER, 10, null, $setting->extra_attributes->get('lists.news_per_page', 10)),
                $this->record('blog_per_page', self::NUMBER, 10, null, $setting->extra_attributes->get('lists.blog_per_page', 10)),
            ]),

            $this->recordComplex('sections', [
                $this->record('show_news_section', self::SELECT, 1, $booleanOptions, $setting->extra_attributes->get('sections.show_news_section', 1)),
                $this->record('show_blog_section', self::SELECT, 1, $booleanOptions, $setting->extra_attributes->get('sections.show_blog_section', 1)),
            ]),
        ];
    }

    /** @throws ValidationException */
    public function validate(Setting $setting, array $payload = []): array
    {
        $rules = [
            'homepage' => ['required', 'array'],
            'homepage.featured_count' => ['nullable', 'integer', 'min:0', 'max:100'],
            'homepage.show_featured' => ['nullable', 'in:0,1'],

            'lists' => ['required', 'array'],
            'lists.products_per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'lists.news_per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'lists.blog_per_page' => ['nullable', 'integer', 'min:1', 'max:100'],

            'sections' => ['required', 'array'],
            'sections.show_news_section' => ['nullable', 'in:0,1'],
            'sections.show_blog_section' => ['nullable', 'in:0,1'],
        ];

        return $this->makeValidator($payload, $rules);
    }
}
