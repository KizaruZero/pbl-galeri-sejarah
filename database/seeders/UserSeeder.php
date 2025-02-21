<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $user = [
            [
            'name' => 'kizaru kaede',
            'email' => 'kizarukaede@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            ],
            [
            'name' => 'ardya pusaka',
            'email' => 'ardyapusaka@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'direktur',
            ],
            [
            'name' => 'kizaru',
            'email' => 'kizaru@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'member',
            ]
        ];
    }
}
