<?php

namespace App\Http\Controllers;

use App\Models\MasterPart;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

class MasterPartController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }

        $stockFilter = $request->input('stock_filter', 'all');

        // Base query for catalog items (dieset_id IS NULL)
        $catalogQuery = MasterPart::whereNull('dieset_id');

        // Summary KPI stats
        $totalParts = (clone $catalogQuery)->count();
        $totalStock = (clone $catalogQuery)->sum('current_stock');
        $criticalStock = (clone $catalogQuery)->where('current_stock', '<=', 2)->count();
        $safeStock = (clone $catalogQuery)->where('current_stock', '>', 2)->count();

        // Filtered query for the table
        $query = MasterPart::whereNull('dieset_id');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('part_code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($stockFilter === 'critical') {
            $query->where('current_stock', '<=', 2);
        } elseif ($stockFilter === 'safe') {
            $query->where('current_stock', '>', 2);
        } elseif ($stockFilter === 'empty') {
            $query->where('current_stock', '<=', 0);
        }

        $parts = $query->orderBy('id', 'asc')->paginate($perPage);

        return view('admin.master-parts.index', compact(
            'parts',
            'search',
            'perPage',
            'stockFilter',
            'totalParts',
            'totalStock',
            'criticalStock',
            'safeStock'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'part_code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'current_stock' => 'nullable|integer|min:0',
            'max_shoot' => 'nullable|integer|min:0',
            'item_notes' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('parts_images', 'public');
        }

        MasterPart::create([
            'part_code' => trim($request->part_code),
            'name' => trim($request->name),
            'description' => $request->description ? trim($request->description) : trim($request->name),
            'current_stock' => $request->current_stock ?? 0,
            'max_shoot' => $request->max_shoot ?? 3000000,
            'actual_shoot' => 0,
            'item_notes' => $request->item_notes,
            'image_path' => $imagePath,
            'dieset_id' => null, // Tandai sebagai Data Katalog Master
            'status' => 'active'
        ]);

        return back()->with('success', 'Data Master Part berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $part = MasterPart::whereNull('dieset_id')->findOrFail($id);

        $request->validate([
            'part_code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'current_stock' => 'nullable|integer|min:0',
            'max_shoot' => 'nullable|integer|min:0',
            'item_notes' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePath = $part->image_path;
        if ($request->hasFile('image')) {
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('parts_images', 'public');
        }

        // Update Data Katalog Master
        $part->update([
            'part_code' => trim($request->part_code),
            'name' => trim($request->name),
            'description' => $request->description ? trim($request->description) : trim($request->name),
            'current_stock' => $request->current_stock ?? 0,
            'max_shoot' => $request->max_shoot ?? 3000000,
            'item_notes' => $request->item_notes,
            'image_path' => $imagePath,
        ]);

        // Opsional sinkronisasi nama/deskripsi ke part yang terpasang di Mold
        MasterPart::whereNotNull('dieset_id')->where('part_code', $request->part_code)->update([
            'name' => trim($request->name),
            'description' => $request->description ? trim($request->description) : trim($request->name),
            'max_shoot' => $request->max_shoot ?? 3000000,
            'image_path' => $imagePath,
        ]);

        return back()->with('success', 'Data Master Part berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $part = MasterPart::whereNull('dieset_id')->findOrFail($id);
        if ($part->image_path && Storage::disk('public')->exists($part->image_path)) {
            Storage::disk('public')->delete($part->image_path);
        }
        $part->delete();

        return back()->with('success', 'Data Master Part berhasil dihapus!');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:20480',
        ]);

        $file = $request->file('excel_file');
        
        try {
            $spreadsheet = IOFactory::load($file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            $imported = 0;
            $updated = 0;

            foreach (array_slice($rows, 1) as $row) {
                $itemNo = isset($row[1]) ? trim((string)$row[1]) : '';
                $desc = isset($row[2]) ? trim((string)$row[2]) : '';
                $qty = isset($row[3]) && is_numeric($row[3]) ? (int)round($row[3]) : 0;

                if (empty($itemNo)) {
                    continue;
                }

                $part = MasterPart::whereNull('dieset_id')->where('part_code', $itemNo)->first();
                if ($part) {
                    $part->update([
                        'name' => $desc ?: $itemNo,
                        'description' => $desc,
                        'current_stock' => $qty,
                    ]);
                    $updated++;
                } else {
                    MasterPart::create([
                        'part_code' => $itemNo,
                        'name' => $desc ?: $itemNo,
                        'description' => $desc,
                        'current_stock' => $qty,
                        'max_shoot' => 3000000,
                        'actual_shoot' => 0,
                        'dieset_id' => null,
                        'status' => 'active',
                    ]);
                    $imported++;
                }
            }

            return back()->with('success', "Import data Excel berhasil! {$imported} part baru ditambahkan, {$updated} part diperbarui.");
        } catch (\Exception $e) {
            return back()->withErrors(['excel_file' => 'Gagal membaca berkas Excel: ' . $e->getMessage()]);
        }
    }

    public function syncWings()
    {
        return back()->with('success', 'Sinkronisasi berhasil! Data terbaru dari Wings telah dimuat.');
    }
}