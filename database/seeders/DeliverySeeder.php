<?php

namespace Database\Seeders;

use App\Models\Delivery;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeliverySeeder extends Seeder
{
    public function run()
    {
        $users = User::where('user_type', 'delivery')->get();

        foreach ($users as $user) {
            Delivery::create([
                'user_id' => $user->id,
                'location' => json_encode([
                    'lat' => fake()->latitude,
                    'lng' => fake()->longitude,
                ]),
                'status' => 'available',
            ]);
        }
    }
}
