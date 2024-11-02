<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $table = "it_assets_histories";

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

    public function it_asset()
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
