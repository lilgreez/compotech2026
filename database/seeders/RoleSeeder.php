<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Admin',
                'description' => 'Full access to all features',
            ],
            [
                'name' => 'Supervisor',
                'description' => 'View all data, approve requests, export reports',
            ],
            [
                'name' => 'Maintenance',
                'description' => 'CRUD operations for inspections',
            ],
            [
                'name' => 'Operator',
                'description' => 'Create inspection records only',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']],
                ['description' => $role['description']]
            );
        }
    }
}