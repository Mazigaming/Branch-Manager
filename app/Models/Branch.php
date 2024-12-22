<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = ['name'];
    public function leaves() { return $this->hasMany(Leaf::class); }
}
