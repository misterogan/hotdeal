<?php

namespace App\Exports;

use App\Hotpoint;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class HotpointExcel implements FromCollection, WithTitle, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $param = request()->route()->parameter('date');
        $date_from = substr($param, 0, 10);
        $date_until = substr($param, 11, 21);

        $data = Hotpoint::select(
                        'users.name',
                        'users.email',
                        'hotpoints.type',
                        'hotpoints.value',
                        'hotpoints.code',
                        'hotpoints.detail',
                        DB::raw('DATE(hotpoints.created_at) as date')
                        )
                        ->leftJoin('users', 'hotpoints.user_id', 'users.id')
                        ->whereBetween('hotpoints.created_at', [$date_from, $date_until])
                        ->orderByDesc('hotpoints.created_at')
                        ->get();
        return $data;
    }

    public function title(): string
    {
        return 'Hotpoint';
    }

    public function headings(): array
    {
        return [
            'Name',
            'Email',
            'Type',
            'Value',
            'Code',
            'Detail',
            'Date',
        ];
    }
}
