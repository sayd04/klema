<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@klema.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Create regular user for testing
        User::create([
            'first_name' => 'John',
            'last_name' => 'Farmer',
            'email' => 'farmer@klema.com',
            'password' => Hash::make('farmer123'),
            'role' => 'user',
        ]);

        $this->command->info('Admin users created successfully!');
        $this->command->info('Admin: admin@klema.com / admin123');
        $this->command->info('Farmer: farmer@klema.com / farmer123');
    }
} 