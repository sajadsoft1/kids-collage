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
            ->orderByDesc('created_at')
            ->get()
            ->each(function (Blog $blog) use ($sitemap) {
                $sitemap->add(
                    Url::create(route('blog.detail', ['slug' => $blog->slug]))
                        ->setLastModificationDate($blog->updated_at)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                        ->setPriority(0.8)
                        ->addImage($blog->getFirstMediaUrl('image', Constants::RESOLUTION_854_480))
                );
            });

        return response($sitemap->render(), 200)
            ->header('Content-Type', 'application/xml');
    }

    public function portfolio(): Response
    {
        $sitemap = Sitemap::create();

        PortFolio::where('published', true)
            ->orderByDesc('created_at')
            ->get()
            ->each(function (PortFolio $portfolio) use ($sitemap) {
                $sitemap->add(
                    Url::create(route('portfolio.detail', ['slug' => $portfolio->slug]))
                        ->setLastModificationDate($portfolio->updated_at)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                        ->setPriority(0.8)
                        ->addImage($portfolio->getFirstMediaUrl('image', Constants::RESOLUTION_854_480))
                );
            });

        return response($sitemap->render(), 200)
            ->header('Content-Type', 'application/xml');
    }
}
