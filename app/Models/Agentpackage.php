<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agentpackage extends Model
{
    use HasFactory;
    protected $table= "agentpackagies";
    protected $fillable = ['agent_id', 'package','regular','featured','premium'];
}
