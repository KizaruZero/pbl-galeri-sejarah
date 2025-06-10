<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ContentPhotoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $photos = [
            [
                'title' => 'Sunset at the Beach',
                'user_id' => 1,
                'image_url' => 'https://example.com/sunset.jpg',
                'description' => 'A beautiful sunset at the beach.',
                'source' => 'User Upload',
                'alt_text' => 'Sunset at the beach',
                'note' => 'Taken with a DSLR camera.',
                'tag' => 'nature, sunset',
                'status' => 2,
                'total_views' => 0,
                'approved_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Mountain Hike',
                'user_id' => 2,
                'image_url' => 'foto_content/nature.png',
                'description' => 'A scenic view from the top of the mountain.',
                'source' => 'User Upload',
                'alt_text' => 'Mountain hike view',
                'note' => 'Hiked for 5 hours to reach the top.',
                'tag' => 'nature, sunset',
                'status' => 2,
                'total_views' => 0,
                'approved_at' => null,
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
                'tag' => 'nature, sunset',
                'status' => 2,
                'total_views' => 0,
                'approved_at' => now(),
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
                'tag' => 'nature, sunset',
                'status' => 2,
                'total_views' => 0,
                'approved_at' => now(),
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
                'tag' => 'nature, sunset',
                'status' => 2,
                'total_views' => 0,
                'approved_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($photos as &$photo) {
            $photo['slug'] = Str::slug($photo['title']); // Generate slug dari judul
        }

        // Insert data ke database
        DB::table('content_photo')->insert($photos);
    }
}
