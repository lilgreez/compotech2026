<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RbacPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Dashboard
            [
                'name' => 'dashboard.view',
                'display_name' => 'Lihat Dashboard',
                'module' => 'Dashboard',
                'description' => 'Akses dan melihat statistik ringkasan pada dashboard utama',
            ],

            // Mold & Status
            [
                'name' => 'mold_status.view',
                'display_name' => 'Lihat Status Mold',
                'module' => 'Mold & Diecast',
                'description' => 'Melihat status operasional dan kondisi mold',
            ],
            [
                'name' => 'mold_status.export',
                'display_name' => 'Export Status Mold',
                'module' => 'Mold & Diecast',
                'description' => 'Mengekspor data status mold ke format PDF/Excel',
            ],

            // Inspection Monitoring
            [
                'name' => 'inspection.view',
                'display_name' => 'Lihat Monitoring Inspeksi',
                'module' => 'Inspection Monitoring',
                'description' => 'Melihat daftar riwayat pemeriksaan dan monitoring inspeksi',
            ],
            [
                'name' => 'inspection.create',
                'display_name' => 'Input Form Inspeksi',
                'module' => 'Inspection Monitoring',
                'description' => 'Membuat dan menginput formulir inspeksi mold berkala',
            ],
            [
                'name' => 'inspection.update',
                'display_name' => 'Edit / Update Inspeksi',
                'module' => 'Inspection Monitoring',
                'description' => 'Memperbarui hasil temuan dan tindakan perbaikan inspeksi',
            ],
            [
                'name' => 'inspection.approve',
                'display_name' => 'Approval Laporan Inspeksi',
                'module' => 'Inspection Monitoring',
                'description' => 'Menyetujui dan memvalidasi laporan inspeksi dari operator/maintenance',
            ],
            [
                'name' => 'inspection.export',
                'display_name' => 'Export Laporan Inspeksi',
                'module' => 'Inspection Monitoring',
                'description' => 'Mengekspor data inspeksi ke Excel/PDF',
            ],

            // Parts Stock
            [
                'name' => 'parts_stock.view',
                'display_name' => 'Lihat Stok Parts',
                'module' => 'Parts Stock',
                'description' => 'Melihat kuantitas dan status ketersediaan stok parts cadangan',
            ],
            [
                'name' => 'parts_stock.manage',
                'display_name' => 'Kelola Stok Parts',
                'module' => 'Parts Stock',
                'description' => 'Menambah atau mengurangi kuantitas fisik stok spareparts',
            ],
            [
                'name' => 'parts_stock.notify',
                'display_name' => 'Kirim Notifikasi Stok ke SPV',
                'module' => 'Parts Stock',
                'description' => 'Mengirim pemberitahuan stok kritis via email ke Supervisor',
            ],

            // Master Data
            [
                'name' => 'master_molds.view',
                'display_name' => 'Lihat Master Mold',
                'module' => 'Master Data',
                'description' => 'Melihat data master spesifikasi teknis mold',
            ],
            [
                'name' => 'master_molds.manage',
                'display_name' => 'Kelola Master Mold',
                'module' => 'Master Data',
                'description' => 'Menambah, mengubah, dan menghapus data master mold serta generate parts',
            ],
            [
                'name' => 'master_parts.manage',
                'display_name' => 'Kelola Master Parts',
                'module' => 'Master Data',
                'description' => 'Sinkronisasi katalog Wings dan manajemen master komponen parts',
            ],
            [
                'name' => 'master_inspections.manage',
                'display_name' => 'Kelola Master Inspeksi',
                'module' => 'Master Data',
                'description' => 'Mengatur kamus item standar pemeriksaan cetakan mold',
            ],

            // Administrator & Security
            [
                'name' => 'operators.manage',
                'display_name' => 'Kelola Akun Operator',
                'module' => 'Administrator',
                'description' => 'Menambah dan mengelola kredensial akun operator',
            ],
            [
                'name' => 'audit_logs.view',
                'display_name' => 'Lihat Audit Trail',
                'module' => 'Administrator',
                'description' => 'Memantau jejak audit perubahan data pada sistem',
            ],
            [
                'name' => 'email_reports.manage',
                'display_name' => 'Kelola Email Report',
                'module' => 'Administrator',
                'description' => 'Mengatur pengiriman laporan terjadwal otomatis via email',
            ],
            [
                'name' => 'rbac.manage',
                'display_name' => 'Kelola Role & Permissions (RBAC)',
                'module' => 'Administrator',
                'description' => 'Manajemen hak akses peran, permission matrix, dan user assignment',
            ],
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(
                ['name' => $perm['name']],
                [
                    'display_name' => $perm['display_name'],
                    'module' => $perm['module'],
                    'description' => $perm['description'],
                ]
            );
        }

        // Attach default permissions to initial roles
        $admin = Role::where('name', 'Admin')->first();
        if ($admin) {
            $admin->permissions()->sync(Permission::all());
        }

        $supervisor = Role::where('name', 'Supervisor')->first();
        if ($supervisor) {
            $spvPerms = Permission::whereIn('name', [
                'dashboard.view',
                'mold_status.view',
                'mold_status.export',
                'inspection.view',
                'inspection.approve',
                'inspection.export',
                'parts_stock.view',
                'parts_stock.notify',
                'master_molds.view',
            ])->get();
            $supervisor->permissions()->sync($spvPerms);
        }

        $maintenance = Role::where('name', 'Maintenance')->first();
        if ($maintenance) {
            $maintPerms = Permission::whereIn('name', [
                'dashboard.view',
                'mold_status.view',
                'inspection.view',
                'inspection.create',
                'inspection.update',
                'parts_stock.view',
                'parts_stock.manage',
                'master_molds.view',
            ])->get();
            $maintenance->permissions()->sync($maintPerms);
        }

        $operator = Role::where('name', 'Operator')->first();
        if ($operator) {
            $opPerms = Permission::whereIn('name', [
                'dashboard.view',
                'mold_status.view',
                'inspection.create',
            ])->get();
            $operator->permissions()->sync($opPerms);
        }
    }
}
