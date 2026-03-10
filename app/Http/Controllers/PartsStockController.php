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

class PartsStockController extends Controller
{
    public function index(Request $request): View
    {
        // Menerima parameter tab dari URL (all, low, safe) - Default: all
        $tab = $request->query('tab', 'all');
        $search = $request->query('search');
        $perPage = $request->input('per_page', 10);

        $query = MasterPart::query();

        // Logika Tab Sesuai PDF Hal 15
        if ($tab === 'low') {
            $query->where('current_stock', '<=', 2);
        } elseif ($tab === 'safe') {
            $query->where('current_stock', '>', 2);
        }

        // Logika Search
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('part_code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $parts = $query->paginate($perPage);

        return view('parts-stock.index', compact('parts', 'tab', 'search', 'perPage'));
    }

    public function mailToSpv()
    {
        // 1. Generate Excel & Simpan Sementara di Storage
        $filePath = 'exports/LessStockDiesetParts_' . time() . '.xlsx';
        Excel::store(new LowStockExport, $filePath, 'local');

        // 2. Ambil daftar email dari tabel email_reports (Jika tabel kosong, kita pakai admin/supervior default)
        $emails = DB::table('email_reports')->where('status', 'Aktif')->pluck('email')->toArray();
        
        // Fallback email jika tabel kosong agar tidak error saat testing
        if (empty($emails)) {
            $emails = ['spv@compotech.com']; 
        }

        // 3. Kirim Email (Karena di .env Anda MAIL_MAILER=log, ini akan masuk ke file laravel.log, sangat aman untuk testing!)
        Mail::to($emails)->send(new LowStockMail($filePath));

        return back()->with('success', 'Laporan Low Stock berhasil dikirim ke Email SPV!');
    }
}