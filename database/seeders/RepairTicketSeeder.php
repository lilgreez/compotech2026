<?php

namespace Database\Seeders;

use App\Models\MasterDieset;
use App\Models\MasterPart;
use App\Models\RepairTicket;
use App\Models\RepairTicketLog;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class RepairTicketSeeder extends Seeder
{
    public function run(): void
    {
        if (RepairTicket::count() > 0) {
            return;
        }

        // Ensure we have technician users
        $admin = User::first();
        if (!$admin) {
            return;
        }

        $tech1 = User::firstOrCreate(
            ['email' => 'agus.maintenance@compotech.com'],
            [
                'name' => 'Agus Setiawan (Mekanik)',
                'password' => Hash::make('password'),
            ]
        );

        $tech2 = User::firstOrCreate(
            ['email' => 'hendro.tooling@compotech.com'],
            [
                'name' => 'Hendro Wibowo (Teknisi)',
                'password' => Hash::make('password'),
            ]
        );

        // Fetch sample molds
        $molds = MasterDieset::with('parts')->take(10)->get();
        if ($molds->isEmpty()) {
            return;
        }

        $now = Carbon::now();

        $ticketsData = [
            [
                'mold_index' => 0,
                'priority' => 'Urgent',
                'status' => 'In Progress',
                'progress' => 65,
                'technician' => $tech1,
                'issue' => "Pin Ejector nomor 2 pada Cavity A mengalami deformasi bengkok dan macet pada saat proses demolding. Siklus produksi dihentikan sementara.",
                'tech_note' => "Pin rusak sudah dilepas. Sedang dilakukan penyesuaian lubang bushing dan persiapan pemasangan pin pengganti baru.",
                'days_ago' => 2,
            ],
            [
                'mold_index' => 1,
                'priority' => 'High',
                'status' => 'Pending Part',
                'progress' => 35,
                'technician' => $tech2,
                'issue' => "Terjadi kebocoran fluida pendingin pada jalur saluran naple cooling sisi kanan mold. Tekanan air turun drastis.",
                'tech_note' => "Seal O-Ring Vyton robek dan naple aus. Menunggu pengambilan suku cadang Seal O-Ring Vyton dari gudang spare part.",
                'days_ago' => 3,
            ],
            [
                'mold_index' => 2,
                'priority' => 'Medium',
                'status' => 'Open',
                'progress' => 0,
                'technician' => null,
                'issue' => "Ditemukan goresan halus (scoring mark) pada permukaan insert cavity setelah shoot ke-1.850.000. Perlu inspeksi visual dan lapping permukaan.",
                'tech_note' => null,
                'days_ago' => 1,
            ],
            [
                'mold_index' => 3,
                'priority' => 'Medium',
                'status' => 'In Progress',
                'progress' => 80,
                'technician' => $tech1,
                'issue' => "Pegas return spring terasa lemah sehingga pelat ejector lambat kembali ke posisi semula. Suara berderit saat return stroke.",
                'tech_note' => "Pegas lama sudah diganti dengan Spring CSL baru. Sedang tahap uji coba pergerakan mekanis tanpa beban.",
                'days_ago' => 4,
            ],
            [
                'mold_index' => 4,
                'priority' => 'Low',
                'status' => 'Open',
                'progress' => 0,
                'technician' => null,
                'issue' => "Jadwal pembersihan kerak air (descaling) pada seluruh naple cooling dan penggantian filter pendingin pra-shift.",
                'tech_note' => null,
                'days_ago' => 1,
            ],
            [
                'mold_index' => 5,
                'priority' => 'High',
                'status' => 'Resolved',
                'progress' => 100,
                'technician' => $tech2,
                'issue' => "Baut penahan slider piston longgar akibat getaran mesin diecast, menyebabkan posisi slider bergeser 0.3mm.",
                'tech_note' => "Baut diganti dengan Baut L grade 12.9 baru, dikencangkan sesuai torsi standar 60 Nm dan diberi threadlocker. Mold sudah lulus tes uji coba 50 shoot.",
                'days_ago' => 6,
            ],
            [
                'mold_index' => 6,
                'priority' => 'Medium',
                'status' => 'Resolved',
                'progress' => 100,
                'technician' => $tech1,
                'issue' => "Pembersihan saluran gasvent yang tersumbat residu aluminium. Terdapat cacat flash halus pada hasil coran.",
                'tech_note' => "Saluran gasvent dibersihkan dengan chemical cleaner dan ultrasonic bath. Hasil cetakan kembali presisi.",
                'days_ago' => 8,
            ],
            [
                'mold_index' => 7,
                'priority' => 'Urgent',
                'status' => 'In Progress',
                'progress' => 50,
                'technician' => $tech2,
                'issue' => "Sensor limit switch pengaman mold tidak memberikan sinyal ke kontroler PLC mesin. Mesin menolak menutup mold (interlock aktif).",
                'tech_note' => "Kabel sensor putus akibat terjepit mekanisme penutup. Sedang dilakukan rewiring kabel sensor shielded baru.",
                'days_ago' => 1,
            ],
        ];

        foreach ($ticketsData as $idx => $t) {
            $mold = $molds[$t['mold_index']] ?? $molds->first();
            $part = $mold->parts->isNotEmpty() ? $mold->parts->first() : null;

            $createdDate = (clone $now)->subDays($t['days_ago'])->subHours(rand(1, 8));
            $seqNumber = str_pad($idx + 1, 4, '0', STR_PAD_LEFT);
            $ticketNumber = "TKT-" . $createdDate->format('Ymd') . "-{$seqNumber}";

            $ticket = RepairTicket::create([
                'ticket_number' => $ticketNumber,
                'dieset_id' => $mold->id,
                'part_id' => $part ? $part->id : null,
                'reporter_user_id' => $admin->id,
                'technician_user_id' => $t['technician'] ? $t['technician']->id : null,
                'issue_description' => $t['issue'],
                'priority' => $t['priority'],
                'evidence_photo' => null,
                'status' => $t['status'],
                'progress_percent' => $t['progress'],
                'technician_notes' => $t['tech_note'],
                'resolved_at' => $t['status'] === 'Resolved' ? (clone $createdDate)->addHours(6) : null,
                'created_at' => $createdDate,
                'updated_at' => $createdDate,
            ]);

            // Initial log
            RepairTicketLog::create([
                'repair_ticket_id' => $ticket->id,
                'user_id' => $admin->id,
                'action' => 'Tiket Dibuat',
                'note' => "Tiket perbaikan diajukan oleh {$admin->name} untuk mold {$mold->name} dengan prioritas {$t['priority']}.",
                'created_at' => $createdDate,
                'updated_at' => $createdDate,
            ]);

            // Assignment log if assigned
            if ($t['technician']) {
                $assignDate = (clone $createdDate)->addMinutes(30);
                RepairTicketLog::create([
                    'repair_ticket_id' => $ticket->id,
                    'user_id' => $admin->id,
                    'action' => 'Penugasan Teknisi',
                    'note' => "Tiket ditugaskan kepada teknisi {$t['technician']->name}.",
                    'created_at' => $assignDate,
                    'updated_at' => $assignDate,
                ]);

                // Progress log if in progress or resolved
                if ($t['progress'] > 0) {
                    $progressDate = (clone $assignDate)->addHours(2);
                    RepairTicketLog::create([
                        'repair_ticket_id' => $ticket->id,
                        'user_id' => $t['technician']->id,
                        'action' => 'Update Progress',
                        'note' => "Progress mencapai {$t['progress']}%. Catatan: " . ($t['tech_note'] ?: "Penanganan teknis sedang berjalan."),
                        'created_at' => $progressDate,
                        'updated_at' => $progressDate,
                    ]);
                }
            }
        }
    }
}
