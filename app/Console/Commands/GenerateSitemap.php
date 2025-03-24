<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\ContentPhoto;
use App\Models\ContentVideo;



class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate sitemap.xml';

    /**
     * The console command description.
     *
     * @var string
     */

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sitemap = Sitemap::create();

        // Tambahkan URL statis
        $sitemap->add(Url::create('/'));
        $sitemap->add(Url::create('/galeri'));

        // Tambahkan URL dinamis dari database
        $photos = ContentPhoto::all();
        foreach ($photos as $photo) {
            $sitemap->add(Url::create("/content-photo/{$photo->slug}"));
        }

        $videos = ContentVideo::all();
        foreach ($videos as $video) {
            $sitemap->add(Url::create("/content-video/{$video->slug}"));
        }

        $sitemap->writeToFile(public_path('sitemap.xml'));
    }
}
