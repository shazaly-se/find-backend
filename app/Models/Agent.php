<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Property;
use App\Models\AgentLanguage;
class Agent extends Model
{
    use HasFactory;
    protected $table="agents";

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function property()
    {
        return $this->hasMany(Property::class,'agency_id');
    }

    public function agentproperty()
    {
        return $this->hasMany(Property::class,'agent_id');
    }

    public function language()
    {
        return $this->hasMany(AgentLanguage::class,'agent_id');
    }
    

}
