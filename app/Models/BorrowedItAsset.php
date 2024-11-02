<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowedItAsset extends Model
{
    use HasFactory;

    protected $fillable = [
        'assets_id', 
        'it_assets', 
        "borrower", 
        "office_name", 
        "created_at", 
        "updated_at"
    ];

    public function it_asset() {
        return $this->belongsTo(Asset::class)->withDefault();
    }
}
