<?php

declare(strict_types=1);

namespace App\Livewire\Web\Pages;

use App\Models\Blog;
use App\Models\Faq;
use App\Models\PortFolio;
use App\Models\Slider;
use App\Services\SeoBuilder;
use Illuminate\View\View;
use Livewire\Component;

class HomePage extends Component
{
    public $tab_service_selected = 'tab_0';

    public function render(): View
    {
        SeoBuilder::create()
            ->title(trans('web/home.hero_section.title'))
            ->description(trans('web/home.hero_section.description'))
            ->keywords(trans('web/home.hero_section.keywords'))
            ->images([asset('favicon.ico')])
            ->canonical(url('/'))
            ->hreflangs([
                ['lang' => 'en', 'url' => url('/')],
                ['lang' => 'fa', 'url' => url('/fa')],
            ])
            ->apply();

        return view('livewire.web.pages.home-page', [
            'sliders'    => Slider::where('published', true)
                ->orderByDesc('id')
                ->get(),

            'faqs'       => Faq::where('published', true)
                ->where('favorite', true)
                ->orderByDesc('ordering')
                ->get(),

            'portfolios' => PortFolio::where('published', true)
                ->limit(3)
                ->get(),

            'blogs'      => Blog::where('published', true)
                ->orderByDesc('published_at')
                ->limit(10)
                ->get(),
        ])
            ->layout('components.layouts.web');
    }
}
