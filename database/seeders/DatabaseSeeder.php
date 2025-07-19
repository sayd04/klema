<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Location;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
        ]);

        // Create a default location for the user
        $user->locations()->create([
            'name' => 'Manila, Philippines',
            'latitude' => 14.5995,
            'longitude' => 120.9842,
        ]);
    }
}
