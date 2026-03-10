<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ANDREW FIX: Memanggil RoleSeeder milik Anda agar dieksekusi!
        $this->call([
            RoleSeeder::class,
        ]);
    }
}