<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ContentVideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $videos = [
            [
                'title' => 'Exploring the Mountains',
                'video_url' => 'https://example.com/mountain_video.mp4',
                'link_youtube' => 'https://www.youtube.com/watch?v=12345',
                'thumbnail' => 'https://example.com/mountain_thumbnail.jpg',
                'description' => 'A breathtaking journey through the mountains.',
                'note' => 'Filmed with a drone.',
                'source' => 'User Upload',
                'status' => 2,
                'user_id' => 1,
                'total_views' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'City Life at Night',
                'video_url' => 'https://example.com/city_video.mp4',
                'link_youtube' => 'https://www.youtube.com/watch?v=12345',
                'thumbnail' => 'https://example.com/mountain_thumbnail.jpg',
                'description' => 'The vibrant nightlife of the city.',
                'note' => 'Time-lapse video.',
                'source' => 'User Upload',
                'status' => 2,
                'user_id' => 2,
                'total_views' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Underwater Adventure',
                'video_url' => 'https://example.com/underwater_video.mp4',
                'link_youtube' => 'https://www.youtube.com/watch?v=12345',
                'thumbnail' => 'https://example.com/mountain_thumbnail.jpg',
                'description' => 'Exploring the beauty of the ocean.',
                'note' => 'Filmed with a waterproof camera.',
                'source' => 'User Upload',
                'status' => 2,
                'user_id' => 3,
                'total_views' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Autumn in the Park',
                'video_url' => 'https://example.com/autumn_video.mp4',
                'link_youtube' => 'https://www.youtube.com/watch?v=12345',
                'thumbnail' => 'https://example.com/mountain_thumbnail.jpg',
                'description' => 'A peaceful walk through the park during autumn.',
                'note' => 'Filmed with a smartphone.',
                'source' => 'User Upload',
                'status' => 2,
                'user_id' => 4,
                'total_views' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Sunset on the Beach',
                'video_url' => 'https://example.com/sunset_video.mp4',
                'link_youtube' => 'https://www.youtube.com/watch?v=12345',
                'thumbnail' => 'https://example.com/mountain_thumbnail.jpg',
                'description' => 'A relaxing sunset by the beach.',
                'note' => 'Filmed with a DSLR camera.',
                'source' => 'User Upload',
                'status' => 2,
                'user_id' => 5,
                'total_views' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($videos as &$video) {
            $video['slug'] = Str::slug($video['title']); // Generate slug dari judul
        }

        // Insert data ke database
        DB::table('content_video')->insert($videos);
    }
}
