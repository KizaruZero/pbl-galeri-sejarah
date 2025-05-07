<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            CategorySeeder::class,
            ReactionSeeder::class,
            UserSeeder::class,
            ContentPhotoSeeder::class,
            ContentVideoSeeder::class,
            ContentReactionSeeder::class,
            MertadataPhotoSeeder::class,
            MertadataVideoSeeder::class,
            UserCommentSeeder::class,
            UserReactionSeeder::class,
            CategoryContentSeeder::class,
            ComproSeeder::class,
            ArticleSeeder::class,
            EventSeeder::class,
        ]);
    }
}
