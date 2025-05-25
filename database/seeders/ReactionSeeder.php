<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $reaction = [
            [
                'react_type' => 'like',
                'icon' => '👍',

            ],
            [
                'react_type' => 'dislike',
                'icon' => '👎',

            ],
            [
                'react_type' => 'love',
                'icon' => '❤️',

            ],
            [
                'react_type' => 'haha',
                'icon' => '😂',

            ],
            [
                'react_type' => 'wow',
                'icon' => '😮',

            ],
            [
                'react_type' => 'sad',
                'icon' => '😢',

            ],
            [
                'react_type' => 'angry',
                'icon' => '😡',

            ],
            
        ];

        foreach ($reaction as $key => $value) {
            \App\Models\Reaction::create($value);
        }
    }
}
