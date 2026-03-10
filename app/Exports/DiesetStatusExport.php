<?php

namespace App\Exports;

use App\Models\MasterDieset;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DiesetStatusExport implements FromView, ShouldAutoSize, WithStyles
{
    public function view(): View
    {
        // Ambil semua dieset beserta parts-nya
        $diesets = MasterDieset::with('parts')->get();
        return view('dieset-status.export-excel', [
            'diesets' => $diesets
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 =>['font' => ['bold' => true, 'size' => 14]],
            3 => ['font' =>['bold' => true]],
        ];
    }
}