<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Propertytype extends Model
{
    use HasFactory;

    protected $table= "propertytypes";
    protected $fillable = ["type_id","typeName"];

    
}
