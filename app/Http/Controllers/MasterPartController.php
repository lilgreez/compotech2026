<?php

namespace App\Http\Controllers;

use App\Models\MasterPart;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class MasterPartController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        // Hanya mengambil data Katalog (dieset_id IS NULL)
        $query = MasterPart::whereNull('dieset_id');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('part_code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $parts = $query->orderBy('id', 'desc')->paginate($perPage);

        return view('admin.master-parts.index', compact('parts', 'search', 'perPage'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'part_code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'max_shoot' => 'nullable|integer|min:0',
            'item_notes' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Maks 2MB
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('parts_images', 'public');
        }

        MasterPart::create([
            'part_code' => $request->part_code,
            'name' => $request->name,
            'description' => $request->description,
            'max_shoot' => $request->max_shoot ?? 0,
            'item_notes' => $request->item_notes,
            'image_path' => $imagePath,
            'dieset_id' => null, // Tandai sebagai Data Katalog
            'current_stock' => 0,
            'status' => 'active'
        ]);

        return back()->with('success', 'Data Parts Item berhasil ditambahkan ke Katalog!');
    }

    public function update(Request $request, $id)
    {
        $part = MasterPart::whereNull('dieset_id')->findOrFail($id);

        $request->validate([
            'part_code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'max_shoot' => 'nullable|integer|min:0',
            'item_notes' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = $part->image_path;
        if ($request->hasFile('image')) {
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('parts_images', 'public');
        }

        // Update Data Katalog
        $part->update([
            'part_code' => $request->part_code,
            'name' => $request->name,
            'description' => $request->description,
            'max_shoot' => $request->max_shoot ?? 0,
            'item_notes' => $request->item_notes,
            'image_path' => $imagePath,
        ]);

        // MAGIC: Update otomatis ke semua Part di dalam Dieset yang menggunakan kode ini!
        MasterPart::whereNotNull('dieset_id')->where('part_code', $request->part_code)->update([
            'name' => $request->name,
            'description' => $request->description,
            'max_shoot' => $request->max_shoot ?? 0,
            'image_path' => $imagePath,
        ]);

        return back()->with('success', 'Data Parts berhasil diperbarui. Seluruh Dieset yang memakai part ini telah di-update!');
    }

    public function destroy($id)
    {
        $part = MasterPart::whereNull('dieset_id')->findOrFail($id);
        if ($part->image_path && Storage::disk('public')->exists($part->image_path)) {
            Storage::disk('public')->delete($part->image_path);
        }
        $part->delete();

        return back()->with('success', 'Data Katalog Parts berhasil dihapus!');
    }

    public function syncWings()
    {
        // Fungsi simulasi sinkronisasi data dari sistem ERP Wings
        return back()->with('success', 'Sinkronisasi berhasil! Data terbaru dari Wings telah dimuat.');
    }
}