<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;

    protected $fillable = [
        "office_name", 
        "location"
    ];

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }

    public function persons()
    {
        return $this->hasMany(Person::class);
    }

    public function validations()
    {
        return $this->hasMany(Validation::class);
    }
}
