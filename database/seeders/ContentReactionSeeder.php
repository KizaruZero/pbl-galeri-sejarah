<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContentReactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            for ($j = 1; $j <= 7; $j++) {
                DB::table('content_reactions')->insert([
                    'user_id' => rand(1, 7),
                    'content_photo_id' => $i,
                    'content_video_id' => null,
                    'reaction_type_id' => rand(1, 7),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('content_reactions')->insert([
                    'user_id' => rand(1, 7),
                    'content_photo_id' => null,
                    'content_video_id' => $i,
                    'reaction_type_id' => rand(1, 7),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
