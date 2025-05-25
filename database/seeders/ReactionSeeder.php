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
                'icon' => 'fa-solid fa-thumbs-up',

            ],
            [
                'react_type' => 'dislike',
                'icon' => 'fa-solid fa-thumbs-down',

            ],
            [
                'react_type' => 'love',
                'icon' => 'fa-solid fa-heart',

            ],
            [
                'react_type' => 'haha',
                'icon' => 'fa-solid fa-face-grin-squint-tears',

            ],
            [
                'react_type' => 'wow',
                'icon' => 'fa-solid fa-face-surprise',

            ],
            [
                'react_type' => 'sad',
                'icon' => 'fa-solid fa-face-sad-tear',

            ],
            [
                'react_type' => 'angry',
                'icon' => 'fa-solid fa-face-angry',

            ],
            
        ];

        foreach ($reaction as $key => $value) {
            \App\Models\Reaction::create($value);
        }
    }
}
