<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;

class TransactionExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $column = new Collection(DB::getSchemaBuilder()->getColumnListing('histories'));

        $histories = DB::table('histories')
            ->orderBy("histories.transaction_date", "DESC")
            ->get();

        $histories->prepend($column);

        return $histories;
    }
}
