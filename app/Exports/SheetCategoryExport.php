<?php

namespace App\Exports;

use App\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;

class SheetCategoryExport implements FromCollection,WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Category::select('id','category')->where('status','active')->orderBy('created_at','ASC')->get();
    }

    public function title(): string
    {
        return 'Kategori';
    }
}
