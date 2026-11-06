<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds for 5 Official Roles per BRD IFU V.01.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Admin',
                'description' => 'Super Administrator - Akses penuh ke seluruh konfigurasi, master data, dan audit trail sistem',
            ],
            [
                'name' => 'Supervisor',
                'description' => 'Supervisor Moldshop/Produksi - Monitoring status mold, approval inspeksi, penerbitan SPK, dan penugasan perbaikan',
            ],
            [
                'name' => 'Maintenance',
                'description' => 'Teknisi / Mekanik - Pelaksana pengerjaan tahapan SPK mold baru dan penanganan tiket perbaikan mold',
            ],
            [
                'name' => 'Operator',
                'description' => 'Operator Mesin - Pelaksana inspeksi mold lapangan dan pengaju tiket perbaikan jika terjadi kerusakan',
            ],
            [
                'name' => 'PPIC',
                'description' => 'Production Planning & Inventory Control - Monitoring ketersediaan mold dan pemantauan progress SPK mold baru',
            ],
        ];

        foreach ($roles as $r) {
            Role::updateOrCreate(
                ['name' => $r['name']],
                ['description' => $r['description']]
            );
        }
    }
}