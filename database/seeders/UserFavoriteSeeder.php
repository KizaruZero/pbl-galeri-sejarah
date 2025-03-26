<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserFavoriteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $user_favorite = [
            [
                'user_id' => 5,
                'content_photo_id' => 3,
                'content_video_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'content_photo_id' => 1,
                'content_video_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'content_photo_id' => null,
                'content_video_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'content_photo_id' => null,
                'content_video_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 4,
                'content_photo_id' => null,
                'content_video_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 4,
                'content_photo_id' => 3,
                'content_video_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ];
    }
}
