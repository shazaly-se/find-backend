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

        $user = auth()->user();
        $agency=Agency::where('user_id',$user->id)->first();
       

        $agents_en= Agent::join("propertylocations","propertylocations.agent_id","agents.id")
        ->distinct()
      ->where("propertylocations.agency_id",$agency->id)
        ->get(array("agents.id","agents.name_en as name"));

        $agents_ar= Agent::join("propertylocations","propertylocations.agent_id","agents.id")
        ->distinct()
      
        ->get(array("agents.id","agents.name_ar as name"));
       return response()->json(["agents_en" => $agents_en,"agents_ar" => $agents_ar]);
    }

    public function locations(){
        $user = auth()->user();
        $agency=Agency::where('user_id',$user->id)->first();

        // emirates

        $emirates_en= DB::table("propertylocations")->join("properties","properties.id","propertylocations.property_id")->distinct()
        ->where("propertylocations.agency_id",$agency->id)
       ->get(array("propertylocations.emirate_en as location"));

       foreach($emirates_en as $emirate_en){
        $emirate_en->type=1;
       }

       $emirates_ar= DB::table("propertylocations")->join("properties","properties.id","propertylocations.property_id")->distinct()
       ->where("propertylocations.agency_id",$agency->id)
      ->get(array("propertylocations.emirate_ar as location"));

      foreach($emirates_ar as $emirate_ar){
        $emirate_ar->type=1;
       }

       // area 
       $areas_en= DB::table("propertylocations")->join("properties","properties.id","propertylocations.property_id")->distinct()
       ->where("propertylocations.agency_id",$agency->id)
       ->get(array("propertylocations.area_en as location"));

       foreach($areas_en as $area_en){
        $area_en->type=2;
       }

       $areas_ar= DB::table("propertylocations")->join("properties","properties.id","propertylocations.property_id")->distinct()
       ->where("propertylocations.agency_id",$agency->id)
       ->get(array("propertylocations.area_ar as location"));

       foreach($areas_ar as $area_ar){
        $area_ar->type=2;
       }

       // build or street

       $buildingorstreets_en= DB::table("propertylocations")->join("properties","properties.id","propertylocations.property_id")->distinct()
       ->where("propertylocations.agency_id",$agency->id)
       ->get(array("propertylocations.streetorbuild_en as location"));

       foreach($buildingorstreets_en as $buildingorstreet_en){
        $buildingorstreet_en->type=3;
       }

       
       $buildingorstreets_ar= DB::table("propertylocations")->join("properties","properties.id","propertylocations.property_id")->distinct()
       ->where("propertylocations.agency_id",$agency->id)
       ->get(array("propertylocations.streetorbuild_ar as location"));

       foreach($buildingorstreets_ar as $buildingorstreet_ar){
        $buildingorstreet_ar->type=3;
       }
      // return $buildingorstreets;

      // array english data
      $emirateandarea_en= $emirates_en->merge($areas_en);
      $emirateandarea_and_buildingorstreet_en= $emirateandarea_en->merge($buildingorstreets_en);

 // array arabic data
      $emirateandarea_ar= $emirates_ar->merge($areas_ar);
      $emirateandarea_and_buildingorstreet_ar= $emirateandarea_ar->merge($buildingorstreets_ar);

        // $locations_en= Propertylocation::join("properties","properties.id","=","propertylocations.property_id")->distinct()
        //  ->where("propertylocations.agency_id",$agency->id)
        // ->get(array("area_en as area"));


        // $locations_ar= Propertylocation::join("properties","properties.id","=","propertylocations.property_id")->distinct()
        // ->where("propertylocations.agency_id",$agency->id)
        // ->get(array("area_ar as area"));




       return response()->json(["locations_en" => $emirateandarea_and_buildingorstreet_en,"locations_ar" => $emirateandarea_and_buildingorstreet_ar]);
    }

    public function allproperties()
    {
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
        $user = auth()->user();
        $agency=Agency::where('user_id',$user->id)->first();
 
         $locations = Propertylocation::join("properties","properties.id","=","propertylocations.property_id")->distinct()
         ->join("propertydetails","propertydetails.property_id","=","properties.id")
          ->where("propertylocations.agency_id",$agency->id)
        ->select("area_en","area_ar", DB::raw('COUNT(properties.id) as property_count'))
        ->groupBy("area_en","area_ar")->get();

        foreach($locations as $location){
             $location->rent = DB::table('properties')
             ->join("propertydetails","propertydetails.property_id","=","properties.id")
             ->join("propertylocations","propertylocations.property_id","=","properties.id")
                   ->select('purpose', DB::raw('COUNT(properties.id) as rent_count'))
                   ->where('propertydetails.purpose', 1)
                   ->where("propertylocations.agency_id",$agency->id)
                   ->where('propertylocations.area_en', $location->area_en)
                   ->groupBy('purpose')->get();

                   $location->buy = DB::table('properties')
                   ->join("propertydetails","propertydetails.property_id","=","properties.id")
                   ->join("propertylocations","propertylocations.property_id","=","properties.id")
                         ->select('purpose', DB::raw('COUNT(properties.id) as buy_count'))
                         ->where('propertydetails.purpose', 2)
                         ->where("propertylocations.agency_id",$agency->id)
                         ->where('propertylocations.area_en', $location->area_en)
                         ->groupBy('purpose')->get();

                         $location->basic = DB::table('properties')
                         ->join("propertydetails","propertydetails.property_id","=","properties.id")
                         ->join("propertylocations","propertylocations.property_id","=","properties.id")
                         ->select('package_type', DB::raw('COUNT(properties.id) as basic_count'))
                         ->where('package_type', 1)
                         ->where("propertylocations.agency_id",$agency->id)
                         ->where('propertylocations.area_en', $location->area_en)
                         ->groupBy('package_type')->get();

                 

                         $location->featured = DB::table('properties')
                         ->join("propertydetails","propertydetails.property_id","=","properties.id")
                         ->join("propertylocations","propertylocations.property_id","=","properties.id")
                         ->select('package_type', DB::raw('COUNT(properties.id) as featured_count'))
                         ->where('package_type', 2)
                         ->where("propertylocations.agency_id",$agency->id)
                         ->where('propertylocations.area_en', $location->area_en)
                         ->groupBy('package_type')->get();

                         $location->premium = DB::table('properties')
                         ->join("propertydetails","propertydetails.property_id","=","properties.id")
                         ->join("propertylocations","propertylocations.property_id","=","properties.id")
                         ->select('package_type', DB::raw('COUNT(properties.id) as premium_count'))
                         ->where('package_type', 3)
                         ->where("propertylocations.agency_id",$agency->id)
                         ->where('propertylocations.area_en', $location->area_en)
                         ->groupBy('package_type')->get();

         }



        return response()->json(["locations" =>$locations ]);
    }

    public function filterusagepackage(Request $request){

         $user = auth()->user();
        $agency=Agency::where('user_id',$user->id)->first();

        $users = $request->get('users');
        
        $alllocations = $request->get('locations');

        $usersArr = array();
        for($i=0;$i< count($users); $i++){
            array_push($usersArr, $users[$i]['id']);

        }

        $alllocationsArr = array();
        for($i=0;$i< count($alllocations); $i++){
            array_push($alllocationsArr, $alllocations[$i]['location']);

        }


        $locations = Propertylocation::distinct()
        ->join("agents","agents.id","propertylocations.agent_id")
        ->select("area_en","area_ar", DB::raw('COUNT(propertylocations.property_id) as property_count'))
        ->where("propertylocations.agency_id",$agency->id)
        ->where(function ($query1) use($usersArr){
        if(count($usersArr) > 0){ $query1->whereIn("agents.id",$usersArr); }
        })
        ->where(function ($query2) use($alllocationsArr){
            if(count($alllocationsArr) > 0){ 
                $query2->whereIn("propertylocations.emirate_en", $alllocationsArr); 
                $query2->orWhereIn("propertylocations.area_en", $alllocationsArr); 
                $query2->orWhereIn("propertylocations.streetorbuild_en", $alllocationsArr); 
            }
         })
        ->groupBy("area_en","area_ar")->get();
        foreach($locations as $location){
             $location->rent = DB::table('properties')
             ->join("propertydetails","propertydetails.property_id","=","properties.id")
             ->join("propertylocations","propertylocations.property_id","=","properties.id")
                   ->select('purpose', DB::raw('COUNT(properties.id) as rent_count'))
                   ->where('propertydetails.purpose', 1)
                   ->where('propertylocations.area_en', $location->area_en)
                   ->where("propertylocations.agency_id",$agency->id)
                   ->groupBy('purpose')->get();

                   $location->buy = DB::table('properties')
                   ->join("propertydetails","propertydetails.property_id","=","properties.id")
                   ->join("propertylocations","propertylocations.property_id","=","properties.id")
                         ->select('purpose', DB::raw('COUNT(properties.id) as buy_count'))
                         ->where('propertydetails.purpose', 2)
                         ->where('propertylocations.area_en', $location->area_en)
                         ->where("propertylocations.agency_id",$agency->id)
                         ->groupBy('purpose')->get();

                         $location->basic = DB::table('properties')
                         ->join("propertydetails","propertydetails.property_id","=","properties.id")
                         ->join("propertylocations","propertylocations.property_id","=","properties.id")
                         ->select('package_type', DB::raw('COUNT(properties.id) as basic_count'))
                         ->where('package_type', 1)
                         ->where('propertylocations.area_en', $location->area_en)
                         ->where("propertylocations.agency_id",$agency->id)
                         ->groupBy('package_type')->get();

                         $location->featured = DB::table('properties')
                         ->join("propertydetails","propertydetails.property_id","=","properties.id")
                         ->join("propertylocations","propertylocations.property_id","=","properties.id")
                         ->select('package_type', DB::raw('COUNT(properties.id) as featured_count'))
                         ->where('package_type', 2)
                         ->where('propertylocations.area_en', $location->area_en)
                         ->where("propertylocations.agency_id",$agency->id)
                         ->groupBy('package_type')->get();

                         $location->premium = DB::table('properties')
                         ->join("propertydetails","propertydetails.property_id","=","properties.id")
                         ->join("propertylocations","propertylocations.property_id","=","properties.id")
                         ->select('package_type', DB::raw('COUNT(properties.id) as premium_count'))
                         ->where('package_type', 3)
                         ->where('propertylocations.area_en', $location->area_en)
                         ->where("propertylocations.agency_id",$agency->id)
                         ->groupBy('package_type')->get();

         }
        return response()->json(["locations" =>$locations ]);
    }
    public function propertystatus()
    {
        return "property status";
    }
}
