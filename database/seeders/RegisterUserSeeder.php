<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RegisterUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'user_name' => 'admin',
                'email' => 'nour@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
            ],
            [
                'user_name' => 'publisher',
                'email' => 'mohammed@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'publisher',
            ],
            [
                'user_name' => 'student',
                'email' => 'enas@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'student',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
