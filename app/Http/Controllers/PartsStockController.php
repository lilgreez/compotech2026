<?php

namespace App\Http\Controllers;

use App\Models\MasterPart;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Exports\LowStockExport;
use App\Mail\LowStockMail;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PartsStockController extends Controller
{
    public function index(Request $request): View
    {
        // Menerima parameter tab dari URL (all, low, safe) - Default: all
        $tab = $request->query('tab', 'all');
        $search = $request->query('search');
        $category = $request->query('category', 'all');
        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }

        // Global KPI Metrics (tidak terpengaruh pagination)
        $totalParts = MasterPart::count();
        $lowStockCount = MasterPart::where('current_stock', '<=', 2)->count();
        $safeStockCount = MasterPart::where('current_stock', '>', 2)->count();
        $totalUnits = (int) MasterPart::sum('current_stock');

        // Query Utama dengan Eager Loading
        $query = MasterPart::with('dieset');

        // Logika Tab Sesuai Threshold Buffer (≤ 2 Pcs = Kritis/Low Stock)
        if ($tab === 'low') {
            $query->where('current_stock', '<=', 2);
        } elseif ($tab === 'safe') {
            $query->where('current_stock', '>', 2);
        }

        // Filter Kategori
        if ($category !== 'all') {
            if ($category === 'general') {
                $query->whereNull('category');
            } else {
                $query->where('category', $category);
            }
        }

        // Logika Search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('part_code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('item_notes', 'like', "%{$search}%")
                  ->orWhereHas('dieset', function ($sub) use ($search) {
                      $sub->where('name', 'like', "%{$search}%")
                          ->orWhere('kode_mold', 'like', "%{$search}%");
                  });
            });
        }

        // Urutan: Prioritaskan stok paling sedikit jika di tab low, atau urutkan part_code
        if ($tab === 'low') {
            $query->orderBy('current_stock', 'asc')->orderBy('part_code', 'asc');
        } else {
            $query->orderBy('part_code', 'asc')->orderBy('name', 'asc');
        }

        $parts = $query->paginate($perPage)->withQueryString();

        // Ambil daftar email supervisor aktif
        $spvEmails = DB::table('email_reports')->where('status', 'Aktif')->pluck('email')->toArray();
        if (empty($spvEmails)) {
            $spvEmails = ['supervisor.tooling@compotech.com'];
        }

        return view('parts-stock.index', compact(
            'parts',
            'tab',
            'search',
            'category',
            'perPage',
            'totalParts',
            'lowStockCount',
            'safeStockCount',
            'totalUnits',
            'spvEmails'
        ));
    }

    public function exportExcel(): BinaryFileResponse
    {
        $fileName = 'Laporan_Stock_Sparepart_' . date('Ymd_His') . '.xlsx';
        return Excel::download(new LowStockExport, $fileName);
    }

    public function mailToSpv()
    {
        // 1. Generate Excel & Simpan Sementara di Storage
        $filePath = 'exports/LessStockMoldParts_' . time() . '.xlsx';
        Excel::store(new LowStockExport, $filePath, 'local');

        // 2. Ambil daftar email dari tabel email_reports
        $emails = DB::table('email_reports')->where('status', 'Aktif')->pluck('email')->toArray();
        if (empty($emails)) {
            $emails = ['supervisor.tooling@compotech.com']; 
        }

        // 3. Hitung jumlah part low stock
        $count = MasterPart::where('current_stock', '<=', 2)->count();

        // 4. Kirim Email Notifikasi
        Mail::to($emails)->send(new LowStockMail($filePath));

        return back()->with('success', "Laporan {$count} item spare part Low Stock berhasil dikirimkan ke Email Supervisor (" . implode(', ', $emails) . ")!");
    }

    public function adjustStock(Request $request, $id)
    {
        $request->validate([
            'current_stock' => 'required|integer|min:0',
            'adjustment_reason' => 'nullable|string|max:255',
        ]);

        $part = MasterPart::findOrFail($id);
        $oldStock = $part->current_stock;
        $part->current_stock = $request->current_stock;
        if ($request->adjustment_reason) {
            $timestamp = date('d/m/Y H:i');
            $part->item_notes = ($part->item_notes ? $part->item_notes . " | " : "") . "[Penyesuaian Stok: {$oldStock} -> {$part->current_stock} ({$request->adjustment_reason}) - {$timestamp}]";
        }
        $part->save();

        return back()->with('success', "Stok untuk part [{$part->part_code}] {$part->name} berhasil diperbarui dari {$oldStock} menjadi {$part->current_stock} Pcs!");
    }
}