<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dibi extends Model
{
    use HasFactory;
    protected $table = 'dibi';
    protected $fillable = ['regency_id','regency_name','province_name','date','hazard','location','chronology','cause','dead','missing','injured','house','facility'];    

    public function regency(){
        return $this -> belongsTo(LocationRegency::class, 'regency_id', 'regency_id'); 
    }    
}
