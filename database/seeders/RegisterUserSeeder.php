<?php

namespace Database\Seeders;

use App\Models\User;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegisterUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         User::create([
            'user_name' => 'admin',
            'email' => 'nour@gmail.com',
            'password' => bcrypt('12345678'),
            'role'=>'admin',

        ]);

        User::create([
            'user_name' => 'publisher',
            'email' => 'mohammed@gmail.com',
            'password' => bcrypt('12345678'),
            'role'=>'publisher',

        ]);

        User::create([
            'user_name' => 'student',
            'email' => 'enas@gmail.com',
            'password' => bcrypt('12345678'),
            'role'=>'student',

        ]);
    }
}
