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
        $perPage = $request->input('per_page', 10);
        $query = MasterDieset::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%")->orWhere('product_code', 'like', "%{$search}%");
        }

        $diesets = $query->orderBy('id', 'desc')->paginate($perPage);
        return view('admin.master-diesets.index', compact('diesets', 'search', 'perPage'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:master_diesets',
            'product_code' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255', 
            'total_shoot' => 'nullable|integer|min:0',  
            'production_year' => 'nullable|integer', 
        ]);
        MasterDieset::create($validated);
        return redirect()->route('master-diesets.index')->with('success', 'Data Dieset berhasil ditambahkan!');
    }

    public function update(Request $request, MasterDieset $masterDieset): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:master_diesets,name,' . $masterDieset->id,
            'product_code' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'total_shoot' => 'nullable|integer|min:0',
            'production_year' => 'nullable|integer',
        ]);
        $masterDieset->update($validated);
        return redirect()->route('master-diesets.index')->with('success', 'Data Dieset berhasil diperbarui!');
    }

    public function destroy(MasterDieset $masterDieset): RedirectResponse
    {
        if ($masterDieset->parts()->count() > 0) {
            return redirect()->route('master-diesets.index')->with('error', 'Gagal! Dieset tidak bisa dihapus karena memiliki Parts.');
        }
        $masterDieset->delete();
        return redirect()->route('master-diesets.index')->with('success', 'Data Dieset berhasil dihapus!');
    }

    // =========================================================================
    // FITUR ADVANCED: DETAIL ITEM PARTS (Halaman 21-24 PDF)
    // =========================================================================
    
    public function show($id): View
    {
        $dieset = MasterDieset::with('parts')->findOrFail($id);
        
        // Mengelompokkan parts berdasarkan kategori agar UI Table bisa di-rowspan (Hal 21 PDF)
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

        // Menyimpan semua baris yang digenerate oleh Alpine JS
        foreach ($request->parts as $partData) {
            $dieset->parts()->create([
                'category' => $request->category,
                'cavity_number' => $partData['cavity'],
                'part_code' => $partData['code'],
                'name' => $partData['name'],
                'description' => $partData['desc'],
                'actual_shoot' => $partData['shoot'],
                'max_shoot' => 3000000, // Sesuai PDF default 3.000.000
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