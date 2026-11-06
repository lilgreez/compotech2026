<?php

namespace Database\Seeders;

use App\Models\MasterDieset;
use App\Models\MasterPart;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MoldComponentSeeder extends Seeder
{
    public function run(): void
    {
        $diesets = MasterDieset::all();

        // Get catalog spare parts grouped by item type
        $neeples = MasterPart::whereNull('dieset_id')
            ->where('name', 'like', '%Neeple%')
            ->get();

        $seals = MasterPart::whereNull('dieset_id')
            ->where('name', 'like', '%Seal O Ring%')
            ->get();

        $ejectorPins = MasterPart::whereNull('dieset_id')
            ->where('name', 'like', '%Pin Ejector%')
            ->get();

        $springs = MasterPart::whereNull('dieset_id')
            ->where('name', 'like', '%Spring%')
            ->get();

        $fasteners = MasterPart::whereNull('dieset_id')
            ->where(function ($q) {
                $q->where('name', 'like', '%Baut%')
                  ->orWhere('name', 'like', '%Insert%');
            })
            ->get();

        $now = now();
        $records = [];

        foreach ($diesets as $dieset) {
            // Check if already has parts
            $existingCount = MasterPart::where('dieset_id', $dieset->id)->count();
            if ($existingCount > 0) {
                continue;
            }

            $cavityInt = (int) preg_replace('/[^0-9]/', '', $dieset->cavity ?: '4') ?: 4;

            // 1. Cooling System: Neeple
            $neeple = null;
            if ($dieset->ukuran_naple) {
                $cleanUkuran = trim($dieset->ukuran_naple);
                $neeple = $neeples->first(function ($item) use ($cleanUkuran) {
                    return stripos($item->name, $cleanUkuran) !== false;
                });
            }
            if (!$neeple && $neeples->count() > 0) {
                $neeple = $neeples->random();
            }

            if ($neeple) {
                $shoot = rand(200000, 2400000);
                $records[] = [
                    'dieset_id' => $dieset->id,
                    'category' => 'Cooling System',
                    'cavity_number' => 1,
                    'part_code' => $neeple->part_code,
                    'name' => $neeple->name,
                    'description' => 'Cooling connector naple: ' . ($dieset->ukuran_naple ?: 'Standard'),
                    'actual_shoot' => $shoot,
                    'max_shoot' => 3000000,
                    'current_stock' => $neeple->current_stock,
                    'status' => 'active',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            // 2. Cooling System: Seal O Ring
            if ($seals->count() > 0) {
                $seal = $seals->random();
                $records[] = [
                    'dieset_id' => $dieset->id,
                    'category' => 'Cooling System',
                    'cavity_number' => 1,
                    'part_code' => $seal->part_code,
                    'name' => $seal->name,
                    'description' => 'Water seal channel ring',
                    'actual_shoot' => rand(150000, 2100000),
                    'max_shoot' => 3000000,
                    'current_stock' => $seal->current_stock,
                    'status' => 'active',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            // 3. Ejection System: Pin Ejector
            if ($ejectorPins->count() > 0) {
                $pin = $ejectorPins->random();
                $records[] = [
                    'dieset_id' => $dieset->id,
                    'category' => 'Ejection System',
                    'cavity_number' => min(2, $cavityInt),
                    'part_code' => $pin->part_code,
                    'name' => $pin->name,
                    'description' => 'Main ejector pin for mold core',
                    'actual_shoot' => rand(300000, 2600000),
                    'max_shoot' => 3000000,
                    'current_stock' => $pin->current_stock,
                    'status' => 'active',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            // 4. Ejection System: Return Spring
            if ($springs->count() > 0) {
                $spring = $springs->random();
                $records[] = [
                    'dieset_id' => $dieset->id,
                    'category' => 'Ejection System',
                    'cavity_number' => 1,
                    'part_code' => $spring->part_code,
                    'name' => $spring->name,
                    'description' => 'Ejector return coil spring',
                    'actual_shoot' => rand(250000, 2200000),
                    'max_shoot' => 3000000,
                    'current_stock' => $spring->current_stock,
                    'status' => 'active',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            // 5. Mechanical & Fasteners
            if ($fasteners->count() > 0) {
                $fastener = $fasteners->random();
                $records[] = [
                    'dieset_id' => $dieset->id,
                    'category' => 'Mechanical & Fasteners',
                    'cavity_number' => 1,
                    'part_code' => $fastener->part_code,
                    'name' => $fastener->name,
                    'description' => 'Clamping & assembly fastener',
                    'actual_shoot' => rand(100000, 1800000),
                    'max_shoot' => 3000000,
                    'current_stock' => $fastener->current_stock,
                    'status' => 'active',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        if (!empty($records)) {
            // Insert in chunks of 100 for maximum performance
            foreach (array_chunk($records, 100) as $chunk) {
                DB::table('master_parts')->insert($chunk);
            }
        }
    }
}
