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

        $allproperties = Property::join('property','propertydetails.property_id','properties.id')
                                  ->join('propertytypes','propertytypes.id','properties.propertytypes_id')
                                  ->join('agents','agents.id','properties.agent_id')
                                  ->join('users','users.id','agents.user_id')
                                  ->get(array('properties.*','propertydetails.beds','propertydetails.baths',
                          'propertydetails.area','agents.name_en','agents.name_ar','agents.mobile','propertytypes.typeName_en',
                     'propertytypes.typeName_ar','agents.profile',"purpose"));
                 return response()->json(["allproperties" =>$allproperties]);
                   }

                   public function propertymap(){

                    $propertiesmap = Property::join('propertylocations','propertylocations.property_id','properties.id')
                    ->join('propertydetails','propertydetails.property_id','properties.id')
                    ->join('propertytypes','propertytypes.id','properties.propertytypes_id')
                            ->get(array('properties.id','properties.title_en','properties.title_ar',"properties.price","properties.image",
                            'propertylocations.lat','propertylocations.lng',
                            "propertydetails.beds","propertydetails.baths","propertydetails.purpose",
                            "propertydetails.area","propertytypes.typeName_en","propertytypes.typeName_ar"
                                ));
                         return response()->json($propertiesmap);
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
             
            ));

        $similar = Property::join('propertydetails','propertydetails.property_id','=','properties.id')
                           ->join('propertylocations','propertylocations.property_id','=','properties.id')
                           ->join('agents','agents.id','=','properties.agent_id')
                           ->join('users','users.id','=','agents.user_id')
                           ->join('propertytypes','propertytypes.id','properties.propertytypes_id')
                           ->where('purpose',$property->purpose)
                           ->where('propertytypes.id',$property->propertytypes_id)
                           ->where('propertylocations.emirate_en',$property->location->emirate_en)->take(3)
                           ->get();
        $amenities= Propertyfeature::join('features','features.id','=','propertyfeatures.feature_id')
                                   ->where('property_id',$property->id)->get();

        $medias= Media::where('property_id',$property->id)->get();
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
  
            $property = Property::join('propertydetails','propertydetails.property_id','=','properties.id')
            ->join('propertylocations','propertylocations.property_id','=','properties.id')                   
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
        return $request->all();
    }
    public function location(){

       $emirates= DB::table("propertylocations")->join("properties","properties.id","propertylocations.property_id")->distinct()
       ->get(array("propertylocations.emirate_en as location"));

       foreach($emirates as $emirate){
        $emirate->type=1;
       }
        $areas= DB::table("propertylocations")->join("properties","properties.id","propertylocations.property_id")->distinct()
       ->get(array("propertylocations.area_en as location"));

       foreach($areas as $area){
        $area->type=2;
       }
       $buildingorstreets= DB::table("propertylocations")->join("properties","properties.id","propertylocations.property_id")->distinct()
       ->get(array("propertylocations.streetorbuild_en as location"));

       foreach($buildingorstreets as $buildingorstreet){
        $buildingorstreet->type=3;
       }
          $emirateandarea= $emirates->merge($areas);
          $emirateandarea_and_buildingorstreet= $emirateandarea->merge($buildingorstreets);
           return response()->json(["locations" =>$emirateandarea_and_buildingorstreet]);
   
     }

     public function getlocation($switcher,$data){
        if($switcher == "agency")
        {
            $emirates= DB::table("emirates")
            ->join("propertylocations",'propertylocations.emirate_en',"emirates.emirate_en")
            ->where(function ($query) use($data){
            if(!is_null($data)){ 
                $query->where("propertylocations.emirate_en",'LIKE', '%'.$data.'%');
            
            }
            })
            ->distinct()
            ->get(array("emirates.emirate_en as title"));
            $location= DB::table('propertylocations')
            ->where(function ($query1) use($data){
                if(!is_null($data)){ 
                    $query1->where("propertylocations.emirate_en",'LIKE', '%'.$data.'%');
                
                }
                })
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



      
       $subLocationSend = $request->subLocationSend;
       


     $selectedLocations = $request->selectedLocations;
   

      $locationsArr = array();
      if(count($request->selectedLocations) > 0){
        for($i=0;$i< count($selectedLocations); $i++){
            array_push($locationsArr, $selectedLocations[$i]['location']);
        }
      }
      $selectedFeatures = $request->selectedFeatures;
      $featuresArr = array();
      if(count($request->selectedFeatures) > 0){
        for($i=0;$i< count($selectedFeatures); $i++){
            array_push($featuresArr, $selectedFeatures[$i]['label']);
        }
      }

      if($request->popular =="")
      {

       // return "cooo";
          
        $filteredproperties = Property::join('propertydetails','propertydetails.property_id','properties.id')
        ->join('propertytypes','propertytypes.id','properties.propertytypes_id')
        ->join('agents','agents.id','properties.agent_id')
        ->join('agencies','agencies.id','properties.agency_id')
        ->join('users','users.id','agents.user_id')
        ->join('propertylocations','propertylocations.property_id','properties.id')
        ->leftJoin('propertyfeatures','propertyfeatures.property_id','properties.id')
        ->leftJoin('features','features.id','propertyfeatures.feature_id')
        ->leftJoin('wishlists','wishlists.property_id','properties.id')
        ->where(function ($query) use($request){
         if($request->purpose > 0){ $query->where('propertydetails.purpose', $request->purpose); }
         if($request->propertyType > 0){ $query->where('properties.propertytypes_id', $request->propertyType); }
         if($request->bedRoomSelected > -1){ $query->where('beds', $request->bedRoomSelected); }
         if($request->bathRoomSelected > 0){ $query->where('baths', $request->bathRoomSelected); }
         if($request->frequencySelected > 0){ $query->where('rent_frequency', $request->frequencySelected); }
         if($request->furnished > -1){ $query->where('propertydetails.furnishing', $request->furnished); }
         if($request->priceFrom > 0 && $request->priceTo  > 0 ){ $query->whereBetween('price', [$request->priceFrom , $request->priceTo ]); }
         if($request->priceFrom > 0 && $request->priceTo  == 0 ){ $query->where('price', '>=' ,$request->priceFrom ); }
         if($request->priceFrom == 0 && $request->priceTo  > 0 ){ $query->where('price', '<=' ,$request->priceTo ); }
        //  if(count($request->subLocationSend) > 0){
        //  if($request->subLocationSend["type"] == 1)
        //  { 
        //      $query->where('propertylocations.emirate_en', $request->subLocationSend["location"]);
        //  }
        //          if($request->subLocationSend["type"] == 2)
        //          { 
        //              $query->where('propertylocations.area_en', $request->subLocationSend["location"]);
        //          }
        //  if($request->subLocationSend["type"] == 3)
        //  { 
        //      $query->where('propertylocations.streetorbuild_en',$request->subLocationSend["location"]);
        //  }
         
        
        // }
    
         })
         ->where(function ($query2) use($locationsArr){
            if(count($locationsArr) > 0){ 
                $query2->whereIn("propertylocations.emirate_en", $locationsArr); 
                $query2->orWhereIn("propertylocations.area_en", $locationsArr); 
                $query2->orWhereIn("propertylocations.streetorbuild_en", $locationsArr); 
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
        'propertytypes.typeName_ar','agencies.logo',"purpose","wishlists.status as wishlist_status","propertylocations.lat","propertylocations.lng"));
        // SUB LOCATIONS QUERY 

        if(count($request->subLocationSend) == 0){

            if(count($featuresArr) > 0){
                $emirates=  DB::table("propertylocations")
                ->join("properties","properties.id","propertylocations.property_id")
                ->join('propertydetails','propertydetails.property_id','properties.id')
                ->leftJoin('propertyfeatures','propertyfeatures.property_id','properties.id')
                ->leftJoin('features','features.id','propertyfeatures.feature_id')
    
                ->select('propertylocations.emirate_en as location', DB::raw('COUNT(properties.id) as property_count'))
                ->groupBy('propertylocations.emirate_en')
                 ->where(function ($query) use($request){
                   if($request->purpose > 0){ $query->where('propertydetails.purpose', $request->purpose); }
                   if($request->propertyType > 0){ $query->where('properties.propertytypes_id', $request->propertyType); }
                   if($request->bedRoomSelected > -1){ $query->where('beds', $request->bedRoomSelected); }
                   if($request->bathRoomSelected > 0){ $query->where('baths', $request->bathRoomSelected); }
                   if($request->frequencySelected > 0){ $query->where('rent_frequency', $request->frequencySelected); }
                   if($request->furnished > -1){ $query->where('propertydetails.furnishing', $request->furnished); }
          
                   if($request->priceFrom > 0 && $request->priceTo  > 0 ){ $query->whereBetween('price', [$request->priceFrom , $request->priceTo ]); }

                   if($request->priceFrom > 0 && $request->priceTo  == 0 ){ $query->where('price', '>=' ,$request->priceFrom ); }
          
                   if($request->priceFrom == 0 && $request->priceTo  > 0 ){ $query->where('price', '<=' ,$request->priceTo ); }
                 })
                 ->distinct()
                 ->where(function ($query3) use($featuresArr){
                    if(count($featuresArr) > 0){ 
                        $query3->whereIn("features.feature_en", $featuresArr); 
                        $query3->orWhereIn("features.feature_ar", $featuresArr); 
                    }
                 })->distinct()
                ->get();
                foreach($emirates as $emirate){
                    $emirate->type=1;
                   }
                   

            }else{

                $emirates=  DB::table("propertylocations")
                ->join("properties","properties.id","propertylocations.property_id")
                ->join('propertydetails','propertydetails.property_id','properties.id')
                
    
                ->select('propertylocations.emirate_en as location', DB::raw('COUNT(properties.id) as property_count'))
                ->groupBy('propertylocations.emirate_en')
                 ->where(function ($query) use($request){
                   if($request->purpose > 0){ $query->where('propertydetails.purpose', $request->purpose); }
                   if($request->propertyType > 0){ $query->where('properties.propertytypes_id', $request->propertyType); }
                   if($request->bedRoomSelected > -1){ $query->where('beds', $request->bedRoomSelected); }
                   if($request->bathRoomSelected > 0){ $query->where('baths', $request->bathRoomSelected); }
                   if($request->frequencySelected > 0){ $query->where('rent_frequency', $request->frequencySelected); }
                   if($request->furnished > -1){ $query->where('propertydetails.furnishing', $request->furnished); }
          
                   if($request->priceFrom > 0 && $request->priceTo  > 0 ){ $query->whereBetween('price', [$request->priceFrom , $request->priceTo ]); }

                   if($request->priceFrom > 0 && $request->priceTo  == 0 ){ $query->where('price', '>=' ,$request->priceFrom ); }
          
                   if($request->priceFrom == 0 && $request->priceTo  > 0 ){ $query->where('price', '<=' ,$request->priceTo ); }

                 })
                 ->distinct()
    
                ->get();
                foreach($emirates as $emirate){
                    $emirate->type=1;
                   }
            }

         
            
        
         
            return response()->json(["filteredproperties" =>$filteredproperties,"sublocation"=>$emirates]);
          
           }else{
        
               if($request->subLocationSend["type"] == 1)  {

                if(count($featuresArr) > 0){
                    $areas= DB::table("propertylocations")->join("properties","properties.id","propertylocations.property_id")
                    ->join('propertydetails','propertydetails.property_id','properties.id')
                    ->leftJoin('propertyfeatures','propertyfeatures.property_id','properties.id')
                    ->leftJoin('features','features.id','propertyfeatures.feature_id')
                    ->select('propertylocations.area_en as location',DB::raw('COUNT(properties.id) as property_count'))
                    ->where('propertylocations.emirate_en',$request->subLocationSend["location"])->distinct()
                    ->groupBy('propertylocations.area_en')->where(function ($query) use($request){
                        if($request->purpose > 0){ $query->where('propertydetails.purpose', $request->purpose); }
                   if($request->propertyType > 0){ $query->where('properties.propertytypes_id', $request->propertyType); }
                   if($request->bedRoomSelected > -1){ $query->where('beds', $request->bedRoomSelected); }
                   if($request->bathRoomSelected > 0){ $query->where('baths', $request->bathRoomSelected); }
                   if($request->frequencySelected > 0){ $query->where('rent_frequency', $request->frequencySelected); }
                   if($request->furnished > -1){ $query->where('propertydetails.furnishing', $request->furnished); }
          
                   if($request->priceFrom > 0 || $request->priceTo  > 0 ){ $query->whereBetween('price', [$request->priceFrom , $request->priceTo ]); }
                      })
                      ->where(function ($query3) use($featuresArr){
                        if(count($featuresArr) > 0){ 
                            $query3->whereIn("features.feature_en", $featuresArr); 
                            $query3->orWhereIn("features.feature_ar", $featuresArr); 
                        }
                     })->distinct()
                      ->get();
    
             
                    foreach($areas as $area){
                     $area->type=2;
                    }
                }else{

                    $areas= DB::table("propertylocations")->join("properties","properties.id","propertylocations.property_id")
                    ->join('propertydetails','propertydetails.property_id','properties.id')
                    ->select('propertylocations.area_en as location',DB::raw('COUNT(properties.id) as property_count'))
                    ->where('propertylocations.emirate_en',$request->subLocationSend["location"])->distinct()
                    ->groupBy('propertylocations.area_en')->where(function ($query) use($request){
                        if($request->purpose > 0){ $query->where('propertydetails.purpose', $request->purpose); }
                   if($request->propertyType > 0){ $query->where('properties.propertytypes_id', $request->propertyType); }
                   if($request->bedRoomSelected > -1){ $query->where('beds', $request->bedRoomSelected); }
                   if($request->bathRoomSelected > 0){ $query->where('baths', $request->bathRoomSelected); }
                   if($request->frequencySelected > 0){ $query->where('rent_frequency', $request->frequencySelected); }
                   if($request->furnished > -1){ $query->where('propertydetails.furnishing', $request->furnished); }
          
                   if($request->priceFrom > 0 && $request->priceTo  > 0 ){ $query->whereBetween('price', [$request->priceFrom , $request->priceTo ]); }

                   if($request->priceFrom > 0 && $request->priceTo  == 0 ){ $query->where('price', '>=' ,$request->priceFrom ); }
          
                   if($request->priceFrom == 0 && $request->priceTo  > 0 ){ $query->where('price', '<=' ,$request->priceTo ); }
                      })->get();
    
             
                    foreach($areas as $area){
                     $area->type=2;
                    }

                }
        
         
                return response()->json(["filteredproperties" =>$filteredproperties,"sublocation"=>$areas]);
        
               } else if($request->subLocationSend["type"] == 2) {

                if(count($featuresArr) > 0){
                    $buildingorstreets= DB::table("propertylocations")->join("properties","properties.id","propertylocations.property_id")
                    ->join('propertydetails','propertydetails.property_id','properties.id')
                    ->leftJoin('propertyfeatures','propertyfeatures.property_id','properties.id')
                    ->leftJoin('features','features.id','propertyfeatures.feature_id')
                     ->select('propertylocations.streetorbuild_en as location', DB::raw('COUNT(properties.id) as property_count'))
                    ->where('propertylocations.area_en',$request->subLocationSend["location"])->distinct()
                    ->groupBy('propertylocations.streetorbuild_en')->where(function ($query) use($request){
                        if($request->purpose > 0){ $query->where('propertydetails.purpose', $request->purpose); }
                        if($request->propertyType > 0){ $query->where('properties.propertytypes_id', $request->propertyType); }
                        if($request->bedRoomSelected > -1){ $query->where('beds', $request->bedRoomSelected); }
                        if($request->bathRoomSelected > 0){ $query->where('baths', $request->bathRoomSelected); }
                        if($request->frequencySelected > 0){ $query->where('rent_frequency', $request->frequencySelected); }
                        if($request->furnished > -1){ $query->where('propertydetails.furnishing', $request->furnished); }
               
                        if($request->priceFrom > 0 && $request->priceTo  > 0 ){ $query->whereBetween('price', [$request->priceFrom , $request->priceTo ]); }

                        if($request->priceFrom > 0 && $request->priceTo  == 0 ){ $query->where('price', '>=' ,$request->priceFrom ); }
               
                        if($request->priceFrom == 0 && $request->priceTo  > 0 ){ $query->where('price', '<=' ,$request->priceTo ); }
                      })
                      ->where(function ($query3) use($featuresArr){
                        if(count($featuresArr) > 0){ 
                            $query3->whereIn("features.feature_en", $featuresArr); 
                            $query3->orWhereIn("features.feature_ar", $featuresArr); 
                        }
                     })->distinct()
                     ->get();
                    foreach($buildingorstreets as $buildingorstreet){
                     $buildingorstreet->type=3;
                    }
                }
                else{
                    $buildingorstreets= DB::table("propertylocations")->join("properties","properties.id","propertylocations.property_id")
                    ->join('propertydetails','propertydetails.property_id','properties.id')
                     ->select('propertylocations.streetorbuild_en as location', DB::raw('COUNT(properties.id) as property_count'))
                    ->where('propertylocations.area_en',$request->subLocationSend["location"])->distinct()
                    ->groupBy('propertylocations.streetorbuild_en')->where(function ($query) use($request){
                        if($request->purpose > 0){ $query->where('propertydetails.purpose', $request->purpose); }
                        if($request->propertyType > 0){ $query->where('properties.propertytypes_id', $request->propertyType); }
                        if($request->bedRoomSelected > -1){ $query->where('beds', $request->bedRoomSelected); }
                        if($request->bathRoomSelected > 0){ $query->where('baths', $request->bathRoomSelected); }
                        if($request->frequencySelected > 0){ $query->where('rent_frequency', $request->frequencySelected); }
                        if($request->furnished > -1){ $query->where('propertydetails.furnishing', $request->furnished); }
               
                        if($request->priceFrom > 0 && $request->priceTo  > 0 ){ $query->whereBetween('price', [$request->priceFrom , $request->priceTo ]); }

                        if($request->priceFrom > 0 && $request->priceTo  == 0 ){ $query->where('price', '>=' ,$request->priceFrom ); }
               
                        if($request->priceFrom == 0 && $request->priceTo  > 0 ){ $query->where('price', '<=' ,$request->priceTo ); }
                      })->get();
                    foreach($buildingorstreets as $buildingorstreet){
                     $buildingorstreet->type=3;
                    }

                }
        
              
                return response()->json(["filteredproperties" =>$filteredproperties,"sublocation"=>$buildingorstreets]);
               } else  if($request->subLocationSend["type"] == 3) {
                return response()->json(["filteredproperties" =>$filteredproperties,"sublocation"=>[]]);
               }
            
           }
        
      }
      else

      if($request->popular =="newest")
      {
        $filteredproperties = Property::join('propertydetails','propertydetails.property_id','properties.id')
        ->join('propertytypes','propertytypes.id','properties.propertytypes_id')
        ->join('agents','agents.id','properties.agent_id')
        ->join('agencies','agencies.id','properties.agency_id')
        ->join('users','users.id','agents.user_id')
        ->join('propertylocations','propertylocations.property_id','properties.id')
        ->leftJoin('propertyfeatures','propertyfeatures.property_id','properties.id')
        ->leftJoin('features','features.id','propertyfeatures.feature_id')
        ->leftJoin('wishlists','wishlists.property_id','properties.id')
        ->where(function ($query) use($request){
         if($request->purpose > 0){ $query->where('propertydetails.purpose', $request->purpose); }
         if($request->propertyType > 0){ $query->where('properties.propertytypes_id', $request->propertyType); }
         if($request->bedRoomSelected > -1){ $query->where('beds', $request->bedRoomSelected); }
         if($request->bathRoomSelected > 0){ $query->where('baths', $request->bathRoomSelected); }
         if($request->frequencySelected > 0){ $query->where('rent_frequency', $request->frequencySelected); }
         if($request->furnished > -1){ $query->where('propertydetails.furnishing', $request->furnished); }

         if($request->priceFrom > 0 && $request->priceTo  > 0 ){ $query->whereBetween('price', [$request->priceFrom , $request->priceTo ]); }

         if($request->priceFrom > 0 && $request->priceTo  == 0 ){ $query->where('price', '>=' ,$request->priceFrom ); }

         if($request->priceFrom == 0 && $request->priceTo  > 0 ){ $query->where('price', '<=' ,$request->priceTo ); }


        //  if(count($request->subLocationSend) > 0){
        //  if($request->subLocationSend["type"] == 1)
        //  { 
        //      $query->where('propertylocations.emirate_en', $request->subLocationSend["location"]);
        //  }
        //          if($request->subLocationSend["type"] == 2)
        //          { 
        //              $query->where('propertylocations.area_en', $request->subLocationSend["location"]);
        //          }
        //  if($request->subLocationSend["type"] == 3)
        //  { 
        //      $query->where('propertylocations.streetorbuild_en',$request->subLocationSend["location"]);
        //  }
         
        
        // }
    
         })
         ->where(function ($query2) use($locationsArr){
            if(count($locationsArr) > 0){ 
                $query2->whereIn("propertylocations.emirate_en", $locationsArr); 
                $query2->orWhereIn("propertylocations.area_en", $locationsArr); 
                $query2->orWhereIn("propertylocations.streetorbuild_en", $locationsArr); 
            }
         })
    
         ->where(function ($query3) use($featuresArr){
            if(count($featuresArr) > 0){ 
                $query3->whereIn("features.feature_en", $featuresArr); 
                $query3->orWhereIn("features.feature_ar", $featuresArr); 
            }
         })->distinct()
    ->orderBy("created_at","desc")
        ->get(array('properties.*','propertydetails.beds','propertydetails.baths',
        'propertydetails.area','agents.name_en','agents.name_ar','agents.mobile','propertytypes.typeName_en',
        'propertytypes.typeName_ar','agencies.logo',"purpose","wishlists.status as wishlist_status","propertylocations.lat","propertylocations.lng"));
        // SUB LOCATIONS QUERY 

        if(count($request->subLocationSend) < 1){

            if(count($featuresArr) > 0){
                $emirates=  DB::table("propertylocations")
                ->join("properties","properties.id","propertylocations.property_id")
                ->join('propertydetails','propertydetails.property_id','properties.id')
                ->leftJoin('propertyfeatures','propertyfeatures.property_id','properties.id')
                ->leftJoin('features','features.id','propertyfeatures.feature_id')
    
                ->select('propertylocations.emirate_en as location', DB::raw('COUNT(properties.id) as property_count'))
                ->groupBy('propertylocations.emirate_en')
                 ->where(function ($query) use($request){
                   if($request->purpose > 0){ $query->where('propertydetails.purpose', $request->purpose); }
                   if($request->propertyType > 0){ $query->where('properties.propertytypes_id', $request->propertyType); }
                   if($request->bedRoomSelected > -1){ $query->where('beds', $request->bedRoomSelected); }
                   if($request->bathRoomSelected > 0){ $query->where('baths', $request->bathRoomSelected); }
                   if($request->frequencySelected > 0){ $query->where('rent_frequency', $request->frequencySelected); }
                   if($request->furnished > -1){ $query->where('propertydetails.furnishing', $request->furnished); }
          
                   if($request->priceFrom > 0 && $request->priceTo  > 0 ){ $query->whereBetween('price', [$request->priceFrom , $request->priceTo ]); }

                   if($request->priceFrom > 0 && $request->priceTo  == 0 ){ $query->where('price', '>=' ,$request->priceFrom ); }
          
                   if($request->priceFrom == 0 && $request->priceTo  > 0 ){ $query->where('price', '<=' ,$request->priceTo ); }
                 })
                 ->distinct()
                 ->where(function ($query3) use($featuresArr){
                    if(count($featuresArr) > 0){ 
                        $query3->whereIn("features.feature_en", $featuresArr); 
                        $query3->orWhereIn("features.feature_ar", $featuresArr); 
                    }
                 })->distinct()
                ->get();
                foreach($emirates as $emirate){
                    $emirate->type=1;
                   }
                   

            }else{

                $emirates=  DB::table("propertylocations")
                ->join("properties","properties.id","propertylocations.property_id")
                ->join('propertydetails','propertydetails.property_id','properties.id')
                
    
                ->select('propertylocations.emirate_en as location', DB::raw('COUNT(properties.id) as property_count'))
                ->groupBy('propertylocations.emirate_en')
                 ->where(function ($query) use($request){
                   if($request->purpose > 0){ $query->where('propertydetails.purpose', $request->purpose); }
                   if($request->propertyType > 0){ $query->where('properties.propertytypes_id', $request->propertyType); }
                   if($request->bedRoomSelected > -1){ $query->where('beds', $request->bedRoomSelected); }
                   if($request->bathRoomSelected > 0){ $query->where('baths', $request->bathRoomSelected); }
                   if($request->frequencySelected > 0){ $query->where('rent_frequency', $request->frequencySelected); }
                   if($request->furnished > -1){ $query->where('propertydetails.furnishing', $request->furnished); }
          
                   if($request->priceFrom > 0 && $request->priceTo  > 0 ){ $query->whereBetween('price', [$request->priceFrom , $request->priceTo ]); }

                   if($request->priceFrom > 0 && $request->priceTo  == 0 ){ $query->where('price', '>=' ,$request->priceFrom ); }
          
                   if($request->priceFrom == 0 && $request->priceTo  > 0 ){ $query->where('price', '<=' ,$request->priceTo ); }
                 })
                 ->distinct()
    
                ->get();
                foreach($emirates as $emirate){
                    $emirate->type=1;
                   }
            }

         
            
        
         
            return response()->json(["filteredproperties" =>$filteredproperties,"sublocation"=>$emirates]);
          
           }else{
        
               if($request->subLocationSend["type"] == 1)  {

                if(count($featuresArr) > 0){
                    $areas= DB::table("propertylocations")->join("properties","properties.id","propertylocations.property_id")
                    ->join('propertydetails','propertydetails.property_id','properties.id')
                    ->leftJoin('propertyfeatures','propertyfeatures.property_id','properties.id')
                    ->leftJoin('features','features.id','propertyfeatures.feature_id')
                    ->select('propertylocations.area_en as location',DB::raw('COUNT(properties.id) as property_count'))
                    ->where('propertylocations.emirate_en',$request->subLocationSend["location"])->distinct()
                    ->groupBy('propertylocations.area_en')->where(function ($query) use($request){
                        if($request->purpose > 0){ $query->where('propertydetails.purpose', $request->purpose); }
                   if($request->propertyType > 0){ $query->where('properties.propertytypes_id', $request->propertyType); }
                   if($request->bedRoomSelected > -1){ $query->where('beds', $request->bedRoomSelected); }
                   if($request->bathRoomSelected > 0){ $query->where('baths', $request->bathRoomSelected); }
                   if($request->frequencySelected > 0){ $query->where('rent_frequency', $request->frequencySelected); }
                   if($request->furnished > -1){ $query->where('propertydetails.furnishing', $request->furnished); }
          
                   if($request->priceFrom > 0 && $request->priceTo  > 0 ){ $query->whereBetween('price', [$request->priceFrom , $request->priceTo ]); }

                   if($request->priceFrom > 0 && $request->priceTo  == 0 ){ $query->where('price', '>=' ,$request->priceFrom ); }
          
                   if($request->priceFrom == 0 && $request->priceTo  > 0 ){ $query->where('price', '<=' ,$request->priceTo ); }
                      })
                      ->where(function ($query3) use($featuresArr){
                        if(count($featuresArr) > 0){ 
                            $query3->whereIn("features.feature_en", $featuresArr); 
                            $query3->orWhereIn("features.feature_ar", $featuresArr); 
                        }
                     })->distinct()
                      ->get();
    
             
                    foreach($areas as $area){
                     $area->type=2;
                    }
                }else{

                    $areas= DB::table("propertylocations")->join("properties","properties.id","propertylocations.property_id")
                    ->join('propertydetails','propertydetails.property_id','properties.id')
                    ->select('propertylocations.area_en as location',DB::raw('COUNT(properties.id) as property_count'))
                    ->where('propertylocations.emirate_en',$request->subLocationSend["location"])->distinct()
                    ->groupBy('propertylocations.area_en')->where(function ($query) use($request){
                        if($request->purpose > 0){ $query->where('propertydetails.purpose', $request->purpose); }
                   if($request->propertyType > 0){ $query->where('properties.propertytypes_id', $request->propertyType); }
                   if($request->bedRoomSelected > -1){ $query->where('beds', $request->bedRoomSelected); }
                   if($request->bathRoomSelected > 0){ $query->where('baths', $request->bathRoomSelected); }
                   if($request->frequencySelected > 0){ $query->where('rent_frequency', $request->frequencySelected); }
                   if($request->furnished > -1){ $query->where('propertydetails.furnishing', $request->furnished); }
          
                   if($request->priceFrom > 0 && $request->priceTo  > 0 ){ $query->whereBetween('price', [$request->priceFrom , $request->priceTo ]); }

                   if($request->priceFrom > 0 && $request->priceTo  == 0 ){ $query->where('price', '>=' ,$request->priceFrom ); }
          
                   if($request->priceFrom == 0 && $request->priceTo  > 0 ){ $query->where('price', '<=' ,$request->priceTo ); }
                      })->get();
    
             
                    foreach($areas as $area){
                     $area->type=2;
                    }

                }
        
         
                return response()->json(["filteredproperties" =>$filteredproperties,"sublocation"=>$areas]);
        
               } else if($request->subLocationSend["type"] == 2) {

                if(count($featuresArr) > 0){
                    $buildingorstreets= DB::table("propertylocations")->join("properties","properties.id","propertylocations.property_id")
                    ->join('propertydetails','propertydetails.property_id','properties.id')
                    ->leftJoin('propertyfeatures','propertyfeatures.property_id','properties.id')
                    ->leftJoin('features','features.id','propertyfeatures.feature_id')
                     ->select('propertylocations.streetorbuild_en as location', DB::raw('COUNT(properties.id) as property_count'))
                    ->where('propertylocations.area_en',$request->subLocationSend["location"])->distinct()
                    ->groupBy('propertylocations.streetorbuild_en')->where(function ($query) use($request){
                        if($request->purpose > 0){ $query->where('propertydetails.purpose', $request->purpose); }
                        if($request->propertyType > 0){ $query->where('properties.propertytypes_id', $request->propertyType); }
                        if($request->bedRoomSelected > -1){ $query->where('beds', $request->bedRoomSelected); }
                        if($request->bathRoomSelected > 0){ $query->where('baths', $request->bathRoomSelected); }
                        if($request->frequencySelected > 0){ $query->where('rent_frequency', $request->frequencySelected); }
                        if($request->furnished > -1){ $query->where('propertydetails.furnishing', $request->furnished); }
               
                        if($request->priceFrom > 0 && $request->priceTo  > 0 ){ $query->whereBetween('price', [$request->priceFrom , $request->priceTo ]); }

                        if($request->priceFrom > 0 && $request->priceTo  == 0 ){ $query->where('price', '>=' ,$request->priceFrom ); }
               
                        if($request->priceFrom == 0 && $request->priceTo  > 0 ){ $query->where('price', '<=' ,$request->priceTo ); }
                      })
                      ->where(function ($query3) use($featuresArr){
                        if(count($featuresArr) > 0){ 
                            $query3->whereIn("features.feature_en", $featuresArr); 
                            $query3->orWhereIn("features.feature_ar", $featuresArr); 
                        }
                     })->distinct()
                     ->get();
                    foreach($buildingorstreets as $buildingorstreet){
                     $buildingorstreet->type=3;
                    }
                }
                else{
                    $buildingorstreets= DB::table("propertylocations")->join("properties","properties.id","propertylocations.property_id")
                    ->join('propertydetails','propertydetails.property_id','properties.id')
                     ->select('propertylocations.streetorbuild_en as location', DB::raw('COUNT(properties.id) as property_count'))
                    ->where('propertylocations.area_en',$request->subLocationSend["location"])->distinct()
                    ->groupBy('propertylocations.streetorbuild_en')->where(function ($query) use($request){
                        if($request->purpose > 0){ $query->where('propertydetails.purpose', $request->purpose); }
                        if($request->propertyType > 0){ $query->where('properties.propertytypes_id', $request->propertyType); }
                        if($request->bedRoomSelected > -1){ $query->where('beds', $request->bedRoomSelected); }
                        if($request->bathRoomSelected > 0){ $query->where('baths', $request->bathRoomSelected); }
                        if($request->frequencySelected > 0){ $query->where('rent_frequency', $request->frequencySelected); }
                        if($request->furnished > -1){ $query->where('propertydetails.furnishing', $request->furnished); }
               
                        if($request->priceFrom > 0 && $request->priceTo  > 0 ){ $query->whereBetween('price', [$request->priceFrom , $request->priceTo ]); }

                        if($request->priceFrom > 0 && $request->priceTo  == 0 ){ $query->where('price', '>=' ,$request->priceFrom ); }
               
                        if($request->priceFrom == 0 && $request->priceTo  > 0 ){ $query->where('price', '<=' ,$request->priceTo ); }
                      })->get();
                    foreach($buildingorstreets as $buildingorstreet){
                     $buildingorstreet->type=3;
                    }

                }
        
              
                return response()->json(["filteredproperties" =>$filteredproperties,"sublocation"=>$buildingorstreets]);
               } else  if($request->subLocationSend["type"] == 3) {
                return response()->json(["filteredproperties" =>$filteredproperties,"sublocation"=>[]]);
               }
            
           }
               
      }
      else

      if($request->popular =="lowest")
      {
        $filteredproperties = Property::join('propertydetails','propertydetails.property_id','properties.id')
        ->join('propertytypes','propertytypes.id','properties.propertytypes_id')
        ->join('agents','agents.id','properties.agent_id')
        ->join('agencies','agencies.id','properties.agency_id')
        ->join('users','users.id','agents.user_id')
        ->join('propertylocations','propertylocations.property_id','properties.id')
        ->leftJoin('propertyfeatures','propertyfeatures.property_id','properties.id')
        ->leftJoin('features','features.id','propertyfeatures.feature_id')
        ->leftJoin('wishlists','wishlists.property_id','properties.id')
        ->where(function ($query) use($request){
         if($request->purpose > 0){ $query->where('propertydetails.purpose', $request->purpose); }
         if($request->propertyType > 0){ $query->where('properties.propertytypes_id', $request->propertyType); }
         if($request->bedRoomSelected > -1){ $query->where('beds', $request->bedRoomSelected); }
         if($request->bathRoomSelected > 0){ $query->where('baths', $request->bathRoomSelected); }
         if($request->frequencySelected > 0){ $query->where('rent_frequency', $request->frequencySelected); }
         if($request->furnished > -1){ $query->where('propertydetails.furnishing', $request->furnished); }

         if($request->priceFrom > 0 && $request->priceTo  > 0 ){ $query->whereBetween('price', [$request->priceFrom , $request->priceTo ]); }

         if($request->priceFrom > 0 && $request->priceTo  == 0 ){ $query->where('price', '>=' ,$request->priceFrom ); }

         if($request->priceFrom == 0 && $request->priceTo  > 0 ){ $query->where('price', '<=' ,$request->priceTo ); }


        //  if(count($request->subLocationSend) > 0){
        //  if($request->subLocationSend["type"] == 1)
        //  { 
        //      $query->where('propertylocations.emirate_en', $request->subLocationSend["location"]);
        //  }
        //          if($request->subLocationSend["type"] == 2)
        //          { 
        //              $query->where('propertylocations.area_en', $request->subLocationSend["location"]);
        //          }
        //  if($request->subLocationSend["type"] == 3)
        //  { 
        //      $query->where('propertylocations.streetorbuild_en',$request->subLocationSend["location"]);
        //  }
         
        
        // }
    
         })
         ->where(function ($query2) use($locationsArr){
            if(count($locationsArr) > 0){ 
                $query2->whereIn("propertylocations.emirate_en", $locationsArr); 
                $query2->orWhereIn("propertylocations.area_en", $locationsArr); 
                $query2->orWhereIn("propertylocations.streetorbuild_en", $locationsArr); 
            }
         })
    
         ->where(function ($query3) use($featuresArr){
            if(count($featuresArr) > 0){ 
                $query3->whereIn("features.feature_en", $featuresArr); 
                $query3->orWhereIn("features.feature_ar", $featuresArr); 
            }
         })->distinct()
    ->orderBy("price","asc")
        ->get(array('properties.*','propertydetails.beds','propertydetails.baths',
        'propertydetails.area','agents.name_en','agents.name_ar','agents.mobile','propertytypes.typeName_en',
        'propertytypes.typeName_ar','agencies.logo',"purpose","wishlists.status as wishlist_status","propertylocations.lat","propertylocations.lng"));
        // SUB LOCATIONS QUERY 

        if(count($request->subLocationSend) < 1){

            if(count($featuresArr) > 0){
                $emirates=  DB::table("propertylocations")
                ->join("properties","properties.id","propertylocations.property_id")
                ->join('propertydetails','propertydetails.property_id','properties.id')
                ->leftJoin('propertyfeatures','propertyfeatures.property_id','properties.id')
                ->leftJoin('features','features.id','propertyfeatures.feature_id')
    
                ->select('propertylocations.emirate_en as location', DB::raw('COUNT(properties.id) as property_count'))
                ->groupBy('propertylocations.emirate_en')
                 ->where(function ($query) use($request){
                   if($request->purpose > 0){ $query->where('propertydetails.purpose', $request->purpose); }
                   if($request->propertyType > 0){ $query->where('properties.propertytypes_id', $request->propertyType); }
                   if($request->bedRoomSelected > -1){ $query->where('beds', $request->bedRoomSelected); }
                   if($request->bathRoomSelected > 0){ $query->where('baths', $request->bathRoomSelected); }
                   if($request->frequencySelected > 0){ $query->where('rent_frequency', $request->frequencySelected); }
                   if($request->furnished > -1){ $query->where('propertydetails.furnishing', $request->furnished); }
          
                   if($request->priceFrom > 0 && $request->priceTo  > 0 ){ $query->whereBetween('price', [$request->priceFrom , $request->priceTo ]); }

                   if($request->priceFrom > 0 && $request->priceTo  == 0 ){ $query->where('price', '>=' ,$request->priceFrom ); }
          
                   if($request->priceFrom == 0 && $request->priceTo  > 0 ){ $query->where('price', '<=' ,$request->priceTo ); }
                 })
                 ->distinct()
                 ->where(function ($query3) use($featuresArr){
                    if(count($featuresArr) > 0){ 
                        $query3->whereIn("features.feature_en", $featuresArr); 
                        $query3->orWhereIn("features.feature_ar", $featuresArr); 
                    }
                 })->distinct()
                ->get();
                foreach($emirates as $emirate){
                    $emirate->type=1;
                   }
                   

            }else{

                $emirates=  DB::table("propertylocations")
                ->join("properties","properties.id","propertylocations.property_id")
                ->join('propertydetails','propertydetails.property_id','properties.id')
                
    
                ->select('propertylocations.emirate_en as location', DB::raw('COUNT(properties.id) as property_count'))
                ->groupBy('propertylocations.emirate_en')
                 ->where(function ($query) use($request){
                   if($request->purpose > 0){ $query->where('propertydetails.purpose', $request->purpose); }
                   if($request->propertyType > 0){ $query->where('properties.propertytypes_id', $request->propertyType); }
                   if($request->bedRoomSelected > -1){ $query->where('beds', $request->bedRoomSelected); }
                   if($request->bathRoomSelected > 0){ $query->where('baths', $request->bathRoomSelected); }
                   if($request->frequencySelected > 0){ $query->where('rent_frequency', $request->frequencySelected); }
                   if($request->furnished > -1){ $query->where('propertydetails.furnishing', $request->furnished); }
          
                   if($request->priceFrom > 0 && $request->priceTo  > 0 ){ $query->whereBetween('price', [$request->priceFrom , $request->priceTo ]); }

                   if($request->priceFrom > 0 && $request->priceTo  == 0 ){ $query->where('price', '>=' ,$request->priceFrom ); }
          
                   if($request->priceFrom == 0 && $request->priceTo  > 0 ){ $query->where('price', '<=' ,$request->priceTo ); }
                 })
                 ->distinct()
    
                ->get();
                foreach($emirates as $emirate){
                    $emirate->type=1;
                   }
            }

         
            
        
         
            return response()->json(["filteredproperties" =>$filteredproperties,"sublocation"=>$emirates]);
          
           }else{
        
               if($request->subLocationSend["type"] == 1)  {

                if(count($featuresArr) > 0){
                    $areas= DB::table("propertylocations")->join("properties","properties.id","propertylocations.property_id")
                    ->join('propertydetails','propertydetails.property_id','properties.id')
                    ->leftJoin('propertyfeatures','propertyfeatures.property_id','properties.id')
                    ->leftJoin('features','features.id','propertyfeatures.feature_id')
                    ->select('propertylocations.area_en as location',DB::raw('COUNT(properties.id) as property_count'))
                    ->where('propertylocations.emirate_en',$request->subLocationSend["location"])->distinct()
                    ->groupBy('propertylocations.area_en')->where(function ($query) use($request){
                        if($request->purpose > 0){ $query->where('propertydetails.purpose', $request->purpose); }
                   if($request->propertyType > 0){ $query->where('properties.propertytypes_id', $request->propertyType); }
                   if($request->bedRoomSelected > -1){ $query->where('beds', $request->bedRoomSelected); }
                   if($request->bathRoomSelected > 0){ $query->where('baths', $request->bathRoomSelected); }
                   if($request->frequencySelected > 0){ $query->where('rent_frequency', $request->frequencySelected); }
                   if($request->furnished > -1){ $query->where('propertydetails.furnishing', $request->furnished); }
          
                   if($request->priceFrom > 0 && $request->priceTo  > 0 ){ $query->whereBetween('price', [$request->priceFrom , $request->priceTo ]); }

                   if($request->priceFrom > 0 && $request->priceTo  == 0 ){ $query->where('price', '>=' ,$request->priceFrom ); }
          
                   if($request->priceFrom == 0 && $request->priceTo  > 0 ){ $query->where('price', '<=' ,$request->priceTo ); }
                      })
                      ->where(function ($query3) use($featuresArr){
                        if(count($featuresArr) > 0){ 
                            $query3->whereIn("features.feature_en", $featuresArr); 
                            $query3->orWhereIn("features.feature_ar", $featuresArr); 
                        }
                     })->distinct()
                      ->get();
    
             
                    foreach($areas as $area){
                     $area->type=2;
                    }
                }else{

                    $areas= DB::table("propertylocations")->join("properties","properties.id","propertylocations.property_id")
                    ->join('propertydetails','propertydetails.property_id','properties.id')
                    ->select('propertylocations.area_en as location',DB::raw('COUNT(properties.id) as property_count'))
                    ->where('propertylocations.emirate_en',$request->subLocationSend["location"])->distinct()
                    ->groupBy('propertylocations.area_en')->where(function ($query) use($request){
                        if($request->purpose > 0){ $query->where('propertydetails.purpose', $request->purpose); }
                   if($request->propertyType > 0){ $query->where('properties.propertytypes_id', $request->propertyType); }
                   if($request->bedRoomSelected > -1){ $query->where('beds', $request->bedRoomSelected); }
                   if($request->bathRoomSelected > 0){ $query->where('baths', $request->bathRoomSelected); }
                   if($request->frequencySelected > 0){ $query->where('rent_frequency', $request->frequencySelected); }
                   if($request->furnished > -1){ $query->where('propertydetails.furnishing', $request->furnished); }
          
                   if($request->priceFrom > 0 && $request->priceTo  > 0 ){ $query->whereBetween('price', [$request->priceFrom , $request->priceTo ]); }

                   if($request->priceFrom > 0 && $request->priceTo  == 0 ){ $query->where('price', '>=' ,$request->priceFrom ); }
          
                   if($request->priceFrom == 0 && $request->priceTo  > 0 ){ $query->where('price', '<=' ,$request->priceTo ); }
                      })->get();
    
             
                    foreach($areas as $area){
                     $area->type=2;
                    }

                }
        
         
                return response()->json(["filteredproperties" =>$filteredproperties,"sublocation"=>$areas]);
        
               } else if($request->subLocationSend["type"] == 2) {

                if(count($featuresArr) > 0){
                    $buildingorstreets= DB::table("propertylocations")->join("properties","properties.id","propertylocations.property_id")
                    ->join('propertydetails','propertydetails.property_id','properties.id')
                    ->leftJoin('propertyfeatures','propertyfeatures.property_id','properties.id')
                    ->leftJoin('features','features.id','propertyfeatures.feature_id')
                     ->select('propertylocations.streetorbuild_en as location', DB::raw('COUNT(properties.id) as property_count'))
                    ->where('propertylocations.area_en',$request->subLocationSend["location"])->distinct()
                    ->groupBy('propertylocations.streetorbuild_en')->where(function ($query) use($request){
                        if($request->purpose > 0){ $query->where('propertydetails.purpose', $request->purpose); }
                        if($request->propertyType > 0){ $query->where('properties.propertytypes_id', $request->propertyType); }
                        if($request->bedRoomSelected > -1){ $query->where('beds', $request->bedRoomSelected); }
                        if($request->bathRoomSelected > 0){ $query->where('baths', $request->bathRoomSelected); }
                        if($request->frequencySelected > 0){ $query->where('rent_frequency', $request->frequencySelected); }
                        if($request->furnished > -1){ $query->where('propertydetails.furnishing', $request->furnished); }
               
                        if($request->priceFrom > 0 && $request->priceTo  > 0 ){ $query->whereBetween('price', [$request->priceFrom , $request->priceTo ]); }

                        if($request->priceFrom > 0 && $request->priceTo  == 0 ){ $query->where('price', '>=' ,$request->priceFrom ); }
               
                        if($request->priceFrom == 0 && $request->priceTo  > 0 ){ $query->where('price', '<=' ,$request->priceTo ); }
                      })
                      ->where(function ($query3) use($featuresArr){
                        if(count($featuresArr) > 0){ 
                            $query3->whereIn("features.feature_en", $featuresArr); 
                            $query3->orWhereIn("features.feature_ar", $featuresArr); 
                        }
                     })->distinct()
                     ->get();
                    foreach($buildingorstreets as $buildingorstreet){
                     $buildingorstreet->type=3;
                    }
                }
                else{
                    $buildingorstreets= DB::table("propertylocations")->join("properties","properties.id","propertylocations.property_id")
                    ->join('propertydetails','propertydetails.property_id','properties.id')
                     ->select('propertylocations.streetorbuild_en as location', DB::raw('COUNT(properties.id) as property_count'))
                    ->where('propertylocations.area_en',$request->subLocationSend["location"])->distinct()
                    ->groupBy('propertylocations.streetorbuild_en')->where(function ($query) use($request){
                        if($request->purpose > 0){ $query->where('propertydetails.purpose', $request->purpose); }
                        if($request->propertyType > 0){ $query->where('properties.propertytypes_id', $request->propertyType); }
                        if($request->bedRoomSelected > -1){ $query->where('beds', $request->bedRoomSelected); }
                        if($request->bathRoomSelected > 0){ $query->where('baths', $request->bathRoomSelected); }
                        if($request->frequencySelected > 0){ $query->where('rent_frequency', $request->frequencySelected); }
                        if($request->furnished > -1){ $query->where('propertydetails.furnishing', $request->furnished); }
               
                        if($request->priceFrom > 0 && $request->priceTo  > 0 ){ $query->whereBetween('price', [$request->priceFrom , $request->priceTo ]); }

                        if($request->priceFrom > 0 && $request->priceTo  == 0 ){ $query->where('price', '>=' ,$request->priceFrom ); }
               
                        if($request->priceFrom == 0 && $request->priceTo  > 0 ){ $query->where('price', '<=' ,$request->priceTo ); }
                      })->get();
                    foreach($buildingorstreets as $buildingorstreet){
                     $buildingorstreet->type=3;
                    }

                }
        
              
                return response()->json(["filteredproperties" =>$filteredproperties,"sublocation"=>$buildingorstreets]);
               } else  if($request->subLocationSend["type"] == 3) {
                return response()->json(["filteredproperties" =>$filteredproperties,"sublocation"=>[]]);
               }
            
           }
               
      }
      else

      if($request->popular =="highest")
      {
        $filteredproperties = Property::join('propertydetails','propertydetails.property_id','properties.id')
        ->join('propertytypes','propertytypes.id','properties.propertytypes_id')
        ->join('agents','agents.id','properties.agent_id')
        ->join('agencies','agencies.id','properties.agency_id')
        ->join('users','users.id','agents.user_id')
        ->join('propertylocations','propertylocations.property_id','properties.id')
        ->leftJoin('propertyfeatures','propertyfeatures.property_id','properties.id')
        ->leftJoin('features','features.id','propertyfeatures.feature_id')
        ->leftJoin('wishlists','wishlists.property_id','properties.id')
        ->where(function ($query) use($request){
         if($request->purpose > 0){ $query->where('propertydetails.purpose', $request->purpose); }
         if($request->propertyType > 0){ $query->where('properties.propertytypes_id', $request->propertyType); }
         if($request->bedRoomSelected > -1){ $query->where('beds', $request->bedRoomSelected); }
         if($request->bathRoomSelected > 0){ $query->where('baths', $request->bathRoomSelected); }
         if($request->frequencySelected > 0){ $query->where('rent_frequency', $request->frequencySelected); }
         if($request->furnished > -1){ $query->where('propertydetails.furnishing', $request->furnished); }

         if($request->priceFrom > 0 && $request->priceTo  > 0 ){ $query->whereBetween('price', [$request->priceFrom , $request->priceTo ]); }

         if($request->priceFrom > 0 && $request->priceTo  == 0 ){ $query->where('price', '>=' ,$request->priceFrom ); }

         if($request->priceFrom == 0 && $request->priceTo  > 0 ){ $query->where('price', '<=' ,$request->priceTo ); }


        //  if(count($request->subLocationSend) > 0){
        //  if($request->subLocationSend["type"] == 1)
        //  { 
        //      $query->where('propertylocations.emirate_en', $request->subLocationSend["location"]);
        //  }
        //          if($request->subLocationSend["type"] == 2)
        //          { 
        //              $query->where('propertylocations.area_en', $request->subLocationSend["location"]);
        //          }
        //  if($request->subLocationSend["type"] == 3)
        //  { 
        //      $query->where('propertylocations.streetorbuild_en',$request->subLocationSend["location"]);
        //  }
         
        
        // }
    
         })
         ->where(function ($query2) use($locationsArr){
            if(count($locationsArr) > 0){ 
                $query2->whereIn("propertylocations.emirate_en", $locationsArr); 
                $query2->orWhereIn("propertylocations.area_en", $locationsArr); 
                $query2->orWhereIn("propertylocations.streetorbuild_en", $locationsArr); 
            }
         })
    
         ->where(function ($query3) use($featuresArr){
            if(count($featuresArr) > 0){ 
                $query3->whereIn("features.feature_en", $featuresArr); 
                $query3->orWhereIn("features.feature_ar", $featuresArr); 
            }
         })->distinct()
        ->orderBy("price","desc")
        ->get(array('properties.*','propertydetails.beds','propertydetails.baths',
        'propertydetails.area','agents.name_en','agents.name_ar','agents.mobile','propertytypes.typeName_en',
        'propertytypes.typeName_ar','agencies.logo',"purpose","wishlists.status as wishlist_status","propertylocations.lat","propertylocations.lng"));
        // SUB LOCATIONS QUERY 

        if(count($request->subLocationSend) < 1){

            if(count($featuresArr) > 0){
                $emirates=  DB::table("propertylocations")
                ->join("properties","properties.id","propertylocations.property_id")
                ->join('propertydetails','propertydetails.property_id','properties.id')
                ->leftJoin('propertyfeatures','propertyfeatures.property_id','properties.id')
                ->leftJoin('features','features.id','propertyfeatures.feature_id')
    
                ->select('propertylocations.emirate_en as location', DB::raw('COUNT(properties.id) as property_count'))
                ->groupBy('propertylocations.emirate_en')
                 ->where(function ($query) use($request){
                   if($request->purpose > 0){ $query->where('propertydetails.purpose', $request->purpose); }
                   if($request->propertyType > 0){ $query->where('properties.propertytypes_id', $request->propertyType); }
                   if($request->bedRoomSelected > -1){ $query->where('beds', $request->bedRoomSelected); }
                   if($request->bathRoomSelected > 0){ $query->where('baths', $request->bathRoomSelected); }
                   if($request->frequencySelected > 0){ $query->where('rent_frequency', $request->frequencySelected); }
                   if($request->furnished > -1){ $query->where('propertydetails.furnishing', $request->furnished); }
          
                   if($request->priceFrom > 0 && $request->priceTo  > 0 ){ $query->whereBetween('price', [$request->priceFrom , $request->priceTo ]); }

                   if($request->priceFrom > 0 && $request->priceTo  == 0 ){ $query->where('price', '>=' ,$request->priceFrom ); }
          
                   if($request->priceFrom == 0 && $request->priceTo  > 0 ){ $query->where('price', '<=' ,$request->priceTo ); }
                 })
                 ->distinct()
                 ->where(function ($query3) use($featuresArr){
                    if(count($featuresArr) > 0){ 
                        $query3->whereIn("features.feature_en", $featuresArr); 
                        $query3->orWhereIn("features.feature_ar", $featuresArr); 
                    }
                 })->distinct()
                ->get();
                foreach($emirates as $emirate){
                    $emirate->type=1;
                   }
                   

            }else{

                $emirates=  DB::table("propertylocations")
                ->join("properties","properties.id","propertylocations.property_id")
                ->join('propertydetails','propertydetails.property_id','properties.id')
                
    
                ->select('propertylocations.emirate_en as location', DB::raw('COUNT(properties.id) as property_count'))
                ->groupBy('propertylocations.emirate_en')
                 ->where(function ($query) use($request){
                   if($request->purpose > 0){ $query->where('propertydetails.purpose', $request->purpose); }
                   if($request->propertyType > 0){ $query->where('properties.propertytypes_id', $request->propertyType); }
                   if($request->bedRoomSelected > -1){ $query->where('beds', $request->bedRoomSelected); }
                   if($request->bathRoomSelected > 0){ $query->where('baths', $request->bathRoomSelected); }
                   if($request->frequencySelected > 0){ $query->where('rent_frequency', $request->frequencySelected); }
                   if($request->furnished > -1){ $query->where('propertydetails.furnishing', $request->furnished); }
          
                   if($request->priceFrom > 0 && $request->priceTo  > 0 ){ $query->whereBetween('price', [$request->priceFrom , $request->priceTo ]); }

                   if($request->priceFrom > 0 && $request->priceTo  == 0 ){ $query->where('price', '>=' ,$request->priceFrom ); }
          
                   if($request->priceFrom == 0 && $request->priceTo  > 0 ){ $query->where('price', '<=' ,$request->priceTo ); }
                 })
                 ->distinct()
    
                ->get();
                foreach($emirates as $emirate){
                    $emirate->type=1;
                   }
            }

         
            
        
         
            return response()->json(["filteredproperties" =>$filteredproperties,"sublocation"=>$emirates]);
          
           }else{
        
               if($request->subLocationSend["type"] == 1)  {

                if(count($featuresArr) > 0){
                    $areas= DB::table("propertylocations")->join("properties","properties.id","propertylocations.property_id")
                    ->join('propertydetails','propertydetails.property_id','properties.id')
                    ->leftJoin('propertyfeatures','propertyfeatures.property_id','properties.id')
                    ->leftJoin('features','features.id','propertyfeatures.feature_id')
                    ->select('propertylocations.area_en as location',DB::raw('COUNT(properties.id) as property_count'))
                    ->where('propertylocations.emirate_en',$request->subLocationSend["location"])->distinct()
                    ->groupBy('propertylocations.area_en')->where(function ($query) use($request){
                        if($request->purpose > 0){ $query->where('propertydetails.purpose', $request->purpose); }
                   if($request->propertyType > 0){ $query->where('properties.propertytypes_id', $request->propertyType); }
                   if($request->bedRoomSelected > -1){ $query->where('beds', $request->bedRoomSelected); }
                   if($request->bathRoomSelected > 0){ $query->where('baths', $request->bathRoomSelected); }
                   if($request->frequencySelected > 0){ $query->where('rent_frequency', $request->frequencySelected); }
                   if($request->furnished > -1){ $query->where('propertydetails.furnishing', $request->furnished); }
          
                   if($request->priceFrom > 0 && $request->priceTo  > 0 ){ $query->whereBetween('price', [$request->priceFrom , $request->priceTo ]); }

                   if($request->priceFrom > 0 && $request->priceTo  == 0 ){ $query->where('price', '>=' ,$request->priceFrom ); }
          
                   if($request->priceFrom == 0 && $request->priceTo  > 0 ){ $query->where('price', '<=' ,$request->priceTo ); }
                      })
                      ->where(function ($query3) use($featuresArr){
                        if(count($featuresArr) > 0){ 
                            $query3->whereIn("features.feature_en", $featuresArr); 
                            $query3->orWhereIn("features.feature_ar", $featuresArr); 
                        }
                     })->distinct()
                      ->get();
    
             
                    foreach($areas as $area){
                     $area->type=2;
                    }
                }else{

                    $areas= DB::table("propertylocations")->join("properties","properties.id","propertylocations.property_id")
                    ->join('propertydetails','propertydetails.property_id','properties.id')
                    ->select('propertylocations.area_en as location',DB::raw('COUNT(properties.id) as property_count'))
                    ->where('propertylocations.emirate_en',$request->subLocationSend["location"])->distinct()
                    ->groupBy('propertylocations.area_en')->where(function ($query) use($request){
                        if($request->purpose > 0){ $query->where('propertydetails.purpose', $request->purpose); }
                   if($request->propertyType > 0){ $query->where('properties.propertytypes_id', $request->propertyType); }
                   if($request->bedRoomSelected > -1){ $query->where('beds', $request->bedRoomSelected); }
                   if($request->bathRoomSelected > 0){ $query->where('baths', $request->bathRoomSelected); }
                   if($request->frequencySelected > 0){ $query->where('rent_frequency', $request->frequencySelected); }
                   if($request->furnished > -1){ $query->where('propertydetails.furnishing', $request->furnished); }
          
                   if($request->priceFrom > 0 && $request->priceTo  > 0 ){ $query->whereBetween('price', [$request->priceFrom , $request->priceTo ]); }

                   if($request->priceFrom > 0 && $request->priceTo  == 0 ){ $query->where('price', '>=' ,$request->priceFrom ); }
          
                   if($request->priceFrom == 0 && $request->priceTo  > 0 ){ $query->where('price', '<=' ,$request->priceTo ); }
                      })->get();
    
             
                    foreach($areas as $area){
                     $area->type=2;
                    }

                }
        
         
                return response()->json(["filteredproperties" =>$filteredproperties,"sublocation"=>$areas]);
        
               } else if($request->subLocationSend["type"] == 2) {

                if(count($featuresArr) > 0){
                    $buildingorstreets= DB::table("propertylocations")->join("properties","properties.id","propertylocations.property_id")
                    ->join('propertydetails','propertydetails.property_id','properties.id')
                    ->leftJoin('propertyfeatures','propertyfeatures.property_id','properties.id')
                    ->leftJoin('features','features.id','propertyfeatures.feature_id')
                     ->select('propertylocations.streetorbuild_en as location', DB::raw('COUNT(properties.id) as property_count'))
                    ->where('propertylocations.area_en',$request->subLocationSend["location"])->distinct()
                    ->groupBy('propertylocations.streetorbuild_en')->where(function ($query) use($request){
                        if($request->purpose > 0){ $query->where('propertydetails.purpose', $request->purpose); }
                        if($request->propertyType > 0){ $query->where('properties.propertytypes_id', $request->propertyType); }
                        if($request->bedRoomSelected > -1){ $query->where('beds', $request->bedRoomSelected); }
                        if($request->bathRoomSelected > 0){ $query->where('baths', $request->bathRoomSelected); }
                        if($request->frequencySelected > 0){ $query->where('rent_frequency', $request->frequencySelected); }
                        if($request->furnished > -1){ $query->where('propertydetails.furnishing', $request->furnished); }
               
                        if($request->priceFrom > 0 && $request->priceTo  > 0 ){ $query->whereBetween('price', [$request->priceFrom , $request->priceTo ]); }

                        if($request->priceFrom > 0 && $request->priceTo  == 0 ){ $query->where('price', '>=' ,$request->priceFrom ); }
               
                        if($request->priceFrom == 0 && $request->priceTo  > 0 ){ $query->where('price', '<=' ,$request->priceTo ); }
                      })
                      ->where(function ($query3) use($featuresArr){
                        if(count($featuresArr) > 0){ 
                            $query3->whereIn("features.feature_en", $featuresArr); 
                            $query3->orWhereIn("features.feature_ar", $featuresArr); 
                        }
                     })->distinct()
                     ->get();
                    foreach($buildingorstreets as $buildingorstreet){
                     $buildingorstreet->type=3;
                    }
                }
                else{
                    $buildingorstreets= DB::table("propertylocations")->join("properties","properties.id","propertylocations.property_id")
                    ->join('propertydetails','propertydetails.property_id','properties.id')
                     ->select('propertylocations.streetorbuild_en as location', DB::raw('COUNT(properties.id) as property_count'))
                    ->where('propertylocations.area_en',$request->subLocationSend["location"])->distinct()
                    ->groupBy('propertylocations.streetorbuild_en')->where(function ($query) use($request){
                        if($request->purpose > 0){ $query->where('propertydetails.purpose', $request->purpose); }
                        if($request->propertyType > 0){ $query->where('properties.propertytypes_id', $request->propertyType); }
                        if($request->bedRoomSelected > -1){ $query->where('beds', $request->bedRoomSelected); }
                        if($request->bathRoomSelected > 0){ $query->where('baths', $request->bathRoomSelected); }
                        if($request->frequencySelected > 0){ $query->where('rent_frequency', $request->frequencySelected); }
                        if($request->furnished > -1){ $query->where('propertydetails.furnishing', $request->furnished); }
               
                        if($request->priceFrom > 0 && $request->priceTo  > 0 ){ $query->whereBetween('price', [$request->priceFrom , $request->priceTo ]); }

                   if($request->priceFrom > 0 && $request->priceTo  == 0 ){ $query->where('price', '>=' ,$request->priceFrom ); }
          
                   if($request->priceFrom == 0 && $request->priceTo  > 0 ){ $query->where('price', '<=' ,$request->priceTo ); }
                      })->get();
                    foreach($buildingorstreets as $buildingorstreet){
                     $buildingorstreet->type=3;
                    }

                }
        
              
                return response()->json(["filteredproperties" =>$filteredproperties,"sublocation"=>$buildingorstreets]);
               } else  if($request->subLocationSend["type"] == 3) {
                return response()->json(["filteredproperties" =>$filteredproperties,"sublocation"=>[]]);
               }
            
           }
               
      }
      
   
     }
}
