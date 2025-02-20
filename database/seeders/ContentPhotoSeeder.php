<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContentPhotoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('content_photo')->insert([
            [
                'title' => 'Sunset at the Beach',
                'user_id' => 1,
                'image_url' => 'https://example.com/sunset.jpg',
                'description' => 'A beautiful sunset at the beach.',
                'source' => 'User Upload',
                'alt_text' => 'Sunset at the beach',
                'note' => 'Taken with a DSLR camera.',
                'status' => 'accepted',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Mountain Hike',
                'user_id' => 2,
                'image_url' => 'https://example.com/mountain.jpg',
                'description' => 'A scenic view from the top of the mountain.',
                'source' => 'User Upload',
                'alt_text' => 'Mountain hike view',
                'note' => 'Hiked for 5 hours to reach the top.',
                'status' => 'accepted',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'City Lights',
                'user_id' => 3,
                'image_url' => 'https://example.com/city.jpg',
                'description' => 'The city lights at night.',
                'source' => 'User Upload',
                'alt_text' => 'City lights at night',
                'note' => 'Long exposure shot.',
                'status' => 'accepted',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Forest Trail',
                'user_id' => 4,
                'image_url' => 'https://example.com/forest.jpg',
                'description' => 'A peaceful trail through the forest.',
                'source' => 'User Upload',
                'alt_text' => 'Forest trail',
                'note' => 'Early morning hike.',
                'status' => 'accepted',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Autumn Leaves',
                'user_id' => 5,
                'image_url' => 'https://example.com/autumn.jpg',
                'description' => 'Colorful autumn leaves on the ground.',
                'source' => 'User Upload',
                'alt_text' => 'Autumn leaves',
                'note' => 'Taken during the fall season.',
                'status' => 'accepted',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
