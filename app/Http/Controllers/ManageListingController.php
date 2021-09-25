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
        $properties = Property::all();
        //return $properties;
        $propertydetails = Propertydetail::all();
        //return $propertydetails;
        $managelistings = Property::join('propertytypes','propertytypes.id','properties.propertytypes_id')
        ->join('users','users.id','properties.user_id')
        ->join('status','status.id','properties.status_id')
        ->join('propertydetails','propertydetails.property_id','properties.id')
       ->join('propertylocations','propertylocations.property_id','properties.id')
        ->get(array('propertytypes.typeName_en','propertytypes.typeName_ar',
        'properties.id','properties.package_type',
        'propertylocations.emirate_en','propertylocations.emirate_ar','users.name','users.name_ar',
        'properties.price','properties.status_id','propertydetails.purpose','propertydetails.beds',
        'status.status_en','status.status_ar'
          ));
          return response()->json(["managelistings" => $managelistings]);
    }

    public function changestatus()
    {
        return "change status";
    }

    public function changepackage(Request $request)
    {

         
        $properties = Property::where('id',$request->id)->first();
       // return $properties;
     

        
   $agencypackage= Agency::where('id','=',$properties->agency_id)->first();
       // return $packages;
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

    // available package for basic
            $basic_available= $agencypackage->basic - $basic_count;

            //if($basic_available )
            if($basic_available > 0 )
            {
               // return "featured_available";
            // implement new featued property
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

                // available packagefor featured
          
            $featured_available= $packages->featured - $featured_count;

            
            if($featured_available > 0 )
            {
               // return "featured_available";
            // implement new featued property
            $properties->package_type=2;
            $properties->update();
            }
           

            return response()->json(["packages" => $packages,
            "featured_used"=>$featured_count,
           "featured_available" =>$featured_available]);

           
            }
     



             if($request->premium==1){
             //  return $featured_count;
          
                // premium
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

                   // available package for premium
        
                $premium_available= $packages->premium - $premium_count;

                if($premium_available > 0 )
                {
                   // return "featured_available";
                // implement new premium property
                $properties->package_type=3;
                $properties->update();
                }

                return response()->json(["packages" => $packages,
               "premium_used"=>$premium_count, "premium_available" =>$premium_available]);
            }
   
             


    // return response()->json(["basic_available" =>$basic_available,"featured_available" =>$featured_available, "premium_available" =>$premium_available]);
                

             

          


   
    }

    public function refresh()
    {
        return "refresh";
    }
}
