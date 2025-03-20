<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'id' => Str::uuid(),
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'phone' => '081234567890',
                'password' => Hash::make('password123'),
                'is_verified' => true,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Test User',
                'email' => 'user@example.com',
                'phone' => '089876543210',
                'password' => Hash::make('password123'),
                'is_verified' => true,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Store Owner',
                'email' => 'store@example.com',
                'phone' => '087654321098',
                'password' => Hash::make('password123'),
                'is_verified' => true,
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
