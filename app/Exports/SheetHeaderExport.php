<?php

namespace App\Exports;

use AWS\CRT\HTTP\Request;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;

class SheetHeaderExport implements FromView,WithEvents,WithStyles,ShouldAutoSize,WithTitle
{
    public function view(): View
    {
        return view('excel.mass_upload');
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:AB1')
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('DD4B39');

                $event->sheet->getDelegate()->getStyle('A2:AB2')
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('E0E0E0');

                $event->sheet->getDelegate()->getStyle('A1:AB1')
                    ->getFont()
                    ->getColor()
                    ->setARGB('FFFFFF');

                $event->sheet->getDelegate()->getStyle('1')->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle('A1:AB1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A2:AB2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            },
        ];
    }
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('B1')->getFont('A1:AB1')->setBold(true);
    }

    public function title(): string
    {
        return 'Template';
    }
}
