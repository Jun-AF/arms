<?php

namespace App\Imports;

use App\Models\Person;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;

class PersonImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return User|null
     */
    public function model(array $row)
    {
        DB::beginTransaction();

        $office = DB::table('offices')->select('id')->where('office_name',$row[1])->get();
        try {
            Person::create([
                'name' => $row[0],
                'office_name' => $row[1],
                'job_title' => $row[2],
                'office_id' => $office[0]->id,
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
