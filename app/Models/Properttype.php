<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Properttype extends Model
{
    use HasFactory;
    protected $table= "propertytypes";
    protected $fillable = ["typeName_en", "typeName_ar"];
}
