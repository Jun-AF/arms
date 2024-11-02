<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Activity extends Model {
    use HasFactory;
    protected $fillable = ["validator_id", "message", "type", "is_read", "created_at", "updated_at"];
    public function validator() {
        return $this->belongsTo(User::class)->withDefault();
    }
}
