<?php

namespace App\Http\Controllers;

use App\Models\MasterDieset;
use App\Models\MasterPart;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MasterDiesetController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $ownership = $request->input('ownership');
        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }

        $query = MasterDieset::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('kode_mold', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('ownership', 'like', "%{$search}%")
                  ->orWhere('product_code', 'like', "%{$search}%")
                  ->orWhere('dimensi_mold', 'like', "%{$search}%")
                  ->orWhere('type_cavity', 'like', "%{$search}%");
            });
        }

        if ($status && in_array($status, ['Active', 'NA'])) {
            $query->where('status', $status);
        }

        if ($ownership) {
            $query->where('ownership', $ownership);
        }

        $diesets = $query->orderBy('id', 'desc')->paginate($perPage);

        $stats = [
            'total' => MasterDieset::count(),
            'active' => MasterDieset::where('status', 'Active')->count(),
            'na' => MasterDieset::where('status', 'NA')->count(),
            'customers' => MasterDieset::whereNotNull('ownership')->where('ownership', '!=', '')->distinct('ownership')->count('ownership'),
        ];

        $ownershipList = MasterDieset::whereNotNull('ownership')
            ->where('ownership', '!=', '')
            ->distinct()
            ->orderBy('ownership')
            ->pluck('ownership');

        return view('admin.master-diesets.index', compact('diesets', 'search', 'status', 'ownership', 'perPage', 'stats', 'ownershipList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode_mold' => 'nullable|string|max:100',
            'name' => 'required|string|max:255',
            'cavity' => 'nullable|string|max:50',
            'status' => 'required|string|in:Active,NA',
            'ownership' => 'nullable|string|max:255',
            'type_cavity' => 'nullable|string|max:50',
            'production_year' => 'nullable|string|max:50',
            'dimensi_mold' => 'nullable|string|max:100',
            'diameter_lokating_ring' => 'nullable|string|max:50',
            'type_cooling' => 'nullable|string|max:50',
            'type_eject' => 'nullable|string|max:50',
            'type_gate' => 'nullable|string|max:50',
            'type_mold' => 'nullable|string|max:50',
            'ukuran_naple' => 'nullable|string|max:50',
            'qty_naple' => 'nullable|string|max:50',
            'naple_std_long' => 'nullable|string|max:50',
            'jumlah_cavity_std' => 'nullable|string|max:50',
            'jumlah_piston_slider' => 'nullable|string|max:50',
            'jumlah_gasvent' => 'nullable|string|max:50',
            'safety_mold' => 'nullable|string|max:100',
            'qty_temp_channel_hr' => 'nullable|string|max:100',
            'total_shoot' => 'nullable|integer|min:0',
            'product_code' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        if (empty($validated['status'])) {
            $validated['status'] = 'Active';
        }
        if (!isset($validated['total_shoot'])) {
            $validated['total_shoot'] = 0;
        }

        MasterDieset::create($validated);
        return redirect()->route('master-diesets.index')->with('success', 'Data Master Mold berhasil ditambahkan!');
    }

    public function update(Request $request, MasterDieset $masterDieset): RedirectResponse
    {
        $validated = $request->validate([
            'kode_mold' => 'nullable|string|max:100',
            'name' => 'required|string|max:255',
            'cavity' => 'nullable|string|max:50',
            'status' => 'required|string|in:Active,NA',
            'ownership' => 'nullable|string|max:255',
            'type_cavity' => 'nullable|string|max:50',
            'production_year' => 'nullable|string|max:50',
            'dimensi_mold' => 'nullable|string|max:100',
            'diameter_lokating_ring' => 'nullable|string|max:50',
            'type_cooling' => 'nullable|string|max:50',
            'type_eject' => 'nullable|string|max:50',
            'type_gate' => 'nullable|string|max:50',
            'type_mold' => 'nullable|string|max:50',
            'ukuran_naple' => 'nullable|string|max:50',
            'qty_naple' => 'nullable|string|max:50',
            'naple_std_long' => 'nullable|string|max:50',
            'jumlah_cavity_std' => 'nullable|string|max:50',
            'jumlah_piston_slider' => 'nullable|string|max:50',
            'jumlah_gasvent' => 'nullable|string|max:50',
            'safety_mold' => 'nullable|string|max:100',
            'qty_temp_channel_hr' => 'nullable|string|max:100',
            'total_shoot' => 'nullable|integer|min:0',
            'product_code' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $masterDieset->update($validated);
        return redirect()->route('master-diesets.index')->with('success', 'Data Master Mold berhasil diperbarui!');
    }

    public function destroy(MasterDieset $masterDieset): RedirectResponse
    {
        if ($masterDieset->parts()->count() > 0) {
            return redirect()->route('master-diesets.index')->with('error', 'Gagal! Mold tidak bisa dihapus karena memiliki item Parts.');
        }
        $masterDieset->delete();
        return redirect()->route('master-diesets.index')->with('success', 'Data Master Mold berhasil dihapus!');
    }

    // =========================================================================
    // DETAIL ITEM PARTS (Sesuai Spesifikasi Teknis & Komponen Mold)
    // =========================================================================
    
    public function show($id): View
    {
        $dieset = MasterDieset::with('parts')->findOrFail($id);
        
        // Mengelompokkan parts berdasarkan kategori agar UI Table bisa di-rowspan
        $groupedParts = $dieset->parts->groupBy('category');

        return view('admin.master-diesets.show', compact('dieset', 'groupedParts'));
    }

    public function storeGeneratedParts(Request $request, $id)
    {
        $dieset = MasterDieset::findOrFail($id);
        
        $request->validate([
            'category' => 'required|string|max:255',
            'parts' => 'required|array',
            'parts.*.cavity' => 'required|integer',
            'parts.*.code' => 'required|string|max:255',
            'parts.*.name' => 'required|string|max:255',
            'parts.*.desc' => 'nullable|string|max:255',
            'parts.*.shoot' => 'required|integer|min:0',
        ]);

        foreach ($request->parts as $partData) {
            $dieset->parts()->create([
                'category' => $request->category,
                'cavity_number' => $partData['cavity'],
                'part_code' => $partData['code'],
                'name' => $partData['name'],
                'description' => $partData['desc'],
                'actual_shoot' => $partData['shoot'],
                'max_shoot' => 3000000,
                'current_stock' => 0,
                'status' => 'active'
            ]);
        }

        return back()->with('success', 'Item Parts berhasil di-generate dan disimpan!');
    }

    public function updatePart(Request $request, $dieset_id, $part_id)
    {
        $part = MasterPart::where('dieset_id', $dieset_id)->findOrFail($part_id);
        
        $validated = $request->validate([
            'part_code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'actual_shoot' => 'required|integer|min:0',
        ]);

        $part->update($validated);
        return back()->with('success', 'Data Part berhasil diperbarui!');
    }

    public function destroyPart($dieset_id, $part_id)
    {
        $part = MasterPart::where('dieset_id', $dieset_id)->findOrFail($part_id);
        $part->delete();
        return back()->with('success', 'Data Part berhasil dihapus!');
    }
}