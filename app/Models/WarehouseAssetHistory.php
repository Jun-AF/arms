<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseAssetHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        "asset_name",
        "sn",
        "transaction_type",
        "transaction_date",
        "comment",
        "asset_id",
        "person_id",
        "office_id",
    ];

    public function warehouse_asset()
    {
        return $this->belongsTo(WarehouseAsset::class)->withDefault();
    }

    public function person()
    {
        return $this->belongsTo(Person::class)->withDefault();
    }

    public function office()
    {
        return $this->belongsTo(History::class)->withDefault();
    }
}
