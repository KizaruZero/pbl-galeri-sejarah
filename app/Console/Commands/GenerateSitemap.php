<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\ContentPhoto;
use App\Models\ContentVideo;
use App\Models\Category;
use App\Models\Article;
use App\Models\Event;
use Carbon\Carbon;

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

        // Add static URLs with high priority
        $sitemap->add(Url::create('/')
            ->setPriority(1.0)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setLastModificationDate(Carbon::yesterday()));

        $sitemap->add(Url::create('/gallery')
            ->setPriority(0.9)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setLastModificationDate(Carbon::yesterday()));

        $sitemap->add(Url::create('/article')
            ->setPriority(0.8)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setLastModificationDate(Carbon::yesterday()));

        $sitemap->add(Url::create('/member')
            ->setPriority(0.7)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            ->setLastModificationDate(Carbon::yesterday()));

        $sitemap->add(Url::create('/contact')
            ->setPriority(0.7)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            ->setLastModificationDate(Carbon::yesterday()));

        $sitemap->add(Url::create('/events')
            ->setPriority(0.8)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setLastModificationDate(Carbon::yesterday()));

        // Add category pages with medium priority
        $categories = Category::all();
        foreach ($categories as $category) {
            $sitemap->add(Url::create("/gallery/{$category->slug}")
                ->setPriority(0.8)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setLastModificationDate($category->updated_at ?? Carbon::yesterday()));
        }

        // Add content photos with their categories
        $photos = ContentPhoto::with(['categoryContents.category', 'metadataPhoto'])->get();
        foreach ($photos as $photo) {
            foreach ($photo->categoryContents as $categoryContent) {
                $url = Url::create("/gallery-photo/{$categoryContent->category->slug}/{$photo->slug}")
                    ->setPriority(0.7)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setLastModificationDate($photo->updated_at ?? Carbon::yesterday());

                // Add image sitemap data if available
                if ($photo->metadataPhoto && $photo->metadataPhoto->image_url) {
                    $url->addImage($photo->metadataPhoto->image_url, $photo->title);
                }

                $sitemap->add($url);
            }
        }

        // Add content videos with their categories
        $videos = ContentVideo::with(['categoryContents.category', 'metadataVideo'])->get();
        foreach ($videos as $video) {
            foreach ($video->categoryContents as $categoryContent) {
                $url = Url::create("/gallery-video/{$categoryContent->category->slug}/{$video->slug}")
                    ->setPriority(0.7)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setLastModificationDate($video->updated_at ?? Carbon::yesterday());

                // Add video thumbnail if available
                if ($video->metadataVideo && $video->metadataVideo->thumbnail) {
                    $url->addImage($video->metadataVideo->thumbnail, $video->title);
                }

                $sitemap->add($url);
            }
        }

        // Add article pages
        $articles = Article::all();
        foreach ($articles as $article) {
            $sitemap->add(Url::create("/article/{$article->slug}")
                ->setPriority(0.8)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setLastModificationDate($article->updated_at ?? Carbon::yesterday()));
        }

        // Add event pages
        $events = Event::all();
        foreach ($events as $event) {
            $sitemap->add(Url::create("/events/{$event->slug}")
                ->setPriority(0.8)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setLastModificationDate($event->updated_at ?? Carbon::yesterday()));
        }

        // Write the sitemap
        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully with SEO optimization!');
    }
}
