<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;

class GeoLocationController extends Controller
{
    public function index(Request $request)
    {
        $ip_address = \Request::ip();
        return $ip_address;
        if ($position = Location::get($ip_address)) {
            // Successfully retrieved position.
            echo $position->countryName;
        } else {
            echo "error";
            // Failed retrieving position.
        }
    }
    
}
