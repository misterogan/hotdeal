<?php

namespace App\Exports;

use App\Category;
use App\Dau;
use App\Nru;
use AWS\CRT\HTTP\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class SheetExistingExport implements WithMapping, WithTitle, WithHeadings
{
    public function array(): array
    {
        $param = request()->route()->parameter('date');
        $date_from = substr($param, 0, 10);
        $date_until = substr($param, 11, 21);
        $dau = Dau::select(DB::raw('DATE(created_at)'), DB::raw('COUNT(*)'))
            ->whereBetween('created_at', [$date_from, $date_until])
            ->orderByDesc(DB::raw('DATE(created_at)'))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->get();
        $nru = Nru::select(DB::raw('DATE(created_at)'), DB::raw('COUNT(*)'))
        ->whereBetween('created_at', [$date_from, $date_until])
        ->orderByDesc(DB::raw('DATE(created_at)'))
        ->groupBy(DB::raw('DATE(created_at)'))
        ->get();
        $data = [
            'dau' => $dau,
            'nru' => $nru
        ];
        return $data;
    }

    public function map($row): array
    {
        $param = request()->route()->parameter('date');
        $date_from = substr($param, 0, 10);
        $date_until = substr($param, 11, 21);
        $dau = Dau::select(DB::raw('DATE(created_at)'), DB::raw('COUNT(*)'))
            ->whereBetween('created_at', [$date_from, $date_until])
            ->orderByDesc(DB::raw('DATE(created_at)'))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->get();
        $nru = Nru::select(DB::raw('DATE(created_at)'), DB::raw('COUNT(*)'))
        ->whereBetween('created_at', [$date_from, $date_until])
        ->orderByDesc(DB::raw('DATE(created_at)'))
        ->groupBy(DB::raw('DATE(created_at)'))
        ->get();

        return [
            'dau' => $dau,
            'nru' => $nru
        ];
    }

    public function headings(): array
    {
        return [
            'Date',
            'DAU',
            'NRU',
        ];
    }

    public function title(): string
    {
        return 'Existing';
    }
}
