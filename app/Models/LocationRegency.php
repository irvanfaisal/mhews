<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationRegency extends Model
{
    use HasFactory;
    
    public function province(){
        return $this -> belongsTo(LocationProvince::class, 'province_id', 'province_id'); 
    }

    public function subdistricts(){
        return $this -> hasMany(LocationSubdistrict::class,'regency_id','regency_id'); 
    }

    public function forecasts(){
        return $this -> hasMany(WeatherForecast::class,'regency_id','regency_id'); 
    }
}
