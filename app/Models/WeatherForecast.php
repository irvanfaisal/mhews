<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeatherForecast extends Model
{
    use HasFactory;
    protected $fillable = ['regency_id','forecast_time','date','rain','temperature','rh','radiation','pressure','wdir','wspd'];

    public function regency(){
        return $this -> belongsTo(LocationRegency::class, 'regency_id', 'regency_id'); 
    }    
}
