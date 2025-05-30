<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('123456789'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Rama',
            'email' => 'user1@example.com',
            'password' => Hash::make('123456789'),
            'role' => 'user',
        ]);

        User::create([
            'name' => 'Ahmad',
            'email' => 'user2@example.com',
            'password' => Hash::make('123456789'),
            'role' => 'user',
        ]);
    }
    }

