<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GeoLocationController extends Controller
{
    public function index(Request $request)
    {
         
            $ip = $request->ip();
        
            $position = \Location::get('5.195.156.177');
                return $position;
          
    }
}
