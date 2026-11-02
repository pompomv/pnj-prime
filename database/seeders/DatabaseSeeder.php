<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RekeningBluSeeder::class,
            UserSeeder::class,
            InovasiSeeder::class,
        ]);
    }
}
