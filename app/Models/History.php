<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $fillable = [
        "uniqueid",
        "asset_name",
        "sn",
        "transaction_type",
        "name",
        "office_name",        
        "transaction_date",
        "comment",
        "asset_id", // foreign table
        "person_id", // foreign table
        "office_id" // foreign table
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class)->withDefault();
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
