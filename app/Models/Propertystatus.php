<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Propertystatus extends Model
{
    use HasFactory;
    protected $table= "propertystatus";
    protected $fillable = ['property_id', 'status_id'];
}
