<?php

namespace App\Http\Controllers;

use App\Models\MasterDieset;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Exports\DiesetStatusExport;
use Maatwebsite\Excel\Facades\Excel;

class DiesetStatusController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10); // Default 10 sesuai PDF
        
        // ANDREW OPTIMIZATION: Menggunakan withCount untuk menghitung stok kurang tanpa meload semua data part ke RAM
        $query = MasterDieset::withCount(['parts as low_stock_count' => function ($query) {
            $query->where('current_stock', '<=', 2); // Anggap batas aman adalah > 2
        }]);
        
        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('product_code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        $diesets = $query->paginate($perPage);

        return view('dieset-status.index', compact('diesets', 'search', 'perPage'));
    }

    public function show($id): View
    {
        // Sesuai PDF Halaman 10: Menampilkan Detail Dieset beserta list Parts
        $dieset = MasterDieset::with('parts')->findOrFail($id);
        
        // Grouping parts berdasarkan kategori
        $groupedParts = $dieset->parts->groupBy('category');

        return view('dieset-status.show', compact('dieset', 'groupedParts'));
    }

    public function export()
    {
        // Fitur Export persis Halaman 11 PDF
        return Excel::download(new DiesetStatusExport, 'Stock_Dieset_Parts_'.date('Ymd_His').'.xlsx');
    }
}