<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;
    protected $table= "packagies";
    protected $fillable = ['agency_id', 'package','regular','featured','premium'];
}
