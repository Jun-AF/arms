<?php

namespace App\Exports;

use App\Models\Office;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;

class ValidationExport implements FromCollection
{
    protected $office_name;

    public function __construct($office_name) {
        $this->office_name = $office_name;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function collection()
    {
        $office = Office::select("id","office_name")
            ->where("office_name", $this->office_name)
            ->get();

        $column = new Collection(['id','asset_name','sn','office_id','office_name','condition','comment','month_period','validation_id','is_validated','validator_id']);

        $validations = DB::table("assets")
            ->select(
                "assets.id",
                "assets.asset_name",
                "assets.sn",
                "assets.office_id",
                "offices.office_name",
                "validations.condition",
                "validations.comment",
                "validations.month_period",
                "validations.id as validation_id",
                "validations.is_validate",
                "validations.validator_id"
            )
            ->leftJoin("offices", "offices.id", "assets.office_id")
            ->leftJoin("validations", "validations.asset_id", "assets.id")
            ->where("assets.office_id", $office[0]->id)
            ->orderBy("assets.id", "desc")
            ->get();
    
        $validations->prepend($column);

        return $validations;
    }
}
