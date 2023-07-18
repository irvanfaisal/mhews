<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    use HasFactory;
    protected $fillable = ['last'];

    public function observations()
    {
        return $this->hasMany(Observation::class);
    }
    public function last()
    {
        return $this->hasOne(Observation::class)->latest();
    }
}
