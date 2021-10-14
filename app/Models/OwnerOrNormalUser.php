<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OwnerOrNormalUser extends Model
{
    use HasFactory;
    protected $table="ownerornormalusers";
}
