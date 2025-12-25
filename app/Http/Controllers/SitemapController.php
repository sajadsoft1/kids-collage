<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Helpers\Constants;
use App\Models\Blog;
use App\Models\PortFolio;
use Illuminate\Http\Response;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $sitemap = Sitemap::create()
            ->add(Url::create(route('home-page')))
            ->add(Url::create(route('sitemap-article')))
            ->add(Url::create(route('sitemap-portfolio')))
            ->add(Url::create(route('services.website')))
            ->add(Url::create(route('services.application')));

        return response($sitemap->render(), 200)
            ->header('Content-Type', 'application/xml');
    }

    public function article(): Response
    {
        $sitemap = Sitemap::create();

        Blog::where('published', true)
            ->with('seoOption')
            ->orderByDesc('created_at')
            ->get()
            ->each(function (Blog $blog) use ($sitemap) {
                // Skip if excluded from sitemap
                if ($blog->seoOption && $blog->seoOption->sitemap_exclude) {
                    return;
                }

                $url = Url::create(route('blog.detail', ['slug' => $blog->slug]))
                    ->setLastModificationDate($blog->updated_at);

                // Use sitemap settings from seoOption if available
                if ($blog->seoOption) {
                    if ($blog->seoOption->sitemap_priority !== null) {
                        $url->setPriority((float) $blog->seoOption->sitemap_priority);
                    } else {
                        $url->setPriority(0.8);
                    }

                    if ($blog->seoOption->sitemap_changefreq) {
                        $url->setChangeFrequency($blog->seoOption->sitemap_changefreq);
                    } else {
                        $url->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY);
                    }
                } else {
                    $url->setPriority(0.8)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY);
                }

                $imageUrl = $blog->getFirstMediaUrl('image', Constants::RESOLUTION_854_480);
                if ($imageUrl) {
                    $url->addImage($imageUrl);
                }

                $sitemap->add($url);
            });

        return response($sitemap->render(), 200)
            ->header('Content-Type', 'application/xml');
    }

    public function portfolio(): Response
    {
        $sitemap = Sitemap::create();

        PortFolio::where('published', true)
            ->with('seoOption')
            ->orderByDesc('created_at')
            ->get()
            ->each(function (PortFolio $portfolio) use ($sitemap) {
                // Skip if excluded from sitemap
                if ($portfolio->seoOption && $portfolio->seoOption->sitemap_exclude) {
                    return;
                }

                $url = Url::create(route('portfolio.detail', ['slug' => $portfolio->slug]))
                    ->setLastModificationDate($portfolio->updated_at);

                // Use sitemap settings from seoOption if available
                if ($portfolio->seoOption) {
                    if ($portfolio->seoOption->sitemap_priority !== null) {
                        $url->setPriority((float) $portfolio->seoOption->sitemap_priority);
                    } else {
                        $url->setPriority(0.8);
                    }

                    if ($portfolio->seoOption->sitemap_changefreq) {
                        $url->setChangeFrequency($portfolio->seoOption->sitemap_changefreq);
                    } else {
                        $url->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY);
                    }
                } else {
                    $url->setPriority(0.8)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY);
                }

                $imageUrl = $portfolio->getFirstMediaUrl('image', Constants::RESOLUTION_854_480);
                if ($imageUrl) {
                    $url->addImage($imageUrl);
                }

                $sitemap->add($url);
            });

        return response($sitemap->render(), 200)
            ->header('Content-Type', 'application/xml');
    }
}
