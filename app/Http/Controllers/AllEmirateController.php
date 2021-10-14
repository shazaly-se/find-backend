<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Propertylocation;
use DB;
class AllEmirateController extends Controller
{
    public function index(){

       return $emirates= DB::table("emirates")
       ->join("propertylocations","propertylocations.emirate_en","=","emirates.emirate_en")
       ->select('emirates.*',"propertylocations.emirate_en as em" )
     
       ->distinct()
       ->get();

       return Propertylocation::join("emirates","emirates.emirate_en","propertylocations.emirate_en")
       ->distinct()->get();
    }

    public function area($emirate_name)
    {
        return Propertylocation::where("emirate_en",$emirate_name)->get();
    }

    public function streetorbuild_en($streetorbuild_en)
    {
        return Propertylocation::where("area_en",$streetorbuild_en)->get();
    }
}
