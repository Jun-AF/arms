<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class WarehouseAssetValidation extends Model {
    use HasFactory;
    protected $fillable = ["asset_id", "validator_id", "office_id", "condition", "comment", "month_period", "is_validated", "created_at", "updated_at"];
    public function warehouse_asset() {
        return $this->belongsTo(WarehouseAsset::class)->withDefault();
    }
    public function validator() {
        return $this->belongsTo(User::class)->withDefault();
    }
    public function office() {
        return $this->belongsTo(Office::class)->withDefault();
    }
}
