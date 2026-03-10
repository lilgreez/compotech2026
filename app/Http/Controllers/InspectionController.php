<?php

namespace App\Http\Controllers;

use App\Models\MasterDieset;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Exports\InspectionMonitorExport;
use Maatwebsite\Excel\Facades\Excel;

class InspectionController extends Controller
{
    public function index(): View
    {
        // View default untuk halaman index inspections (bisa dikembangkan nanti)
        return view('dashboard');
    }

    public function show($id)
    {
        return view('dashboard');
    }

    public function create(): View
    {
        return view('dashboard');
    }

    public function store(Request $request)
    {
        // Logika simpan data dari Input Manual atau API Mobile App nantinya
    }

    // =========================================================================
    // FITUR: INSPECTION MONITOR (Halaman 12 PDF)
    // =========================================================================
    public function monitoring(Request $request): View
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $perPage = $request->input('per_page', 10);

        // Hanya tampilkan Dieset yang memiliki riwayat inspeksi pada rentang tanggal tersebut
        $query = MasterDieset::whereHas('inspectionHistories', function($q) use ($startDate, $endDate) {
            if ($startDate) $q->whereDate('inspection_date', '>=', $startDate);
            if ($endDate) $q->whereDate('inspection_date', '<=', $endDate);
        })->withCount(['inspectionHistories as inspection_count' => function($q) use ($startDate, $endDate) {
            // Hitung total inspeksi untuk masing-masing Dieset tanpa meload datanya ke RAM
            if ($startDate) $q->whereDate('inspection_date', '>=', $startDate);
            if ($endDate) $q->whereDate('inspection_date', '<=', $endDate);
        }]);

        $diesets = $query->paginate($perPage);

        return view('inspection-monitor.index', compact('diesets', 'startDate', 'endDate', 'perPage'));
    }

    // =========================================================================
    // FITUR: DETAIL INSPECTION MONITOR (Halaman 13 PDF)
    // =========================================================================
    public function showMonitoring(Request $request, $id): View
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Tarik Dieset berserta parts dan histori inspeksinya
        // Diurutkan berdasarkan tanggal inspeksi paling baru (desc)
        $dieset = MasterDieset::with(['parts.inspectionHistories' => function($q) use ($startDate, $endDate) {
            if ($startDate) $q->whereDate('inspection_date', '>=', $startDate);
            if ($endDate) $q->whereDate('inspection_date', '<=', $endDate);
            $q->orderBy('inspection_date', 'desc');
        }, 'parts.inspectionHistories.operator'])->findOrFail($id);

        return view('inspection-monitor.show', compact('dieset'));
    }

    // =========================================================================
    // FITUR: EXPORT EXCEL INSPECTION MONITOR (Halaman 14 PDF)
    // =========================================================================
    public function export(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        $fileName = 'Inspection_Monitor_' . date('Ymd_His') . '.xlsx';
        
        // Memanggil class Export yang telah kita buat sebelumnya
        return Excel::download(new InspectionMonitorExport($startDate, $endDate), $fileName);
    }
}