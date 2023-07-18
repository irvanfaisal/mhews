<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Observation extends Model
{
    use HasFactory;
    protected $fillable = ['date', 'sensor_1', 'sensor_2', 'sensor_3', 'hash','station_id'];

    public function station()
    {
        return $this->belongsTo(Station::class);
    }    
}
