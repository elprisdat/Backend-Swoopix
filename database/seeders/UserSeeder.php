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
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Sarah Johnson',
                'email' => 'sarah@example.com',
                'phone' => '081122334455',
                'password' => Hash::make('password123'),
                'is_verified' => true,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Michael Chen',
                'email' => 'michael@example.com',
                'phone' => '082233445566',
                'password' => Hash::make('password123'),
                'is_verified' => true,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Restaurant Manager',
                'email' => 'manager@example.com',
                'phone' => '083344556677',
                'password' => Hash::make('password123'),
                'is_verified' => true,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Customer Service',
                'email' => 'cs@example.com',
                'phone' => '084455667788',
                'password' => Hash::make('password123'),
                'is_verified' => true,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Marketing Staff',
                'email' => 'marketing@example.com',
                'phone' => '085566778899',
                'password' => Hash::make('password123'),
                'is_verified' => true,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Finance Admin',
                'email' => 'finance@example.com',
                'phone' => '086677889900',
                'password' => Hash::make('password123'),
                'is_verified' => true,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Delivery Partner',
                'email' => 'delivery@example.com',
                'phone' => '087788990011',
                'password' => Hash::make('password123'),
                'is_verified' => true,
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
