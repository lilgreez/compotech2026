<?php

namespace App\Exports;

use App\Models\MasterDieset;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InspectionMonitorExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function view(): View
    {
        // Ambil Dieset yang memiliki history inspeksi pada rentang tanggal tersebut
        $diesets = MasterDieset::whereHas('inspectionHistories', function($q) {
            if ($this->startDate) $q->whereDate('inspection_date', '>=', $this->startDate);
            if ($this->endDate) $q->whereDate('inspection_date', '<=', $this->endDate);
        })->with(['parts.inspectionHistories' => function($q) {
            if ($this->startDate) $q->whereDate('inspection_date', '>=', $this->startDate);
            if ($this->endDate) $q->whereDate('inspection_date', '<=', $this->endDate);
        }, 'parts.inspectionHistories.operator'])->get();

        return view('inspection-monitor.export-excel', [
            'diesets' => $diesets
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' =>['bold' => true, 'size' => 14, 'underline' => true]],
            3 =>['font' => ['bold' => true]],
        ];
    }
}