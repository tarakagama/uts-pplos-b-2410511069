<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\Field::create([
            'name' => 'Stadion Gelora Bung Karno',
            'type' => 'Football',
            'price_per_hour' => 500000,
        ]);
    }
}
