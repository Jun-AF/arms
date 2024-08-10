<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Validation extends Model
{
    use HasFactory;

    protected $fillable = [
        "asset_id", // foreign table
        "validator_id", // foreign table
        "office_id", // foreign table
        "condition",
        "comment",
        "month_period",
        "is_validate",
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class)->withDefault();
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function office()
    {
        return $this->belongsTo(Office::class)->withDefault();
    }
}
