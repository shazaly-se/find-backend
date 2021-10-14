<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Propertylocation;
use App\Models\User;
use App\Models\Agency;
use App\Models\Agent;
use DB;

class QuotaUsageController extends Controller
{


    public function index()
    {

        $usageproperties = Property::join('propertydetails','propertydetails.property_id','=','properties.id')
        ->join('agencies','agencies.id','=','properties.agency_id')
        ->join('packages','packages.id','=','properties.package_type')
        ->join('propertylocations','propertylocations.property_id','=','properties.id')
        ->select('propertylocations.area_en','propertylocations.area_ar', DB::raw('COUNT(properties.id) as last_post_created_at'),DB::raw('COUNT(propertydetails.purpose) as p'),)
 
        ->groupBy('propertylocations.area_en','propertylocations.area_ar')->get();
         $emirates= DB::table("emirates")
    ->join("propertylocations","propertylocations.emirate_en","=","emirates.emirate_en")
    ->select('emirates.*',"propertylocations.emirate_en as em" )
  
    ->distinct()
    ->get();
    foreach($emirates as $emirate){
      $emirate->property = Property::join('propertylocations','propertylocations.property_id','=','properties.id')
      ->where("propertylocations.emirate_en",$emirate->emirate_en)->get(array("properties.*"));

    }
    return $emirates;

//    Post::with(array('comments'=>function($query){
//     $query->select('id as commentid','comment')->orderBy('created_at', 'asc')->take(3);
// }))->simplePaginate(15);

    // foreach($locations as $loc){
    //    // return $loc;

    //     $usageproperties = Property::join('propertydetails','propertydetails.property_id','=','properties.id')
    //     ->join('agencies','agencies.id','=','properties.agency_id')
    //     ->join('packages','packages.id','=','properties.package_type')
    //     ->join('propertylocations','propertylocations.property_id','=','properties.id')
    //     ->where("propertylocations.id",$loc->id)
    //     ->get();
    //    // return $usageproperties;

    // }
    

   

        return response()->json(["usageproperties" => $usageproperties]);

    }
}
