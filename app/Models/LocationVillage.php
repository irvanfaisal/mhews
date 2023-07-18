<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationVillage extends Model
{
    use HasFactory;

    public function subdistrict(){
        return $this -> belongsTo(LocationSubdistrict::class, 'subdistrict_id', 'subdistrict_id'); 
    }
}
