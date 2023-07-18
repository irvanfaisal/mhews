<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VolcanoActivity extends Model
{
    use HasFactory;
    protected $fillable = ['title','article','date','time','status'];
}
