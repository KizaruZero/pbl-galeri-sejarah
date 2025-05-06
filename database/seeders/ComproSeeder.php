<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComproSeeder extends Seeder
{
    public function run()
    {
        DB::table('company_profiles')->insert([
            [
                //home
                'bg_home_1' => 'company-profile/backgrounds/bg_home/bg_home_1.png',
                'bg_home_2' => 'company-profile/backgrounds/bg_home/bg_home_2.png',
                'bg_home_3' => 'company-profile/backgrounds/bg_home/bg_home_2.png',
                //home
                'bg_events_1' => 'company-profile/backgrounds/bg_event/bg_event_1.jpg',
                'bg_events_2' => 'company-profile/backgrounds/bg_event/bg_event_2.jpg',
                'bg_events_3' => 'company-profile/backgrounds/bg_event/bg_event_3.jpg',
                //gallery
                'bg_gallery_1' => 'company-profile/backgrounds/bg_gallery/bg_gallery_1.png',
                'bg_gallery_2' => 'company-profile/backgrounds/bg_gallery/bg_gallery_2.png',
                'bg_gallery_3' => 'company-profile/backgrounds/bg_gallery/bg_gallery_3.png',
                //article
                'bg_article_1' => 'company-profile/backgrounds/bg_article/bg_article_1.png',
                'bg_article_2' => 'company-profile/backgrounds/bg_article/bg_article_2.png',
                'bg_article_3' => 'company-profile/backgrounds/bg_article/bg_article_3.png',

                'logo' => 'company-profile/logo.png',
                'cms_name' => 'Keraton Surakarta Hadiningrat',
                'events_text' => 'Temukan acara dan kegiatan terbaru dari perusahaan kami',
                'gallery_text' => 'Galeri dokumentasi kegiatan dan momen penting perusahaan',
                'article_text' => 'Artikel dan berita terbaru seputar perkembangan perusahaan',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
