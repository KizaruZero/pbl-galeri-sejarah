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
        //
        $data = [
            // Content Photo 1 memiliki 2 kategori
            [
                'category_id' => 1, // Kategori 1
                'content_photo_id' => 1, // Content Photo 1
                'content_video_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 2, // Kategori 2
                'content_photo_id' => 1, // Content Photo 1
                'content_video_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Content Video 1 memiliki 2 kategori
            [
                'category_id' => 3, // Kategori 3
                'content_photo_id' => 1,
                'content_video_id' => 1, // Content Video 1
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 4, // Kategori 4
                'content_photo_id' => null,
                'content_video_id' => 1, // Content Video 1
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Content Photo 2 memiliki 1 kategori
            [
                'category_id' => 5, // Kategori 5
                'content_photo_id' => 2, // Content Photo 2
                'content_video_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Content Video 2 memiliki 1 kategori
            [
                'category_id' => 6, // Kategori 6
                'content_photo_id' => null,
                'content_video_id' => 2, // Content Video 2
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Content Photo 3 memiliki 2 kategori
            [
                'category_id' => 7, // Kategori 7
                'content_photo_id' => 3, // Content Photo 3
                'content_video_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 8, // Kategori 8
                'content_photo_id' => 3, // Content Photo 3
                'content_video_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Content Video 3 memiliki 1 kategori
            [
                'category_id' => 9, // Kategori 9
                'content_photo_id' => null,
                'content_video_id' => 3, // Content Video 3
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Content Photo 4 memiliki 1 kategori
            [
                'category_id' => 10, // Kategori 10
                'content_photo_id' => 4, // Content Photo 4
                'content_video_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert data ke tabel category_content
        DB::table('category_content')->insert($data);
    }
}
