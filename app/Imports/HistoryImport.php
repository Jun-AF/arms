<?php

namespace App\Imports;

use App\Models\History;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;

class HistoryImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return User|null
     */
    public function model(array $row)
    {
        DB::beginTransaction();
        $asset = DB::table('assets')->select('id','uniqueid')->where('asset_name',$row[0])->get();
        $person = DB::table('persons')->select('id')->where('user_name',$row[3])->get();
        $office = DB::table('offices')->select('id')->where('office_name',$row[4])->get();
        try {
            History::create([
                'uniqueid' => $asset[0]->uniqueid,
                'asset_name' => $row[0],
                'sn' => $row[1],
                'transaction_type' => $row[2],
                'name' => $row[3],
                'office_name' => $row[4],
                'transaction_date' => $row[5],
                'comment' => $row[6],
                'asset_id' => $asset[0]->id,
                'person_id' => $person[0]->id,
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
