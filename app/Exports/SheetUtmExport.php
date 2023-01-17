<?php

namespace App\Exports;

use App\Category;
use App\Dau;
use App\Nru;
use App\UserActivity;
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

class SheetUtmExport implements FromCollection, WithTitle, WithHeadings
{
    public function collection()
    {
        $param = request()->route()->parameter('date');
        $date_from = substr($param, 0, 10);
        $date_until = substr($param, 11, 21);

        $data = UserActivity::select(DB::raw('DATE(created_at) as date'), 'activity', 'user_id', 'utm_id', 'utm_source', 'utm_campaign', 'utm_term', 'utm_medium')
                ->whereBetween('created_at', [$date_from, $date_until])
                ->orderBy('created_at')
                ->get();

        return $data;
    }

    public function headings(): array
    {
        return [
            'Date',
            'Activity',
            'User ID',
            'UTM ID',
            'UTM Source',
            'UTM Campaign',
            'UTM Term',
            'UTM Medium',
        ];
    }

    public function title(): string
    {
        return 'UTM Data';
    }
}