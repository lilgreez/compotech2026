<?php

namespace App\Http\Controllers;

use App\Models\MasterDieset;
use App\Models\MasterPart;
use App\Models\InspectionHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // 1. Data Metric Cards
        $totalDiesets = MasterDieset::count();
        
        // Menghitung parts yang stoknya kritis (<= 2)
        $lowStockParts = MasterPart::where('current_stock', '<=', 2)->count();
        
        $totalInspections = InspectionHistory::count();
        
        $totalOperators = User::whereHas('roles', function($q) {
            $q->where('name', 'Operator');
        })->count();

        // 2. Data Tabel: 5 Inspeksi Terbaru
        $recentInspections = InspectionHistory::with(['part.dieset', 'operator'])
            ->orderBy('inspection_date', 'desc')
            ->take(5)
            ->get();

        // 3. Data Grafik (Chart.js): Tren Inspeksi 7 Hari Terakhir
        $chartDates = [];
        $chartData =[];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $chartDates[] = $date->format('d M'); // Contoh: 10 Mar, 11 Mar
            
            // Hitung jumlah inspeksi pada tanggal tersebut
            $count = InspectionHistory::whereDate('inspection_date', $date->format('Y-m-d'))->count();
            $chartData[] = $count;
        }

        return view('dashboard', compact(
            'totalDiesets', 
            'lowStockParts', 
            'totalInspections', 
            'totalOperators', 
            'recentInspections',
            'chartDates',
            'chartData'
        ));
    }
}