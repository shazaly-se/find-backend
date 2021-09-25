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
class AllPropertyController extends Controller
{
    public function index(){
        $allproperties = Property::join('propertydetails','propertydetails.property_id','properties.id')
        ->join('propertytypes','propertytypes.id','properties.propertytypes_id')
        ->join('agents','agents.id','properties.agent_id')
        ->join('users','users.id','agents.user_id')
       
        ->get(array('properties.*','propertydetails.beds','propertydetails.baths',
        'propertydetails.area','agents.name_en','agents.name_ar','agents.mobile','propertytypes.typeName_en',
        'propertytypes.typeName_ar','agents.profile',"purpose"));
        return response()->json(["allproperties" =>$allproperties]);

    }

    public function details($id){
        $allproperties = Property::join('propertydetails','propertydetails.property_id','properties.id')
        ->join('propertytypes','propertytypes.id','properties.propertytypes_id')
        ->join('agents','agents.id','properties.agent_id')
        ->join('users','users.id','agents.user_id')
        ->where('properties.id',$id)
        ->first(array('properties.*','propertydetails.beds','propertydetails.baths',
        'propertydetails.area','agents.name','agents.name_ar','agents.mobile','propertytypes.typeName_en',
        'propertytypes.typeName_ar','users.profile'));
        return response()->json(["allproperties" =>$allproperties]);

    }

    public function show($id){
                $property = Property::join('propertydetails','propertydetails.property_id','=','properties.id')
                ->join('propertylocations','propertylocations.property_id','=','properties.id')
                ->join('agents','agents.id','properties.agent_id')
                ->join('users','users.id','agents.user_id')
                ->join('propertytypes','propertytypes.id','properties.propertytypes_id')
                ->where('properties.id',$id)->with('location')->first(array('properties.*','propertydetails.beds',
                'propertydetails.baths','propertydetails.area','propertydetails.purpose','agents.name_en as name_en',
                'agents.name_ar','agents.mobile','agents.email','agents.profile','propertytypes.typeName_en','propertytypes.typeName_ar'
                //'propertylocations.location_name_en',
            ));
            //return $property;

            $similar = Property::join('propertydetails','propertydetails.property_id','=','properties.id')
            ->join('propertylocations','propertylocations.property_id','=','properties.id')
            ->join('agents','agents.id','=','properties.agent_id')
            ->join('users','users.id','=','agents.user_id')
            ->join('propertytypes','propertytypes.id','properties.propertytypes_id')
            ->where('purpose',$property->purpose)
            ->where('propertytypes.id',$property->propertytypes_id)
            ->where('propertylocations.emirate_en',$property->location->emirate_en)->take(3)
            ->get();
                //return response()->json($property);
                $amenities= Propertyfeature::join('features','features.id','=','propertyfeatures.feature_id')
            ->where('property_id',$property->id)->get();

            // return $properties;

            $medias= Media::where('property_id',$property->id)->get();
            //$agent= Agent::where('property_id',$property->id)->get();
                return response()->json(["property" => $property,"amenities"=>$amenities,"medias"=>$medias,"similar" =>$similar]);
    }

    public function search(Request $request,$data)
    {
       $searchInput = $request->input('somedata');
       //return $searchInput;

        $property = Property::join('propertydetails','propertydetails.property_id','=','properties.id')
         ->join('propertylocations','propertylocations.property_id','=','properties.id')
         ->where("propertylocations.location_name_en",'LIKE', '%'.$data.'%')
         ->orWhere("propertylocations.location_name_ar",'LIKE', '%'.$data.'%')
         ->get();

         return response()->json(["property" => $property]);
    }

    public function autocomplete(Request $request)
    {
       $searchInput = $request->input('somedata');
       //return $searchInput;

        $propertieslocations = Property::join('propertydetails','propertydetails.property_id','=','properties.id')
         ->join('propertylocations','propertylocations.property_id','=','properties.id')
         //->where("propertylocations.location_name_en",'LIKE', '%'.$request->locations[$i]["label"].'%')
      
         ->get(array("propertylocations.id as value","propertylocations.location_name_en as label"));

         return response()->json(["propertieslocations" => $propertieslocations]);
    }

    public function filter(Request $request)
    {  
  // return $request->all();
//   foreach($request->locations as $locs){
//      return $locs["label"];

//   }

//   for($i=0;$i< count($request->locations); $i++){
//      echo  $i;

//    // $query->where('propertylocations.location_name_en', $request->locations[$i]["label"]);
//   //  ->orWhere('propertylocations.location_name_ar', 'LIKE', '%'.$request->locations[$i]["label"].'%');
  
// }

  
            $property = Property::join('propertydetails','propertydetails.property_id','=','properties.id')
            ->join('propertylocations','propertylocations.property_id','=','properties.id')
            // ->where(function ($query1) use($request){
            // if($request->propertytypes_id > 0){ $query1->where('propertytypes_id', $request->propertytypes_id); }
            // if($request->bed_room > 0){ $query1->where('beds', $request->bed_room); }
            // if($request->bath_room > 0){  $query1->where('baths', $request->bath_room); }
            // if($request->price_from > 0 && $request->price_to > 0){ $query1->whereBetween("price",[$request->price_from, $request->price_to]); }
            // if($request->price_from > 0 ){ $query1->where("price",'>=',$request->price_from); }
            // if($request->price_to > 0){ $query1->where("price",'<=',$request->price_to);  }
            // if($request->frequency > 0){ $query1->where("rent_frequency",'<=',$request->frequency); }
            // })                    
            ->where(function($query) use ($request){

                foreach($request->locations as $locs){
                  

                    $query->where('propertylocations.location_name_en', $locs["label"])
                   ->orWhere('propertylocations.location_name_ar', $locs["label"]);
                  
                }

          
           })->get();
           return response()->json(["property" => $property]);

     }



    public function recent(){
        $recentproperties = Property::join('propertydetails','propertydetails.property_id','properties.id')
        ->join('propertytypes','propertytypes.id','properties.propertytypes_id')
        ->join('agents','agents.id','properties.agent_id')
        ->join('users','users.id','agents.user_id')
        ->orderBy('properties.created_at','desc')
        ->take(6)
        ->get(array('properties.*','propertydetails.beds','propertydetails.baths',
        'propertydetails.area','agents.name_en','agents.name_ar','agents.mobile','propertytypes.typeName_en',
        'propertytypes.typeName_ar','users.profile','users.email','properties.image'));
        return response()->json(["recentproperties" =>$recentproperties]);

    }

    public function allfiles(Request $request){
        return $request->all();
    }

    public function test(Request $request,$data){
       return Propertylocation::where('emirate_en',$request->location)->get();
       // return $data;
        return $request->all();
    }


    
    public function location(){

        $fulllocation = [];
        $emirates= DB::table("emirates")->get(array("id","emirate_en as location"));
       // return $emirates;
    
           $location= Propertylocation::get(array("id","area_en as location"));
      

           $locations= $emirates->merge($location);
           return response()->json(["locations" =>$locations]);
   
     }

     public function getlocation($switcher,$data){
        // return $switcher;
        // return "hi";

        // if (empty($data)) {
        //     return "empty";
        //  }else{
        //      return "not empty";
        //  }

        if($switcher == "agency")
        {

            $emirates= DB::table("emirates")
            ->join("propertylocations",'propertylocations.emirate_en',"emirates.emirate_en")
            ->where(function ($query) use($data){
            if(!is_null($data)){ 
                $query->where("propertylocations.emirate_en",'LIKE', '%'.$data.'%');
            
            }
            })

           // ->where("propertylocations.emirate_en",'LIKE', '%'.$data.'%')
            ->distinct()
            ->get(array("emirates.emirate_en as title"));
 
            $location= DB::table('propertylocations')
            ->where(function ($query1) use($data){
                if(!is_null($data)){ 
                    $query1->where("propertylocations.emirate_en",'LIKE', '%'.$data.'%');
                
                }
                })

            //->where("propertylocations.emirate_en",'LIKE', '%'.$data.'%')
            ->select('area_en as title')
            ->groupBy('area_en')
            ->get();
 
            $agency= Agency::join("propertylocations","propertylocations.agency_id","=","agencies.id")
            ->distinct()

            ->where(function ($query2) use($data){
                if(!is_null($data)){ 
                    $query2->where("propertylocations.emirate_en",'LIKE', '%'.$data.'%')
                    ->orWhere("agencies.name_en",'LIKE', '%'.$data.'%');
                }
                })

           // ->where("propertylocations.emirate_en",'LIKE', '%'.$data.'%')
           // ->orWhere("agencies.name_en",'LIKE', '%'.$data.'%')
           ->get(array("agencies.name_en as title"));
           $locations= $emirates->merge($location);
           $agenciesandlocations= $locations->merge($agency);
           return response()->json(["switcher" =>1,"agenciesandlocations" =>$agenciesandlocations,"data"=>$data]);
        }
        else{
           $emirates= DB::table("emirates")
           ->join("propertylocations",'propertylocations.emirate_en',"emirates.emirate_en")
           ->where("propertylocations.emirate_en",'LIKE', '%'.$data.'%')
           ->distinct()
           ->get(array("emirates.emirate_en as title"));
           $location= DB::table('propertylocations')
           ->where("propertylocations.emirate_en",'LIKE', '%'.$data.'%')
           ->select('area_en as title')
           ->groupBy('area_en')
           ->get();
           $agent= Agent::join("propertylocations","propertylocations.agent_id","agents.id")
           ->distinct()
           ->where("propertylocations.emirate_en",'LIKE', '%'.$data.'%')
           ->orWhere("agents.name_en",'LIKE', '%'.$data.'%')
           ->get(array("agents.name_en as title"));
          $locations= $emirates->merge($location);
          $agentsandlocations= $locations->merge($agent);
          return response()->json(["switcher" =>2,"agentsandlocations" =>$agentsandlocations,"data"=>$data]);
        }
     }

     public function filterproperties(Request $request)
     {

     $selectedLocations = $request->selectedLocations;
     $selectedFeatures = $request->selectedFeatures;

      $locationsArr = array();
      if(count($request->selectedLocations) > 0){
        for($i=0;$i< count($selectedLocations); $i++){
            array_push($locationsArr, $selectedLocations[$i]['location']);
        }
      }

      $featuresArr = array();
      if(count($request->selectedFeatures) > 0){
        for($i=0;$i< count($selectedFeatures); $i++){
            array_push($featuresArr, $selectedFeatures[$i]['label']);
        }
      }
      
    $filteredproperties = Property::join('propertydetails','propertydetails.property_id','properties.id')
    ->join('propertytypes','propertytypes.id','properties.propertytypes_id')
    ->join('agents','agents.id','properties.agent_id')
    ->join('users','users.id','agents.user_id')
    ->leftJoin('propertylocations','propertylocations.property_id','properties.id')
    ->join('propertyfeatures','propertyfeatures.property_id','properties.id')
    ->join('features','features.id','propertyfeatures.feature_id')
    ->where(function ($query) use($request){
     if($request->purpose > 0){ $query->where('propertydetails.purpose', $request->purpose); }
     if($request->propertyType > 0){ $query->where('properties.propertytypes_id', $request->propertyType); }
     if($request->bedRoomSelected > -1){ $query->where('beds', $request->bedRoomSelected); }
     if($request->bathRoomSelected > 0){ $query->where('baths', $request->bathRoomSelected); }
     if($request->frequencySelected > 0){ $query->where('rent_frequency', $request->frequencySelected); }

     })
     ->where(function ($query2) use($locationsArr){
        if(count($locationsArr) > 0){ 
            $query2->whereIn("propertylocations.emirate_en", $locationsArr); 
            $query2->orWhereIn("propertylocations.area_en", $locationsArr); 
        }
     })

     ->where(function ($query3) use($featuresArr){
        if(count($featuresArr) > 0){ 
            $query3->whereIn("features.feature_en", $featuresArr); 
            $query3->orWhereIn("features.feature_ar", $featuresArr); 
        }
     })->distinct()

    ->get(array('properties.*','propertydetails.beds','propertydetails.baths',
    'propertydetails.area','agents.name_en','agents.name_ar','agents.mobile','propertytypes.typeName_en',
    'propertytypes.typeName_ar','agents.profile',"purpose"));
    return response()->json(["filteredproperties" =>$filteredproperties]);
     }
}
