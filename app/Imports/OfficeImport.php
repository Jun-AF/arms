<?php

namespace App\Imports;

use App\Models\Office;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;

class OfficeImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return User|null
     */
    public function model(array $row)
    {
        DB::beginTransaction();
       
        try {
            Office::create([
                'office_name' => $row[0],
                'location' => $row[1],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return 0;
        }
    }
}
