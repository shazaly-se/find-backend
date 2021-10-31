<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Propertydetail;
use App\Models\Package;
use App\Models\Agency;
use DB;


class ManageListingController extends Controller
{
    public function index()
    {
      $user = auth()->user();

      $agencypackage= Agency::where('user_id','=',$user->id)->first();
     // return $agencypackage;

     // basic package 
     $basic = DB::table('properties')
     ->select(DB::raw('count(package_type) as count'))
     ->where('package_type', '=', 1)
     ->where('agency_id','=',$agencypackage->id)
     ->groupBy('package_type')
     ->get();

     $basic_count =0;
     foreach($basic as $basic){
       $basic_count +=$basic->count;
     }

     $basic_available= $agencypackage->basic - $basic_count;


     //return $basic_available;
// featured package
     $featured = DB::table('properties')
     ->select(DB::raw('count(package_type) as count'))
     ->where('package_type', '=', 2)
      ->where('agency_id','=',$agencypackage->id)
     ->groupBy('package_type')
     ->get();

     $featured_count = 0;
     foreach($featured as $featured){
       $featured_count +=$featured->count;
     }

     //return $featured_count;

        // available packagefor featured
  
    $featured_available= $agencypackage->featured - $featured_count;

      //return $featured_available;

      $premium = DB::table('properties')
      ->select(DB::raw('count(package_type) as count'))
      ->where('package_type', '=', 3)
      ->where('agency_id','=',$agencypackage->id)
      ->groupBy('package_type')
      ->get();

      $premium_count =0;
      foreach($premium as $premium){
        $premium_count +=$premium->count;
      }

      $active = DB::table('properties')
      ->select(DB::raw('count(status_id) as count'))
      ->where('status_id', '=', 1)
      ->where('agency_id','=',$agencypackage->id)
      ->groupBy('status_id')
      ->get();

      $active_count =0;
      foreach($active as $active){
        $active_count +=$active->count;
      }

      $inactive = DB::table('properties')
      ->select(DB::raw('count(status_id) as count'))
      ->where('status_id', '=', 2)
      ->where('agency_id','=',$agencypackage->id)
      ->groupBy('status_id')
      ->get();

      $inactive_count =0;
      foreach($inactive as $inactive){
        $inactive_count +=$inactive->count;
      }

      $rent = DB::table('properties')->join("propertydetails","propertydetails.property_id","=","properties.id")
      ->select(DB::raw('count(purpose) as count'))
      ->where('purpose', '=', 1)
      ->where('agency_id','=',$agencypackage->id)
      ->groupBy('purpose')
      ->get();

      $rent_count =0;
      foreach($rent as $rent){
        $rent_count +=$rent->count;
      }

      $buy = DB::table('properties')->join("propertydetails","propertydetails.property_id","=","properties.id")
      ->select(DB::raw('count(purpose) as count'))
      ->where('purpose', '=', 2)
      ->where('agency_id','=',$agencypackage->id)
      ->groupBy('purpose')
      ->get();

      $buy_count =0;
      foreach($buy as $buy){
        $buy_count +=$buy->count;
      }

      $draft = DB::table('properties')->join("propertydetails","propertydetails.property_id","=","properties.id")
      ->select(DB::raw('count(status_id) as count'))
      ->where('status_id', '=', 3)
      ->where('agency_id','=',$agencypackage->id)
      ->groupBy('status_id')
      ->get();

      $draft_count =0;
      foreach($draft as $draft){
        $draft_count +=$draft->count;
      }

      $all = DB::table('properties')->join("propertydetails","propertydetails.property_id","=","properties.id")
      ->select(DB::raw('count(properties.id) as count'))
      ->where('agency_id','=',$agencypackage->id)
      ->get();

      $all_count =0;
      foreach($all as $all){
        $all_count +=$all->count;
      }


        // available package for premium

      $premium_available= $agencypackage->premium - $premium_count;
//return $premium_available;
            


        //return $propertydetails;
        $managelistings = Property::join('propertytypes','propertytypes.id','properties.propertytypes_id')
        ->join('agencies','agencies.id','properties.agency_id')
        ->leftJoin('agents','agents.id','properties.agent_id')
        ->join('status','status.id','properties.status_id')
        ->join('propertydetails','propertydetails.property_id','properties.id')
       ->join('propertylocations','propertylocations.property_id','properties.id')
       ->where('properties.agency_id','=',$agencypackage->id)
        ->get(array('propertytypes.typeName_en','propertytypes.typeName_ar',
        'properties.id','properties.package_type',
        'propertylocations.emirate_en','propertylocations.emirate_ar',
        'properties.price','properties.status_id','propertydetails.purpose','propertydetails.beds',
        'status.status_en','status.status_ar',"agents.name_en as agent_en","agents.name_ar as agent_ar"
          ));




          return response()->json(["managelistings" => $managelistings,"basic_available"=>$basic_available,
          "featured_available"=>$featured_available,
          "premium_available"=>$premium_available,
          "active_count"=>$active_count,"in_active_count"=>$inactive_count,
          "draft_count"=>$draft_count,"all_count"=>$all_count,
          "rent_count"=>$rent_count,"buy_count"=>$buy_count]);
    }

    public function filtermanagelisting(Request $request)
    {
      //return $request->all();
      $user = auth()->user();

      $agencypackage= Agency::where('user_id','=',$user->id)->first();

      $selectedLocation = $request->selectedLocations;
      $selectedLocationsArr = array();
      for($i=0;$i< count($selectedLocation); $i++){
          array_push($selectedLocationsArr, $selectedLocation[$i]['location']);

      }
      //return $selectedLocation;

     // basic package 
     $basic = DB::table('properties')
     ->select(DB::raw('count(package_type) as count'))
     ->where('package_type', '=', 1)
     ->where('agency_id','=',$agencypackage->id)
     ->groupBy('package_type')
     ->get();

     $basic_count =0;
     foreach($basic as $basic){
       $basic_count +=$basic->count;
     }

     $basic_available= $agencypackage->basic - $basic_count;


     //return $basic_available;
// featured package
     $featured = DB::table('properties')
     ->select(DB::raw('count(package_type) as count'))
     ->where('package_type', '=', 2)
      ->where('agency_id','=',$agencypackage->id)
     ->groupBy('package_type')
     ->get();

     $featured_count = 0;
     foreach($featured as $featured){
       $featured_count +=$featured->count;
     }

     //return $featured_count;

        // available packagefor featured
  
    $featured_available= $agencypackage->featured - $featured_count;

      //return $featured_available;

      $premium = DB::table('properties')
      ->select(DB::raw('count(package_type) as count'))
      ->where('package_type', '=', 3)
      ->where('agency_id','=',$agencypackage->id)
      ->groupBy('package_type')
      ->get();

      $premium_count =0;
      foreach($premium as $premium){
        $premium_count +=$premium->count;
      }

      $active = DB::table('properties')
      ->select(DB::raw('count(status_id) as count'))
      ->where('status_id', '=', 1)
      ->where('agency_id','=',$agencypackage->id)
      ->groupBy('status_id')
      ->get();

      $active_count =0;
      foreach($active as $active){
        $active_count +=$active->count;
      }

      $inactive = DB::table('properties')
      ->select(DB::raw('count(status_id) as count'))
      ->where('status_id', '=', 2)
      ->where('agency_id','=',$agencypackage->id)
      ->groupBy('status_id')
      ->get();

      $inactive_count =0;
      foreach($inactive as $inactive){
        $inactive_count +=$inactive->count;
      }

      $rent = DB::table('properties')->join("propertydetails","propertydetails.property_id","=","properties.id")
      ->select(DB::raw('count(purpose) as count'))
      ->where('purpose', '=', 1)
      ->where('agency_id','=',$agencypackage->id)
      ->groupBy('purpose')
      ->get();

      $rent_count =0;
      foreach($rent as $rent){
        $rent_count +=$rent->count;
      }

      $buy = DB::table('properties')->join("propertydetails","propertydetails.property_id","=","properties.id")
      ->select(DB::raw('count(purpose) as count'))
      ->where('purpose', '=', 2)
      ->where('agency_id','=',$agencypackage->id)
      ->groupBy('purpose')
      ->get();

      $buy_count =0;
      foreach($buy as $buy){
        $buy_count +=$buy->count;
      }

      $draft = DB::table('properties')->join("propertydetails","propertydetails.property_id","=","properties.id")
      ->select(DB::raw('count(status_id) as count'))
      ->where('status_id', '=', 3)
      ->where('agency_id','=',$agencypackage->id)
      ->groupBy('status_id')
      ->get();

      $draft_count =0;
      foreach($draft as $draft){
        $draft_count +=$draft->count;
      }

      $all = DB::table('properties')->join("propertydetails","propertydetails.property_id","=","properties.id")
      ->select(DB::raw('count(properties.id) as count'))
      ->where('agency_id','=',$agencypackage->id)
      ->get();

      $all_count =0;
      foreach($all as $all){
        $all_count +=$all->count;
      }


        // available package for premium

      $premium_available= $agencypackage->premium - $premium_count;
//return $premium_available;
            


        //return $propertydetails;
        $managelistings = Property::join('propertytypes','propertytypes.id','properties.propertytypes_id')
        ->join('agencies','agencies.id','properties.agency_id')
        ->join('agents','agents.id','properties.agent_id')
        ->join('status','status.id','properties.status_id')
        ->join('propertydetails','propertydetails.property_id','properties.id')
       ->join('propertylocations','propertylocations.property_id','properties.id')
       ->where('properties.agency_id','=',$agencypackage->id)
       ->where(function ($query) use($request){
        if($request->selected_button ==1){ $query->where('status_id',1 ); }
        if($request->selected_button ==2){ $query->where('status_id',2 ); }
        if($request->selected_button ==3){ $query->where('status_id',3 ); }
        if($request->selected_button ==4){ $query->where('status_id',">",0 ); }
        if($request->selected_button ==5){ $query->where('purpose',1 ); }
        if($request->selected_button ==6){ $query->where('purpose',2 ); }
        if($request->category_id > 0){ $query->where('propertytypes_id',$request->category_id ); }
        if($request->select_purpose > 0){ $query->where('purpose',$request->select_purpose); }
        if($request->beds > -1){ $query->where('beds',$request->beds); }

      })

      ->where(function ($query2) use($selectedLocationsArr){
        if(count($selectedLocationsArr) > 0){ 
            $query2->whereIn("propertylocations.emirate_en", $selectedLocationsArr); 
            $query2->orWhereIn("propertylocations.area_en", $selectedLocationsArr); 
            $query2->orWhereIn("propertylocations.streetorbuild_en", $selectedLocationsArr); 
        }
     })

        ->get(array('propertytypes.typeName_en','propertytypes.typeName_ar',
        'properties.id','properties.package_type',
        'propertylocations.emirate_en','propertylocations.emirate_ar',
        'properties.price','properties.status_id','propertydetails.purpose','propertydetails.beds',
        'status.status_en','status.status_ar',"agents.name_en as agent_en","agents.name_ar as agent_ar"
          ));




          return response()->json(["managelistings" => $managelistings,"basic_available"=>$basic_available,
          "featured_available"=>$featured_available,
          "premium_available"=>$premium_available,
          "active_count"=>$active_count,"inactive_count"=>$inactive_count,
          "all_count"=>$all_count,"draft_count"=>$draft_count,
          "rent_count"=>$rent_count,"buy_count"=>$buy_count]);
    }



    public function changestatus()
    {
        return "change status";
    }

    public function changepackage(Request $request)
    {
     // return $request->all();

      $user = auth()->user();
      //return $user;
        $properties = Property::where('id',$request->id)->first();
       // return $properties;
     

        
   $agencypackage= Agency::where('user_id','=',$user->id)->first();
       // return $agencypackage;
   // count of every used package 

   // BASIC
   if($request->basic ==1){

    $basic = DB::table('properties')
    ->select(DB::raw('count(package_type) as count'))
    ->where('package_type', '=', 1)
    ->where('agency_id','=',$properties->agency_id)
    ->groupBy('package_type')
    ->get();
    
     $basic_count =0;
    foreach($basic as $basic){
      $basic_count +=$basic->count;
    }
            $basic_available= $agencypackage->basic - $basic_count;
            if($basic_available > 0 )
            {
            $properties->package_type=1;
            $properties->update();
            }
            return response()->json(["packages" => $agencypackage,
            "basic_used"=>$basic_count,
            "basic_available" =>$basic_available]);
           }
             if($request->featured ==1 ){
           
             // FEATURED
             $featured = DB::table('properties')
             ->select(DB::raw('count(package_type) as count'))
             ->where('package_type', '=', 2)
             ->where('agency_id','=',1)
             ->groupBy('package_type')
             ->get();
             $featured_count = 0;
             foreach($featured as $featured){
               $featured_count +=$featured->count;
             }
            $featured_available= $agencypackage->featured - $featured_count;
            if($featured_available > 0 )
            {
            $properties->package_type=2;
            $properties->update();
            }
           

            return response()->json(["packages" => $agencypackage,
            "featured_used"=>$featured_count,
           "featured_available" =>$featured_available]);

           
            }
     



             if($request->premium==1){
         
                $premium = DB::table('properties')
                ->select(DB::raw('count(package_type) as count'))
                ->where('package_type', '=', 3)
                ->where('agency_id','=',1)
                ->groupBy('package_type')
                ->get();

                $premium_count =0;
                foreach($premium as $premium){
                  $premium_count +=$premium->count;
                }
        
                $premium_available= $agencypackage->premium - $premium_count;

                if($premium_available > 0 )
                {
                $properties->package_type=3;
                $properties->update();
                }

                return response()->json(["packages" => $agencypackage,
               "premium_used"=>$premium_count, "premium_available" =>$premium_available]);
            }
    }

    public function refresh()
    {
        return "refresh";
    }
}
