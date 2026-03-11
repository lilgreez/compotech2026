<?php

namespace App\Http\Controllers;

use App\Models\MasterInspection;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MasterInspectionController extends Controller
{
    public function index(Request $request): View
    {
        // Parameter tab: kerusakan, tindakan, alasan
        $tab = $request->query('tab', 'kerusakan');
        $search = $request->query('search');
        $perPage = $request->query('per_page', 10);

        $query = MasterInspection::where('type', $tab);

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $inspections = $query->orderBy('id', 'desc')->paginate($perPage);

        return view('admin.master-inspections.index', compact('inspections', 'tab', 'search', 'perPage'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:kerusakan,tindakan,alasan',
        ]);

        MasterInspection::create([
            'name' => $request->name,
            'type' => $request->type,
            'part_id' => null, // Global master data
            'status' => 'active'
        ]);

        return back()->with('success', 'Data berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $inspection = MasterInspection::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $inspection->update([
            'name' => $request->name
        ]);

        return back()->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy($id)
    {
        MasterInspection::findOrFail($id)->delete();
        return back()->with('success', 'Data berhasil dihapus!');
    }
}