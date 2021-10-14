<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agent;
use App\Models\Property;
use App\Models\Language;
use App\Models\Agency;
use App\Models\Propertydetail;
use App\Models\Propertyfeature;
use App\Models\Media;
use App\Models\Propertylocation;
use DB;
class AgentController extends Controller
{
    public function index()
    {
      $agents = Agent::join('users','users.id','agencies.user_id')
      ->get(array('agencies.*','users.email','users.profile','users.active'));
      return response()->json(["agents" => $agents]);
    }
    public function show($id){
        $agent = Agent::withCount('property')->get();
        $agent = Agent::join('users','users.id','agents.user_id')
        ->where('agents.id',$id)
        ->first(array('agents.*','users.email','users.profile'));
        $properties = Property::join('propertydetails','propertydetails.property_id','properties.id')
        ->where("agent_id",$id)->get(array('properties.*','propertydetails.beds','propertydetails.baths',
        'propertydetails.area'));
        return response()->json(["agent" =>$agent,"properties" =>$properties]);
      }

      public function locationandagent(){
        $fulllocation = [];
        $emirates= DB::table("emirates")
        ->join("propertylocations",'propertylocations.emirate_en',"emirates.emirate_en")
        ->distinct()
        ->get(array("emirates.emirate_en as title"));
           $location= DB::table('propertylocations')
            ->select('area_en as title')
            ->groupBy('area_en')
            ->get();
           $agent= Agent::join("propertylocations","propertylocations.agent_id","agents.id")->distinct()
           ->get(array("agents.name_en as title"));
           $locations= $emirates->merge($location);
           $agentsandlocations= $locations->merge($agent);
           return response()->json(["agentsandlocations" =>$agentsandlocations]);
          }
      public function filteragent(Request $request)
      {
       if($request->agentAgentViewSwitcher == "agency"){

        $agencies = Agency::join("propertylocations","propertylocations.agency_id","=","agencies.id")
            ->distinct()
            ->where("propertylocations.emirate_en",'LIKE', '%'.$request->selectedLocation.'%')
            ->orWhere("agencies.name_en",'LIKE', '%'.$request->selectedLocation.'%')
        ->select('agencies.*')
        ->withCount('agents')->withCount('property')->get();
        
        $agents = Agent::join('countries','countries.id','=','agents.nationality')
        ->leftJoin('agentlanguages','agentlanguages.agent_id','=','agents.id')
        ->leftJoin('languages','languages.value','=','agentlanguages.language_id')
        ->join('agencies','agencies.id','agents.agency_id')
        ->leftJoin('jobs','jobs.id','agents.job_id')
        ->join('propertylocations','propertylocations.agent_id','agents.id')
        ->where(function ($query1) use($request){
        if($request->selectedLocation  !=null){ 
           $query1->where('propertylocations.emirate_en','LIKE', '%'.$request->selectedLocation.'%' )
           ->orWhere('propertylocations.area_en','LIKE', '%'.$request->selectedLocation.'%' )
           ->orWhere('agents.name_en','LIKE', '%'.$request->selectedLocation.'%' );
          }
        })->distinct() ->select('agents.*',"countries.country_enNationality",
        "countries.country_arNationality","agencies.name_en as agency_en","agencies.name_ar as agency_ar","agencies.logo",
        "jobs.job_title_en","jobs.job_title_ar")
        ->withCount('agentproperty')
        ->get();
        foreach($agents as $agent){
          $agent->language = Language::join('agentlanguages','agentlanguages.language_id','=','languages.value')->distinct()
          ->where("agentlanguages.agent_id",$agent->id)
          ->get();
        }
        return response()->json(["agencies" => $agencies,"agents" => $agents,"switcher"=>$request->agentAgentViewSwitcher]);

       }
       else{
        

        $agencies = Agency::join("propertylocations","propertylocations.agency_id","=","agencies.id")
        ->join('users','users.id','agencies.user_id')
        ->distinct()
        ->select('agencies.*','users.active')
        ->withCount('agents')->withCount('property')->get();
        
        $agents = Agent::join('countries','countries.id','=','agents.nationality')
        ->leftJoin('agentlanguages','agentlanguages.agent_id','=','agents.id')
        ->leftJoin('languages','languages.value','=','agentlanguages.language_id')
        ->join('agencies','agencies.id','agents.agency_id')
        ->leftJoin('jobs','jobs.id','agents.job_id')
        ->join('propertylocations','propertylocations.agent_id','agents.id')
        ->where(function ($query1) use($request){
        if($request->selectedNationality > 0){ $query1->where('agents.nationality', $request->selectedNationality); }
        if($request->selectedArea > 0){ $query1->where('selectedArea', $request->selectedArea); }
        if($request->selectedLanguage  >0){  $query1->where('agentlanguages.language_id', $request->selectedLanguage); }
        if($request->selectedLocation  !=null){  $query1->where('agents.name_en','LIKE', '%'.$request->selectedLocation.'%' ); }
        })


        ->where(function ($query2) use($request){
          if($request->selectedLocation  !=null){ 
            $query2->where('propertylocations.emirate_en','LIKE', '%'.$request->selectedLocation.'%' )
            ->orWhere('propertylocations.area_en','LIKE', '%'.$request->selectedLocation.'%' )
            ->orWhere('agents.name_en','LIKE', '%'.$request->selectedLocation.'%' );
           }
        })
        
        ->distinct() ->select('agents.*',"countries.country_enNationality",
        "countries.country_arNationality","agencies.name_en as agency_en","agencies.name_ar as agency_ar","agencies.logo",
        "jobs.job_title_en","jobs.job_title_ar")
        ->withCount('agentproperty')
        ->get();
        foreach($agents as $agent){
          $agent->language = Language::join('agentlanguages','agentlanguages.language_id','=','languages.value')->distinct()
          ->where("agentlanguages.agent_id",$agent->id)
          ->get();
        }
        return response()->json(["agencies" => $agencies,"agents" => $agents,"switcher"=>$request->agentAgentViewSwitcher]);

       }
     
      }

      public function showagent($id)
      {
        $agent = Agent::join("users","users.id","=","agents.user_id")
        ->where("agents.id",$id)
        ->first(array("agents.*","users.email"));
        return response()->json($agent);
      }


}
