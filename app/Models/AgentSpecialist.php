<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentSpecialist extends Model
{
    use HasFactory;
    protected $table="agentspecialists";
    protected $fillable=['agent_id','specialist_id'];
}
