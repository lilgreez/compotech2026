<?php

namespace App\Http\Controllers;

use App\Models\MasterDieset;
use App\Models\InspectionHistory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Exports\InspectionMonitorExport;
use Maatwebsite\Excel\Facades\Excel;

class InspectionController extends Controller
{
    public function index(): View
    {
        return redirect()->route('monitoring');
    }

    public function show($id)
    {
        return redirect()->route('monitoring.show', $id);
    }

    public function create(): View
    {
        return redirect()->route('monitoring');
    }

    public function store(Request $request)
    {
        // Reserved for future mobile app API / direct log inputs
    }

    // =========================================================================
    // FITUR: INSPECTION MONITOR (Halaman 12 PDF)
    // =========================================================================
    public function monitoring(Request $request): View
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = $request->input('search');
        $conditionFilter = $request->input('condition', 'all'); // 'all', 'OK', 'Repair', 'Replace'
        $perPage = (int) $request->input('per_page', 10);

        // Overall KPI Metrics for Inspection Monitor
        $kpiQuery = InspectionHistory::query();
        if ($startDate) {
            $kpiQuery->whereDate('inspection_date', '>=', $startDate);
        }
        if ($endDate) {
            $kpiQuery->whereDate('inspection_date', '<=', $endDate);
        }

        $totalInspections = (clone $kpiQuery)->count();
        $okCount = (clone $kpiQuery)->where('condition', 'OK')->count();
        $repairCount = (clone $kpiQuery)->where('condition', 'Repair')->count();
        $replaceCount = (clone $kpiQuery)->where('condition', 'Replace')->count();

        // Total molds that have at least one inspection
        $inspectedMoldsCount = MasterDieset::whereHas('inspectionHistories')->count();
        $totalMolds = MasterDieset::count();

        // Main Query
        $query = MasterDieset::query();

        // Date range filter
        $query->withCount(['inspectionHistories as inspection_count' => function ($q) use ($startDate, $endDate, $conditionFilter) {
            if ($startDate) $q->whereDate('inspection_date', '>=', $startDate);
            if ($endDate) $q->whereDate('inspection_date', '<=', $endDate);
            if ($conditionFilter && $conditionFilter !== 'all') {
                $q->where('condition', $conditionFilter);
            }
        }]);

        // If condition filter applied or date range applied
        if ($conditionFilter && $conditionFilter !== 'all') {
            $query->whereHas('inspectionHistories', function ($q) use ($startDate, $endDate, $conditionFilter) {
                if ($startDate) $q->whereDate('inspection_date', '>=', $startDate);
                if ($endDate) $q->whereDate('inspection_date', '<=', $endDate);
                $q->where('condition', $conditionFilter);
            });
        } elseif ($startDate || $endDate) {
            $query->whereHas('inspectionHistories', function ($q) use ($startDate, $endDate) {
                if ($startDate) $q->whereDate('inspection_date', '>=', $startDate);
                if ($endDate) $q->whereDate('inspection_date', '<=', $endDate);
            });
        }

        // Search Filter across name, kode_mold, ownership, cavity
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('kode_mold', 'like', "%{$search}%")
                  ->orWhere('ownership', 'like', "%{$search}%")
                  ->orWhere('cavity', 'like', "%{$search}%");
            });
        }

        // Eager load latest inspection history to show recent status & date
        $query->with(['inspectionHistories' => function ($q) use ($startDate, $endDate) {
            if ($startDate) $q->whereDate('inspection_date', '>=', $startDate);
            if ($endDate) $q->whereDate('inspection_date', '<=', $endDate);
            $q->latest('inspection_date')->limit(1)->with('operator');
        }]);

        // Order by inspection_count desc, then name asc
        $diesets = $query->orderBy('inspection_count', 'desc')
                         ->orderBy('name', 'asc')
                         ->paginate($perPage)
                         ->withQueryString();

        return view('inspection-monitor.index', compact(
            'diesets',
            'startDate',
            'endDate',
            'search',
            'conditionFilter',
            'perPage',
            'totalInspections',
            'okCount',
            'repairCount',
            'replaceCount',
            'inspectedMoldsCount',
            'totalMolds'
        ));
    }

    // =========================================================================
    // FITUR: DETAIL INSPECTION MONITOR (Halaman 13 PDF)
    // =========================================================================
    public function showMonitoring(Request $request, $id): View
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $conditionFilter = $request->input('condition', 'all');

        $dieset = MasterDieset::with(['parts.inspectionHistories' => function ($q) use ($startDate, $endDate, $conditionFilter) {
            if ($startDate) $q->whereDate('inspection_date', '>=', $startDate);
            if ($endDate) $q->whereDate('inspection_date', '<=', $endDate);
            if ($conditionFilter && $conditionFilter !== 'all') {
                $q->where('condition', $conditionFilter);
            }
            $q->orderBy('inspection_date', 'desc');
        }, 'parts.inspectionHistories.operator'])->findOrFail($id);

        // Gather all histories for this mold to compute stats
        $allHistories = collect();
        foreach ($dieset->parts as $part) {
            foreach ($part->inspectionHistories as $history) {
                $allHistories->push($history);
            }
        }

        $totalMoldInspections = $allHistories->count();
        $okCount = $allHistories->where('condition', 'OK')->count();
        $repairCount = $allHistories->where('condition', 'Repair')->count();
        $replaceCount = $allHistories->where('condition', 'Replace')->count();

        return view('inspection-monitor.show', compact(
            'dieset',
            'startDate',
            'endDate',
            'conditionFilter',
            'totalMoldInspections',
            'okCount',
            'repairCount',
            'replaceCount'
        ));
    }

    // =========================================================================
    // FITUR: EXPORT EXCEL INSPECTION MONITOR (Halaman 14 PDF)
    // =========================================================================
    public function export(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        $fileName = 'Inspection_Monitor_' . date('Ymd_His') . '.xlsx';
        
        return Excel::download(new InspectionMonitorExport($startDate, $endDate), $fileName);
    }
}