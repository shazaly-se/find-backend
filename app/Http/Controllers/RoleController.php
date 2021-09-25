<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Role;
class RoleController extends Controller
{
    public function roles()
    {
        return response()->json(['roles'=>Role::all()]);
    }
}
