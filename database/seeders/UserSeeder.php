<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Master user — admin, seller, AND customer
        $master = User::firstOrCreate(
            ['email' => 'admin@loomi.test'],
            [
                'name'              => 'Loomi Master',
                'password'          => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'visitor@loomi.test'],
            [
                'name'              => 'Loomi Visitor',
                'password'          => Hash::make('visitor123'),
                'email_verified_at' => now(),
            ]
        );

        // Assign all three roles
        $master->assignRole('admin');
        $master->assignRole('seller');
        $master->assignRole('customer');

        // Additional regular users for testing multi-user scenarios
        $extra = [
            ['name' => 'Juan Dela Cruz', 'email' => 'juan@loomi.test',  'role' => 'customer'],
            ['name' => 'Maria Santos',   'email' => 'maria@loomi.test', 'role' => 'customer'],
        ];

        foreach ($extra as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'              => $data['name'],
                    'password'          => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
            $user->assignRole($data['role']);
        }
    }
}
