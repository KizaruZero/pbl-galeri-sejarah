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

            ],
            [
                'react_type' => 'dislike',

            ],
            [
                'react_type' => 'love',

            ],
            [
                'react_type' => 'haha',

            ],
            [
                'react_type' => 'wow',

            ],
            [
                'react_type' => 'sad',

            ],
            [
                'react_type' => 'angry',

            ],
            
        ];

        foreach ($reaction as $key => $value) {
            \App\Models\Reaction::create($value);
        }
    }
}
