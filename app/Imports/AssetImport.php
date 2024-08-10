<?php

namespace App\Imports;

use App\Models\Asset;
use App\Models\History;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;

class AssetImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return User|null
     */
    public function model(array $row)
    {
        DB::beginTransaction();
    
        $asset = DB::table('assets')->select('id')->where('asset_name',$row[0])->get();
        $office = DB::table('offices')->select('id')->where('office_name',$row[7])->get();
        
        try {
            Asset::create([
                'asset_name' => $row[0],
                'type' => $row[1],
                'sn' => $row[2],
                'os' => $row[3],
                'hostname' => $row[4],
                'mac_address' => $row[5],
                'office_name' => $row[6],
                'purchase_date' => $row[7],
                'asset_id' => $asset[0]->id,
                'office_id' => $office[0]->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            History::create([

            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return 0;
        }
       
    }
}
