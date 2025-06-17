<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // Makanan Tradisional (Category ID: 1)
            [
                'category_id' => 1,
                'content_photo_id' => 1, // Timlo Solo
                'content_video_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 1,
                'content_photo_id' => 2, // Tengkleng Solo
                'content_video_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 1,
                'content_photo_id' => 3, // Selat Solo
                'content_video_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 1,
                'content_photo_id' => 4, // Srabi Solo
                'content_video_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 1,
                'content_photo_id' => 5, // Nasi Liwet Solo
                'content_video_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Seni Budaya (Category ID: 2)
            [
                'category_id' => 2,
                'content_photo_id' => 6, // Wayang Orang Sriwedari
                'content_video_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 2,
                'content_photo_id' => 7, // Gamelan Keraton
                'content_video_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 2,
                'content_photo_id' => 8, // Karnaval Batik Solo
                'content_video_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Tempat Bersejarah (Category ID: 3)
            [
                'category_id' => 3,
                'content_photo_id' => 9, // Pura Mangkunegaran
                'content_video_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 3,
                'content_photo_id' => 10, // Masjid Zayed Solo
                'content_video_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 3,
                'content_photo_id' => 11, // Balai Kota Solo
                'content_video_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Acara dan Festival (Category ID: 5)
            [
                'category_id' => 5,
                'content_photo_id' => 12, // Grebeg Maulud
                'content_video_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 5,
                'content_photo_id' => 13, // Kirab Kerbau Bule
                'content_video_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 5,
                'content_photo_id' => 14, // Sekaten Yogyakarta
                'content_video_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert data ke tabel category_content
        DB::table('category_content')->insert($data);
    }
}
