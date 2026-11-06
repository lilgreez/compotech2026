<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Only seed primary Super Admin role, no dummy roles
        Role::firstOrCreate(
            ['name' => 'Admin'],
            ['description' => 'Super Administrator - Full system access']
        );
    }
}