<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RbacPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds according to BRD IFU V.01 Matrix.
     */
    public function run(): void
    {
        $permissions = [
            // Modul 2: Dashboard
            [
                'name' => 'dashboard.view',
                'display_name' => 'Lihat Dashboard',
                'module' => 'Dashboard',
                'description' => 'Akses dan melihat statistik ringkasan pada dashboard utama',
            ],

            // Modul 3: Mold & Status
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
                'description' => 'Mengekspor data status mold ke format Excel',
            ],

            // Modul 4: Inspection Monitoring
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
                'description' => 'Menyetujui dan memvalidasi laporan inspeksi',
            ],
            [
                'name' => 'inspection.export',
                'display_name' => 'Export Laporan Inspeksi',
                'module' => 'Inspection Monitoring',
                'description' => 'Mengekspor data inspeksi ke Excel',
            ],

            // Modul 5: Parts Stock
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

            // Modul 6: Master Data
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

            // Modul 7 & 8: Administrator Settings & Security
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

            // Modul 9: Permintaan Mold Baru (SPK & Progress Tracking)
            [
                'name' => 'spk.view',
                'display_name' => 'Lihat SPK & Progress Mold',
                'module' => 'SPK Mold Baru',
                'description' => 'Melihat daftar SPK pembuatan mold baru dan progress pengerjaan',
            ],
            [
                'name' => 'spk.create',
                'display_name' => 'Terbitkan SPK Mold Baru',
                'module' => 'SPK Mold Baru',
                'description' => 'Membuat SPK pengerjaan mold baru',
            ],
            [
                'name' => 'spk.manage',
                'display_name' => 'Kelola Tahapan & Material SPK',
                'module' => 'SPK Mold Baru',
                'description' => 'Memperbarui status tahapan pengerjaan dan checklist part/material SPK',
            ],

            // Modul 10: Tiket Perbaikan Mold (Repair Ticketing)
            [
                'name' => 'repair_ticket.view',
                'display_name' => 'Lihat Tiket Perbaikan',
                'module' => 'Tiket Perbaikan',
                'description' => 'Melihat daftar tiket perbaikan mold dan status pengerjaan',
            ],
            [
                'name' => 'repair_ticket.create',
                'display_name' => 'Ajukan Tiket Perbaikan',
                'module' => 'Tiket Perbaikan',
                'description' => 'Mengajukan tiket komplain kerusakan mold/part disertai bukti foto',
            ],
            [
                'name' => 'repair_ticket.assign',
                'display_name' => 'Penugasan Teknisi Tiket',
                'module' => 'Tiket Perbaikan',
                'description' => 'Menugaskan teknisi maintenance penanggung jawab perbaikan',
            ],
            [
                'name' => 'repair_ticket.update_progress',
                'display_name' => 'Update Progress Perbaikan',
                'module' => 'Tiket Perbaikan',
                'description' => 'Memperbarui status, persentase progress, dan catatan teknisi perbaikan',
            ],
        ];

        foreach ($permissions as $perm) {
            Permission::updateOrCreate(
                ['name' => $perm['name']],
                [
                    'display_name' => $perm['display_name'],
                    'module' => $perm['module'],
                    'description' => $perm['description'],
                ]
            );
        }

        // Attach permissions to roles according to BRD matrix
        $admin = Role::where('name', 'Admin')->first();
        if ($admin) {
            $admin->permissions()->sync(Permission::all());
        }

        $spv = Role::where('name', 'Supervisor')->first();
        if ($spv) {
            $spvPerms = Permission::whereIn('name', [
                'dashboard.view',
                'mold_status.view', 'mold_status.export',
                'inspection.view', 'inspection.create', 'inspection.update', 'inspection.approve', 'inspection.export',
                'parts_stock.view', 'parts_stock.manage', 'parts_stock.notify',
                'spk.view', 'spk.create', 'spk.manage',
                'repair_ticket.view', 'repair_ticket.create', 'repair_ticket.assign', 'repair_ticket.update_progress',
            ])->get();
            $spv->permissions()->sync($spvPerms);
        }

        $mnt = Role::where('name', 'Maintenance')->first();
        if ($mnt) {
            $mntPerms = Permission::whereIn('name', [
                'dashboard.view',
                'mold_status.view',
                'inspection.view', 'inspection.create', 'inspection.update',
                'spk.view', 'spk.manage',
                'repair_ticket.view', 'repair_ticket.create', 'repair_ticket.update_progress',
            ])->get();
            $mnt->permissions()->sync($mntPerms);
        }

        $opr = Role::where('name', 'Operator')->first();
        if ($opr) {
            $oprPerms = Permission::whereIn('name', [
                'dashboard.view',
                'mold_status.view',
                'inspection.view', 'inspection.create',
                'repair_ticket.view', 'repair_ticket.create',
            ])->get();
            $opr->permissions()->sync($oprPerms);
        }

        $ppc = Role::where('name', 'PPIC')->first();
        if ($ppc) {
            $ppcPerms = Permission::whereIn('name', [
                'dashboard.view',
                'mold_status.view', 'mold_status.export',
                'spk.view',
            ])->get();
            $ppc->permissions()->sync($ppcPerms);
        }
    }
}
