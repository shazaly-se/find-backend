<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Propertydetail;
use App\Models\Propertyfeature;
use App\Models\Agency;
use App\Models\Agent;
use App\Models\Media;
use App\Models\Propertylocation;
use DB;

class AgencyDataShowController extends Controller
{

    public function users(){

        $agency = Agency::first();

        $agents_en= Agent::join("propertylocations","propertylocations.agent_id","agents.id")
        ->distinct()
      
        ->get(array("agents.id","agents.name_en as name"));

        $agents_ar= Agent::join("propertylocations","propertylocations.agent_id","agents.id")
        ->distinct()
      
        ->get(array("agents.id","agents.name_ar as name"));

        // return $agent;
        // $agents= Agent::get(array("agents.id","agents.name_en","agents.name_ar"));

       return response()->json(["agents_en" => $agents_en,"agents_ar" => $agents_ar]);
    }

    public function locations(){

        $agency = Agency::first();
       // return $agency;

        $locations_en= Propertylocation::distinct()
      
        ->get(array("area_en as area"));
        $locations_ar= Propertylocation::distinct()
      
        ->get(array("area_ar as area"));

        // return $agent;
        // $agents= Agent::get(array("agents.id","agents.name_en","agents.name_ar"));

       return response()->json(["locations_en" => $locations_en,"locations_ar" => $locations_ar]);
    }

    public function allproperties()
    {
        //return "all properties";


        $properties = Property::join("propertydetails","propertydetails.property_id","=","properties.id")
        ->join("agents","agents.id","=","properties.agent_id")
        ->join("propertytypes","propertytypes.id","=","properties.propertytypes_id")
        ->join("propertylocations","propertylocations.property_id","=","properties.id")
        ->get(array("properties.id","properties.title_en","properties.title_ar","propertytypes.typeName_en",
        "propertytypes.typeName_ar","propertydetails.purpose"
        ,"propertylocations.emirate_en","propertylocations.emirate_ar","propertylocations.area_en","propertylocations.area_ar"
        ,"propertylocations.streetorbuild_en","propertylocations.streetorbuild_ar"
    ));

        foreach($properties as $property){

           // $agency->properties = Property::where("agency_id",$agency->id)->get();

            $property->rent = DB::table('properties')
            ->join("propertydetails","propertydetails.property_id","=","properties.id")
                  ->select('purpose', DB::raw('COUNT(properties.id) as rent_count'))
                  ->where('propertydetails.purpose', 1)
                  ->where('properties.id', $property->id)
                  ->groupBy('purpose')->get();

                  $property->buy = DB::table('properties')
                  ->join("propertydetails","propertydetails.property_id","=","properties.id")
                        ->select('purpose', DB::raw('COUNT(properties.id) as buy_count'))
                        ->where('propertydetails.purpose', 2)
                        ->where('properties.id', $property->id)
                        ->groupBy('purpose')->get();

        }

        return response()->json(["properties" =>$properties ]);



    }

    public function allagents()
    {
        return "all agents";
    }

    public function agentproperties()
    {
        return "agent properties";
    }

    public function packegedetails()
    {
        return "package details";
    }

    public function packegedetailswithusage()
    {
        $agency = Agency::first();
 
        $locations = Propertylocation::distinct() -> select("area_en","area_ar", DB::raw('COUNT(propertylocations.property_id) as property_count'))
        ->groupBy("area_en","area_ar")->get();

        foreach($locations as $location){
             $location->rent = DB::table('properties')
             ->join("propertydetails","propertydetails.property_id","=","properties.id")
             ->join("propertylocations","propertylocations.property_id","=","properties.id")
                   ->select('purpose', DB::raw('COUNT(properties.id) as rent_count'))
                   ->where('propertydetails.purpose', 1)
                   ->where('propertylocations.area_en', $location->area_en)
                   ->groupBy('purpose')->get();

                   $location->buy = DB::table('properties')
                   ->join("propertydetails","propertydetails.property_id","=","properties.id")
                   ->join("propertylocations","propertylocations.property_id","=","properties.id")
                         ->select('purpose', DB::raw('COUNT(properties.id) as buy_count'))
                         ->where('propertydetails.purpose', 2)
                         ->where('propertylocations.area_en', $location->area_en)
                         ->groupBy('purpose')->get();

                         $location->basic = DB::table('properties')
                         ->join("propertydetails","propertydetails.property_id","=","properties.id")
                         ->join("propertylocations","propertylocations.property_id","=","properties.id")
                         ->select('package_type', DB::raw('COUNT(properties.id) as basic_count'))
                         ->where('package_type', 1)
                         ->where('propertylocations.area_en', $location->area_en)
                         ->groupBy('package_type')->get();

                 

                         $location->featured = DB::table('properties')
                         ->join("propertydetails","propertydetails.property_id","=","properties.id")
                         ->join("propertylocations","propertylocations.property_id","=","properties.id")
                         ->select('package_type', DB::raw('COUNT(properties.id) as featured_count'))
                         ->where('package_type', 2)
                         ->where('propertylocations.area_en', $location->area_en)
                         ->groupBy('package_type')->get();

                         $location->premium = DB::table('properties')
                         ->join("propertydetails","propertydetails.property_id","=","properties.id")
                         ->join("propertylocations","propertylocations.property_id","=","properties.id")
                         ->select('package_type', DB::raw('COUNT(properties.id) as premium_count'))
                         ->where('package_type', 3)
                         ->where('propertylocations.area_en', $location->area_en)
                         ->groupBy('package_type')->get();

         }



        return response()->json(["locations" =>$locations ]);
    }

    public function filterusagepackage(Request $request){
        $agency = Agency::first();
        //return $request->all();

        $users = $request->get('users');
        $usersArr = array();
        //return $users;
        for($i=0;$i< count($users); $i++){
           // $usersArr.array_push($users[$i]['id']);
            array_push($usersArr, $users[$i]['name']);

        }

        //return $usersArr;
 
        $locations = Propertylocation::distinct() -> 
        join("agents","agents.id","propertylocations.agent_id")
        ->select("area_en","area_ar", DB::raw('COUNT(propertylocations.property_id) as property_count'))
        ->where(function ($query1) use($usersArr){
        if(count($usersArr) > 0){ $query1->whereIn("agents.name_en",$usersArr); }
           
        })
            
       
        ->groupBy("area_en","area_ar")->get();
       // return $locations;

        foreach($locations as $location){
             $location->rent = DB::table('properties')
             ->join("propertydetails","propertydetails.property_id","=","properties.id")
             ->join("propertylocations","propertylocations.property_id","=","properties.id")
                   ->select('purpose', DB::raw('COUNT(properties.id) as rent_count'))
                   ->where('propertydetails.purpose', 1)
                   ->where('propertylocations.area_en', $location->area_en)
                   ->groupBy('purpose')->get();

                   $location->buy = DB::table('properties')
                   ->join("propertydetails","propertydetails.property_id","=","properties.id")
                   ->join("propertylocations","propertylocations.property_id","=","properties.id")
                         ->select('purpose', DB::raw('COUNT(properties.id) as buy_count'))
                         ->where('propertydetails.purpose', 2)
                         ->where('propertylocations.area_en', $location->area_en)
                         ->groupBy('purpose')->get();

                         $location->basic = DB::table('properties')
                         ->join("propertydetails","propertydetails.property_id","=","properties.id")
                         ->join("propertylocations","propertylocations.property_id","=","properties.id")
                         ->select('package_type', DB::raw('COUNT(properties.id) as basic_count'))
                         ->where('package_type', 1)
                         ->where('propertylocations.area_en', $location->area_en)
                         ->groupBy('package_type')->get();

                 

                         $location->featured = DB::table('properties')
                         ->join("propertydetails","propertydetails.property_id","=","properties.id")
                         ->join("propertylocations","propertylocations.property_id","=","properties.id")
                         ->select('package_type', DB::raw('COUNT(properties.id) as featured_count'))
                         ->where('package_type', 2)
                         ->where('propertylocations.area_en', $location->area_en)
                         ->groupBy('package_type')->get();

                         $location->premium = DB::table('properties')
                         ->join("propertydetails","propertydetails.property_id","=","properties.id")
                         ->join("propertylocations","propertylocations.property_id","=","properties.id")
                         ->select('package_type', DB::raw('COUNT(properties.id) as premium_count'))
                         ->where('package_type', 3)
                         ->where('propertylocations.area_en', $location->area_en)
                         ->groupBy('package_type')->get();

         }



        return response()->json(["locations" =>$locations ]);
    }

    public function propertystatus()
    {
        return "property status";
    }
}
