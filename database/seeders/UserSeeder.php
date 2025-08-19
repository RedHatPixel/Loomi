<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin Account
        User::create([
            'name' => 'mobarahk',
            'email' => 'mobarahk.admin@gmail.com',
            'password' => 'moba123',
            'role' => 'admin'
        ]);

        // User Account
        User::create([
            'name' => 'nikkko',
            'email' => 'niko.cambronero@gmail.com',
            'password' => 'niko123'
        ]);

        // Create Random Users
        User::factory()->count(20)->create();
    }
}
