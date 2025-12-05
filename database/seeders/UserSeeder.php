<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@shopsmart.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
            [
                'name' => 'Vendedor Demo',
                'email' => 'vendedor@shopsmart.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'vendedor',
            ],
            [
                'name' => 'Cliente Test',
                'email' => 'cliente@shopsmart.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'cliente',
            ],
            [
                'name' => 'Juan Pérez',
                'email' => 'juan@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'cliente',
            ],
            [
                'name' => 'María García',
                'email' => 'maria@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'vendedor',
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }
    }
}
