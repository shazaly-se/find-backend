<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Agent;
use App\Models\Property;
class Agency extends Model
{
    use HasFactory;
    protected $table = "agencies";

    public function user()
    {
        return $this->belongsTo(User::class,'agency_id');
    }


    public function property()
    {
        return $this->hasMany(Property::class,'agency_id');
    }

    public function agents()
    {
        return $this->hasMany(Agent::class, 'agency_id');
    }
}
