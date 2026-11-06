<?php

namespace Database\Seeders;

use App\Models\MasterDieset;
use App\Models\MasterPart;
use App\Models\User;
use App\Models\InspectionHistory;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class InspectionHistorySeeder extends Seeder
{
    public function run(): void
    {
        $existingCount = InspectionHistory::count();
        if ($existingCount > 0) {
            return;
        }

        $operator = User::first();
        if (!$operator) {
            return;
        }

        // Fetch molds with their parts
        $molds = MasterDieset::with('parts')->whereHas('parts')->get();
        if ($molds->isEmpty()) {
            return;
        }

        $sampleActions = [
            'OK' => [
                ['reason' => 'Inspeksi berkala shift 1', 'action' => 'Pemeriksaan visual & pembersihan rutin', 'damage' => 'Kondisi komponen bersih & presisi tanpa keausan'],
                ['reason' => 'Pemeriksaan pra-produksi', 'action' => 'Pengecekan kelancaran gerak pin & saluran air', 'damage' => 'Tidak ditemukan keausan atau penyumbatan'],
                ['reason' => 'Inspeksi pencegahan (preventive)', 'action' => 'Pelumasan berkala pelumas tahan panas', 'damage' => 'Permukaan pin dan bushing dalam toleransi normal'],
            ],
            'Repair' => [
                ['reason' => 'Gerakan ejector agak tersendat', 'action' => 'Pembersihan kerak karbon & penyesuaian guide pin', 'damage' => 'Terdapat sedikit goresan halus pada permukaan shaft'],
                ['reason' => 'Aliran cooling terhambat', 'action' => 'Flushing saluran naple & pembersihan kerak air', 'damage' => 'Endapan kapur pada naple saluran pendingin'],
                ['reason' => 'Baut pengunci agak kendur', 'action' => 'Retorque baut pengikat sesuai standar torsi 45 Nm', 'damage' => 'Vibrasi operasional menyebabkan penurunan torsi baut'],
            ],
            'Replace' => [
                ['reason' => 'Kebocoran fluida pendingin', 'action' => 'Penggantian Seal O-Ring Vyton baru dari stok gudang', 'damage' => 'Seal getas dan robek akibat paparan suhu tinggi'],
                ['reason' => 'Pin bengkok saat siklus eject', 'action' => 'Penggantian Pin Ejector baru dan alignment lubang', 'damage' => 'Deformasi plastis pada ujung pin ejector'],
                ['reason' => 'Tegangan pegas menurun drastis', 'action' => 'Penggantian Spring CSL baru', 'damage' => 'Pegas kelelahan material (fatigue) setelah 1.5jt siklus'],
            ]
        ];

        $records = [];
        $now = Carbon::now();

        // Seed 40 to 60 molds with 2 to 5 inspections each over the last 45 days
        foreach ($molds as $index => $mold) {
            // Seed about 45 molds with inspection histories
            if ($index > 45) {
                break;
            }

            $numInspections = rand(2, 5);

            for ($i = 0; $i < $numInspections; $i++) {
                $daysAgo = rand(1, 45);
                $inspectionDate = (clone $now)->subDays($daysAgo)->subHours(rand(1, 10))->subMinutes(rand(1, 55));

                // Randomly pick a part from this mold
                $part = $mold->parts->random();

                // Condition distribution: 70% OK, 20% Repair, 10% Replace
                $randCond = rand(1, 100);
                if ($randCond <= 70) {
                    $condition = 'OK';
                } elseif ($randCond <= 90) {
                    $condition = 'Repair';
                } else {
                    $condition = 'Replace';
                }

                $template = $sampleActions[$condition][array_rand($sampleActions[$condition])];
                $partsShoot = rand(250000, 2400000);
                $totalShoot = $mold->total_shoot > 0 ? $mold->total_shoot : rand(1200000, 3500000);

                $records[] = [
                    'part_id' => $part->id,
                    'operator_id' => $operator->id,
                    'inspection_date' => $inspectionDate,
                    'parts_shoot' => $partsShoot,
                    'total_shoot' => $totalShoot,
                    'condition' => $condition,
                    'damage_details' => $template['damage'],
                    'action_taken' => $template['action'],
                    'reason' => $template['reason'],
                    'evidence_photo_path' => null,
                    'report_sent' => rand(0, 1),
                    'created_at' => $inspectionDate,
                    'updated_at' => $inspectionDate,
                ];
            }
        }

        if (!empty($records)) {
            // Sort by inspection_date desc
            usort($records, fn($a, $b) => strcmp($b['inspection_date']->format('Y-m-d H:i:s'), $a['inspection_date']->format('Y-m-d H:i:s')));

            foreach (array_chunk($records, 50) as $chunk) {
                InspectionHistory::insert($chunk);
            }
        }
    }
}
