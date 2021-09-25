<?php

namespace App\Models;
use App\Models\Propertylocation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;
    protected $table = "properties";

    public function location()
    {
        return $this->hasOne(Propertylocation::class,'property_id');
    }
}
