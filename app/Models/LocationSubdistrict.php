<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationSubdistrict extends Model
{
    use HasFactory;

    public function villages(){
        return $this -> hasMany(LocationVillage::class, 'subdistrict_id', 'subdistrict_id'); 
    }

    public function regency(){
        return $this -> belongsTo(LocationRegency::class, 'regency_id', 'regency_id'); 
    }
}
