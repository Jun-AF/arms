<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $table = "persons";

    protected $fillable = [
        "name",
        "job_title",
        "created_at",
        "updated_at"
    ];

    public function warehouse_history()
    {
        return $this->hasMany(WarehouseAssetHistory::class);
    }

    public function it_history()
    {
        return $this->hasMany(History::class);
    }
}
