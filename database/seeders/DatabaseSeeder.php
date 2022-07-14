<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Vehicle::create([
            'name' => 'Renault Megan'
        ]);
        Vehicle::create([
            'name' => 'Ford Fiesta'
        ]);
        Vehicle::create([
            'name' => 'Ford Focus'
        ]);
    }
}
