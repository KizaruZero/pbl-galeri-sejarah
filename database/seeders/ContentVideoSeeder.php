<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContentVideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('content_video')->insert([
            [
                'title' => 'Exploring the Mountains',
                'video_url' => 'https://example.com/mountain_video.mp4',
                'description' => 'A breathtaking journey through the mountains.',
                'note' => 'Filmed with a drone.',
                'source' => 'User Upload',
                'status' => 'pending',
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'City Life at Night',
                'video_url' => 'https://example.com/city_video.mp4',
                'description' => 'The vibrant nightlife of the city.',
                'note' => 'Time-lapse video.',
                'source' => 'User Upload',
                'status' => 'pending',
                'user_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Underwater Adventure',
                'video_url' => 'https://example.com/underwater_video.mp4',
                'description' => 'Exploring the beauty of the ocean.',
                'note' => 'Filmed with a waterproof camera.',
                'source' => 'User Upload',
                'status' => 'pending',
                'user_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Autumn in the Park',
                'video_url' => 'https://example.com/autumn_video.mp4',
                'description' => 'A peaceful walk through the park during autumn.',
                'note' => 'Filmed with a smartphone.',
                'source' => 'User Upload',
                'status' => 'pending',
                'user_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Sunset on the Beach',
                'video_url' => 'https://example.com/sunset_video.mp4',
                'description' => 'A relaxing sunset by the beach.',
                'note' => 'Filmed with a DSLR camera.',
                'source' => 'User Upload',
                'status' => 'accepted',
                'user_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
