<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserReactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('user_reactions')->insert([
            // Reaksi untuk komentar 1
            [
                'user_id' => 1, // User yang memberikan reaksi
                'comment_id' => 1, // Komentar yang direaksi
                'reaction_type_id' => 1, // Jenis reaksi (misalnya: like)
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Reaksi untuk komentar 2
            [
                'user_id' => 2,
                'comment_id' => 2,
                'reaction_type_id' => 2, // Jenis reaksi (misalnya: love)
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Reaksi untuk komentar 3
            [
                'user_id' => 3,
                'comment_id' => 3,
                'reaction_type_id' => 3, // Jenis reaksi (misalnya: haha)
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Reaksi untuk komentar 4
            [
                'user_id' => 4,
                'comment_id' => 4,
                'reaction_type_id' => 4, // Jenis reaksi (misalnya: wow)
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Reaksi untuk komentar 5
            [
                'user_id' => 5,
                'comment_id' => 5,
                'reaction_type_id' => 1, // Jenis reaksi (misalnya: like)
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
