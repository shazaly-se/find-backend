<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Agency;
use App\Models\Agent;
use App\Models\Property;
use DB;

class AgenciesAgentController extends Controller
{
    public function agents(){
        $agencies = Agency::join("agents","agents.agency_id","=","agencies.id")
            ->get(array("agents.*","agencies.name_en as company_name_en","agencies.name_ar as company_name_ar","agencies.logo"));
        return response()->json(["agencies" =>$agencies ]);
    }

    public function properties(){
         $agencies = Agency::get();

         foreach($agencies as $agency){
             $agency->rent = DB::table('properties')
             ->join("propertydetails","propertydetails.property_id","=","properties.id")
                   ->select('purpose', DB::raw('COUNT(properties.id) as rent_count'))
                   ->where('propertydetails.purpose', 1)
                   ->where('properties.agency_id', $agency->id)
                   ->groupBy('purpose')->get();

                   $agency->buy = DB::table('properties')
                   ->join("propertydetails","propertydetails.property_id","=","properties.id")
                         ->select('purpose', DB::raw('COUNT(properties.id) as buy_count'))
                         ->where('propertydetails.purpose', 2)
                         ->where('properties.agency_id', $agency->id)
                         ->groupBy('purpose')->get();

         }

         return response()->json(["agencies" =>$agencies ]);
      
    }

    public function agency(){
        $agencies = Agency::join("users","users.id","=","agencies.user_id")
        ->get(array("agencies.*","users.email"));

        foreach($agencies as $agency){
            $agency->agent = DB::table('agents')
                  ->select('agency_id', DB::raw('COUNT(agents.id) as agent_count'))
                  ->where('agents.agency_id', $agency->id)
                  ->groupBy('agency_id')->get();
                  $agency->property = DB::table('properties')
                  ->join("propertydetails","propertydetails.property_id","=","properties.id")
                        ->select('agency_id', DB::raw('COUNT(properties.id) as property_count'))
                        ->where('properties.agency_id', $agency->id)
                        ->groupBy('agency_id')->get();
        }
        return response()->json(["agencies" =>$agencies ]);
    }
    public function agencyagentdetails($id){
        $agency = Agency::where("id",$id)->first();

        $agents = DB::table('agents')
                   ->select('agency_id', DB::raw('COUNT(agents.id) as agent_count'))
                   ->where('agency_id', $agency->id)
                   ->groupBy('agency_id')->get();

                   $total_rent = DB::table('properties')
                   ->join("propertydetails","propertydetails.property_id","=","properties.id")
                   ->select('agency_id', DB::raw('COUNT(properties.id) as property_count'))
                   ->where('agency_id', $agency->id)
                   ->where('purpose', 1)
                   ->groupBy('agency_id')->get();

                   $total_buy = DB::table('properties')
                   ->join("propertydetails","propertydetails.property_id","=","properties.id")
                   ->select('agency_id', DB::raw('COUNT(properties.id) as property_count'))
                   ->where('agency_id', $agency->id)
                   ->where('purpose', 2)
                   ->groupBy('agency_id')->get();



       $properties = Property::join("propertydetails","propertydetails.property_id","=","properties.id")
       ->join("agents","agents.id","=","properties.agent_id")
       ->join("propertytypes","propertytypes.id","=","properties.propertytypes_id ")
       ->where("properties.agency_id",$id)
       ->get(array("agents.name_en","agents.name_ar","properties.title_en","properties.title_ar","properties.title_ar"));

       return response()->json(["properties" =>$properties,"agency" =>$agency,"agents" =>$agents,"total_rent" => $total_rent,"total_buy" => $total_buy ]);
    }
}
