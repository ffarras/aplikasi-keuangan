<?php

namespace App\Exports;

use App\Pemasukan;
use App\Pegawai;
use Maatwebsite\Excel\Concerns\FromCollection;

class PemasukanExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Pegawai::all();
    }
}
