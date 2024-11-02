<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class BorrowedWarehouseAsset extends Model {
    use HasFactory;
    protected $fillable = ["assets_id", "warehouse_assets", "borrower", "office_name", "created_at", "updated_at"];
    public function warehouse_asset() {
        return $this->belongsTo(WarehouseAsset::class)->withDefault();
    }
}
