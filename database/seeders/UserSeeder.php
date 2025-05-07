<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
                'role' => '3',
            ],
            [
                'name' => 'ardya pusaka',
                'email' => 'ardyapusaka@gmail.com',
                'password' => bcrypt('password'),
                'role' => '1',
            ],
            [
                'name' => 'kizaru',
                'email' => 'kizaru@gmail.com',
                'password' => bcrypt('password'),
                'role' => '1',
            ],
            [
                'name' => 'akainu',
                'email' => 'akainu@gmail.com',
                'password' => bcrypt('password'),
                'role' => '1',
            ],
            [
                'name' => 'aokiji',
                'email' => 'aokiji@gmail.com',
                'password' => bcrypt('password'),
                'role' => '1',
            ],
            [
                'name' => 'fujitora',
                'email' => 'fujitora@gmail.com',
                'password' => bcrypt('password'),
                'role' => '2',
            ],
            [
                'name' => 'ryokugyu',
                'email' => 'ryokugyu@gmail.com',
                'password' => bcrypt('password'),
                'role' => '4',
            ],
        ];

        DB::table('users')->insert($user);
    }
}
