<?php

namespace App\Http\Controllers;

use App\Models\MasterDieset;
use App\Models\MasterPart;
use App\Models\InspectionHistory;
use App\Models\RepairTicket;
use App\Models\MoldRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // 1. Data Metric Cards (Utilitarian Industrial KPIs)
        $totalMolds = MasterDieset::count();
        $activeMolds = MasterDieset::where('status', 'Active')->count();
        $naMolds = MasterDieset::where('status', '!=', 'Active')->count();

        // Stok Part Kritis (<= 2 unit per BRD standar PT IFU / Compotec)
        $lowStockParts = MasterPart::where('current_stock', '<=', 2)->count();
        $safeStockParts = MasterPart::where('current_stock', '>', 2)->count();
        $totalCatalogParts = MasterPart::whereNull('dieset_id')->count();

        // Inspeksi 30 Hari Terakhir
        $thirtyDaysAgo = Carbon::now()->subDays(30);
        $inspections30Days = InspectionHistory::where('inspection_date', '>=', $thirtyDaysAgo)->count();
        $totalInspections = InspectionHistory::count();

        // Tiket Perbaikan & SPK Mold
        $activeTicketsCount = RepairTicket::whereIn('status', ['Open', 'In Progress', 'Pending Part'])->count();
        $urgentTicketsCount = RepairTicket::whereIn('status', ['Open', 'In Progress'])->where('priority', 'Urgent')->count();
        $activeSpkCount = MoldRequest::where('status', 'In Progress')->count();

        // Total Operator Aktif
        $totalOperators = User::whereHas('roles', function ($q) {
            $q->where('name', 'Operator');
        })->count();

        // 2. Data Tabel: 5 Inspeksi Terbaru
        $recentInspections = InspectionHistory::with(['part.dieset', 'operator'])
            ->orderBy('inspection_date', 'desc')
            ->take(5)
            ->get();

        // 3. Tiket Perbaikan Aktif Terbaru
        $recentTickets = RepairTicket::with(['dieset', 'technician', 'reporter'])
            ->whereIn('status', ['Open', 'In Progress', 'Pending Part'])
            ->orderByRaw("FIELD(priority, 'Urgent', 'High', 'Medium', 'Low')")
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        // 4. SPK Pembuatan Mold Berjalan
        $activeSpks = MoldRequest::with(['stages'])
            ->where('status', 'In Progress')
            ->orderBy('target_date', 'asc')
            ->take(4)
            ->get();

        // 5. Data Grafik: Tren Aktivitas Inspeksi 7 Hari Terakhir
        $chartDates = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dateKey = $date->format('Y-m-d');
            $chartDates[] = $date->format('d M');
            $chartData[] = InspectionHistory::whereDate('inspection_date', $dateKey)->count();
        }

        $maxChartVal = max(array_merge([1], $chartData));

        return view('dashboard', compact(
            'totalMolds',
            'activeMolds',
            'naMolds',
            'lowStockParts',
            'safeStockParts',
            'totalCatalogParts',
            'inspections30Days',
            'totalInspections',
            'activeTicketsCount',
            'urgentTicketsCount',
            'activeSpkCount',
            'totalOperators',
            'recentInspections',
            'recentTickets',
            'activeSpks',
            'chartDates',
            'chartData',
            'maxChartVal'
        ));
    }
}