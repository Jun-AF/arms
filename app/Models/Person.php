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
        "office_name",
        "job_title",
        "office_id" // foreign table
    ];

    public function office()
    {
        return $this->belongsTo(Office::class)->withDefault();
    }

    public function history()
    {
        return $this->hasMany(History::class);
    }
}
