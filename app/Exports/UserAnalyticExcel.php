<?php

namespace App\Exports;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;


class UserAnalyticExcel implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'UTM' => new SheetUtmExport(),
            'DAU' => new SheetDauExport(),
            'NRU' => new SheetNruExport(),
        ];
    }
}
