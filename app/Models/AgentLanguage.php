<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentLanguage extends Model
{
    use HasFactory;
    protected $table="agentlanguages";
    protected $fillable=['agent_id','language_id'];
}
