<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class MasterPartController extends Controller
{
    public function index(): View
    {
        // Nanti kita isi logika databasenya, sekarang tampilkan view kosong dulu
        return view('dashboard'); 
    }

    // Fungsi CRUD lainnya akan kita bangun nanti
}