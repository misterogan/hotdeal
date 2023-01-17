<?php

namespace App\Imports;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithConditionalSheets;

//class ProductImport implements WithMultipleSheets,WithChunkReading,ShouldQueue
class ProductImport implements WithMultipleSheets
{
    use Queueable, SerializesModels;
    /**
    * @param Collection $collection
    */
    use WithConditionalSheets;

    protected $vendor_id;
    protected $name;


    function __construct($vendor_id,$name) {
        $this->vendor_id = $vendor_id;
        $this->name = $name;
    }

    public function conditionalSheets(): array
    {
        return [
            'Template' => new ProductImportFirstSheet($this->vendor_id,$this->name),
        ];
    }



}
