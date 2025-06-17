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
                'bg_home_1' => 'company-profile/backgrounds/home1.jpg',
                'bg_home_2' => 'company-profile/backgrounds/home2.jpg',
                'bg_home_3' => 'company-profile/backgrounds/home2.jpg',
                //home
                'bg_events_1' => 'company-profile/backgrounds/event1.jpg',
                'bg_events_2' => 'company-profile/backgrounds/event2.jpg',
                'bg_events_3' => 'company-profile/backgrounds/event3.jpg',
                //gallery
                'bg_gallery_1' => 'company-profile/backgrounds/galeri1.png',
                'bg_gallery_2' => 'company-profile/backgrounds/galeri2.png',
                'bg_gallery_3' => 'company-profile/backgrounds/galeri3.png',
                //article
                'bg_article_1' => 'company-profile/backgrounds/artikel1.jpg',
                'bg_article_2' => 'company-profile/backgrounds/artikel2.jpg',
                'bg_article_3' => 'company-profile/backgrounds/artikel3.jpg',

                'bg_member_1' => 'company-profile/backgrounds/member1.jpg',
                'bg_member_2' => 'company-profile/backgrounds/member2.jpg',
                'bg_member_3' => 'company-profile/backgrounds/member3.jpg',

                'logo' => 'company-profile/logo.png',
                // 'cms_name' => 'Pesona Surakarta',
                'events_text' => 'Jelajahi ragam acara budaya dan festival tradisional kota Surakarta',
                'gallery_text' => 'Galeri visual keindahan sejarah, budaya, dan kuliner khas Surakarta',
                'article_text' => 'Temukan cerita menarik tentang warisan budaya dan pesona kota Surakarta',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
