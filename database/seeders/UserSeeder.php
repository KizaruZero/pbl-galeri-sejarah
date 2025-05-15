<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $users = [
            [
                'name' => 'kizaru kaede',
                'email' => 'kizarukaede@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'super_admin'  // This is just for our reference, not stored in DB
            ],
            [
                'name' => 'ardya pusaka',
                'email' => 'ardyapusaka@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'member'
            ],
            [
                'name' => 'kizaru',
                'email' => 'kizaru@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'member'
            ],
            [
                'name' => 'akainu',
                'email' => 'akainu@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'member'
            ],
            [
                'name' => 'aokiji',
                'email' => 'aokiji@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'member'
            ],
            [
                'name' => 'fujitora',
                'email' => 'fujitora@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'member'
            ],
            [
                'name' => 'ryokugyu',
                'email' => 'ryokugyu@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'member'
            ],
            [
                'name' => 'Kizaru Author',
                'email' => 'kizaruauthor@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'author'
            ],
            [
                'name' => 'Kizaru Direktur',
                'email' => 'kizarudirektur@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'direktur'
            ],


        ];

        // Insert the users into the database
        foreach ($users as $userData) {
            // Remove the 'role' key from the data before inserting
            $role = $userData['role'];
            unset($userData['role']);

            // Create user
            $user = User::create($userData);

            // Assign role
            $user->assignRole($role);
        }
    }
}
