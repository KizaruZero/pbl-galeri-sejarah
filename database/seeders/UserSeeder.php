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
            ],
            [
                'name' => 'akainu',
                'email' => 'akainu@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ],
            [
                'name' => 'aokiji',
                'email' => 'aokiji@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'member',
            ],
            [
                'name' => 'fujitora',
                'email' => 'fujitora@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'superadmin',
            ],
            [
                'name' => 'ryokugyu',
                'email' => 'ryokugyu@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'manager',
            ],
        ];

        DB::table('users')->insert($user);
    }
}
