<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            'pending',
            'packaging',
            'shipped',
            'delivered',
            'received'
        ];

        foreach ($statuses as $status) {
            Status::create(['status' => $status]);
        }
    }
}
