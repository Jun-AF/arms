<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        "uniqueid",
        "asset_name",
        "type",
        "sn",
        "os",
        "hostname",
        "mac_address",
        "office_name",
        "purchase_date",
        "asset_in",
        "office_id" // foreign table
    ];

    public function validation()
    {
        return $this->hasOne(Validation::class);
    }

    public function office()
    {
        return $this->belongsTo(Office::class)->withDefault();
    }

    public function history()
    {
        return $this->hasMany(History::class);
    }
}
