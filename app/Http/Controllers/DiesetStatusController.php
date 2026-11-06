<?php

namespace App\Http\Controllers;

use App\Models\MasterDieset;
use App\Models\MasterPart;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Exports\DiesetStatusExport;
use Maatwebsite\Excel\Facades\Excel;

class DiesetStatusController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $statusFilter = $request->input('status', 'all'); // 'all', 'safe', 'warning'
        $ownershipFilter = $request->input('ownership', 'all');
        $perPage = (int) $request->input('per_page', 10);

        // Overall KPI Metrics for Mold Status
        $totalMolds = MasterDieset::count();
        $warningMoldsCount = MasterDieset::whereHas('parts', function ($query) {
            $query->where('current_stock', '<=', 2);
        })->count();
        $safeMoldsCount = $totalMolds - $warningMoldsCount;
        
        $totalInstalledParts = MasterPart::whereNotNull('dieset_id')->count();
        $criticalPartsCount = MasterPart::whereNotNull('dieset_id')
            ->where('current_stock', '<=', 2)
            ->count();
        
        // Distinct list of ownerships for filter dropdown
        $ownershipList = MasterDieset::whereNotNull('ownership')
            ->where('ownership', '!=', '')
            ->distinct()
            ->orderBy('ownership')
            ->pluck('ownership');

        // Main Query with parts count & low stock count
        $query = MasterDieset::withCount([
            'parts as total_parts_count',
            'parts as low_stock_count' => function ($query) {
                $query->where('current_stock', '<=', 2);
            }
        ]);

        // Filter by Status (Aman vs Warning)
        if ($statusFilter === 'warning') {
            $query->whereHas('parts', function ($q) {
                $q->where('current_stock', '<=', 2);
            });
        } elseif ($statusFilter === 'safe') {
            $query->whereDoesntHave('parts', function ($q) {
                $q->where('current_stock', '<=', 2);
            });
        }

        // Filter by Ownership
        if ($ownershipFilter && $ownershipFilter !== 'all') {
            $query->where('ownership', $ownershipFilter);
        }

        // Search Filter across name, kode_mold, ownership, cavity
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('kode_mold', 'like', "%{$search}%")
                  ->orWhere('ownership', 'like', "%{$search}%")
                  ->orWhere('cavity', 'like', "%{$search}%")
                  ->orWhere('type_cavity', 'like', "%{$search}%");
            });
        }

        $diesets = $query->orderBy('name', 'asc')->paginate($perPage)->withQueryString();

        return view('dieset-status.index', compact(
            'diesets',
            'search',
            'statusFilter',
            'ownershipFilter',
            'perPage',
            'totalMolds',
            'safeMoldsCount',
            'warningMoldsCount',
            'totalInstalledParts',
            'criticalPartsCount',
            'ownershipList'
        ));
    }

    public function show($id): View
    {
        $dieset = MasterDieset::with(['parts' => function ($query) {
            $query->orderBy('category', 'asc')->orderBy('name', 'asc');
        }])->findOrFail($id);

        $totalParts = $dieset->parts->count();
        $criticalParts = $dieset->parts->where('current_stock', '<=', 2)->count();
        $safeParts = $totalParts - $criticalParts;
        
        // Grouping parts berdasarkan kategori
        $groupedParts = $dieset->parts->groupBy(function ($part) {
            return $part->category ?: 'General / Uncategorized';
        });

        return view('dieset-status.show', compact('dieset', 'groupedParts', 'totalParts', 'criticalParts', 'safeParts'));
    }

    public function export()
    {
        return Excel::download(new DiesetStatusExport, 'Stock_Mold_Parts_' . date('Ymd_His') . '.xlsx');
    }
}