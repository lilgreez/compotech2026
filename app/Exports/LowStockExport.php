<?php

namespace App\Exports;

use App\Models\MasterPart;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LowStockExport implements FromView, ShouldAutoSize, WithStyles
{
    public function view(): View
    {
        // Ambil data parts yang stock-nya <= 2
        $parts = MasterPart::where('current_stock', '<=', 2)->get();
        
        return view('parts-stock.export-excel', [
            'parts' => $parts
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return[
            1 => ['font' =>['bold' => true, 'size' => 14, 'underline' => true]],
            3 => ['font' => ['bold' => true]],
        ];
    }
}