<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('user_comments')->insert([
            // Komentar untuk content_photo
            [
                'content' => 'Wow, this sunset is absolutely stunning!',
                'user_id' => 1,
                'content_photo_id' => 1, // ID dari content_photo
                'content_video_id' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'content' => 'I love the colors in this photo!',
                'user_id' => 2,
                'content_photo_id' => 2, // ID dari content_photo
                'content_video_id' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Komentar untuk content_video
            [
                'content' => 'This mountain video is so inspiring!',
                'user_id' => 3,
                'content_photo_id' => null,
                'content_video_id' => 1, // ID dari content_video
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'content' => 'The city lights look magical in this video!',
                'user_id' => 4,
                'content_photo_id' => null,
                'content_video_id' => 2, // ID dari content_video
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Komentar untuk content_photo
            [
                'content' => 'The autumn leaves are so beautiful!',
                'user_id' => 5,
                'content_photo_id' => 5, // ID dari content_photo
                'content_video_id' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
