<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Asset extends Model {
    use HasFactory;
    protected $table = "it_assets";
    protected $fillable = ["uniqueid", "asset_name", "sn", "mac_address", "ip_address", "hostname", "operating_system", "storage_capacity", "type_id", "guarantee_date", "purchase_date", "asset_in", "created_at", "updated_at", ];
    public function it_validation() {
        return $this->hasOne(Validation::class);
    }
    public function office() {
        return $this->belongsTo(Office::class)->withDefault();
    }
    public function history() {
        return $this->hasMany(History::class);
    }
    public function type() {
        return $this->belongsTo(AssetType::class)->withDefault();
    }
}
