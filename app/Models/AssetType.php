<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetType extends Model
{
    use HasFactory;

    protected $fillable = [
        "type",
        "group",
        "created_at",
        "updated_at"
    ];

    public function warehouse_asset() {
        return $this->hasMany(WarehouseAsset::class);
    }

    public function it_asset() {
        return $this->hasMany(Asset::class);
    }
}
