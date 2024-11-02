<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Office extends Model {
    use HasFactory;
    protected $fillable = ["office_name", "location", "created_at", "updated_at"];
    public function warehouse_validation() {
        return $this->hasMany(WarehouseAssetValidation::class);
    }
    public function warehouse_asset() {
        return $this->hasMany(WarehouseAsset::class);
    }
    public function it_validation() {
        return $this->hasMany(Validation::class);
    }
    public function it_asset() {
        return $this->hasMany(Asset::class);
    }
}
