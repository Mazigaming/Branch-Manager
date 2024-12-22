<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Leaf extends Model
{
    protected $fillable = ['value', 'branch_id'];
    public function branch() { return $this->belongsTo(Branch::class); }
}
