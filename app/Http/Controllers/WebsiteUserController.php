<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\Property;
use App\Models\Propertydetail;
use App\Models\Propertyfeature;
use App\Models\Agency;
use App\Models\Agent;
class WebsiteUserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['api','jwt.verify']);
    }
    public function wishlist(Request $request)
    {
        $wishlist = new Wishlist;
        $user = auth()->user();
        if($user){
                $old_property= Wishlist::where("property_id",$request->property_id)->first();
        if($old_property){
           
            $old_property->delete();
            return response()->json(["success"=>true,"msg" => "successfully deleted"]);
        }else{
            $wishlist->user_id=$user->id; 
            $wishlist->property_id=$request->property_id; 
            $wishlist->status=1; 
            $wishlist->save();
            return response()->json(["success"=>true,"msg" => "successfully added"]);
        }
        } else{
            return response()->json(["success" =>false,"msg"=> "no user exist"]);

        }

    
       
    }

    public function allwishlist(){
        $user = auth()->user();
        //return $user;
        if($user){
         //   $wishlists =  Wishlist::where("user_id",$user->id)->get(); 

            $filteredproperties = Property::join('propertydetails','propertydetails.property_id','properties.id')
            ->join('propertytypes','propertytypes.id','properties.propertytypes_id')
            ->join('agents','agents.id','properties.agent_id')
            ->join('agencies','agencies.id','properties.agency_id')
            ->join('users','users.id','agents.user_id')
            ->join('propertylocations','propertylocations.property_id','properties.id')
            ->leftJoin('propertyfeatures','propertyfeatures.property_id','properties.id')
            ->leftJoin('features','features.id','propertyfeatures.feature_id')
            ->leftJoin('wishlists','wishlists.property_id','properties.id')

            // ->where(function ($query) use($request){
            //  if($request->purpose > 0){ $query->where('propertydetails.purpose', $request->purpose); }
            //  if($request->propertyType > 0){ $query->where('properties.propertytypes_id', $request->propertyType); }
            //  if($request->bedRoomSelected > -1){ $query->where('beds', $request->bedRoomSelected); }
            //  if($request->bathRoomSelected > 0){ $query->where('baths', $request->bathRoomSelected); }
            //  if($request->frequencySelected > 0){ $query->where('rent_frequency', $request->frequencySelected); }
            //  if($request->furnished > -1){ $query->where('propertydetails.furnishing', $request->furnished); }
            //  if($request->priceFrom > 0 && $request->priceTo  > 0 ){ $query->whereBetween('price', [$request->priceFrom , $request->priceTo ]); }
            //  if($request->priceFrom > 0 && $request->priceTo  == 0 ){ $query->where('price', '>=' ,$request->priceFrom ); }
            //  if($request->priceFrom == 0 && $request->priceTo  > 0 ){ $query->where('price', '<=' ,$request->priceTo ); }
            // //  if(count($request->subLocationSend) > 0){
            // //  if($request->subLocationSend["type"] == 1)
            // //  { 
            // //      $query->where('propertylocations.emirate_en', $request->subLocationSend["location"]);
            // //  }
            // //          if($request->subLocationSend["type"] == 2)
            // //          { 
            // //              $query->where('propertylocations.area_en', $request->subLocationSend["location"]);
            // //          }
            // //  if($request->subLocationSend["type"] == 3)
            // //  { 
            // //      $query->where('propertylocations.streetorbuild_en',$request->subLocationSend["location"]);
            // //  }
             
            
            // // }
        
            //  })
            //  ->where(function ($query2) use($locationsArr){
            //     if(count($locationsArr) > 0){ 
            //         $query2->whereIn("propertylocations.emirate_en", $locationsArr); 
            //         $query2->orWhereIn("propertylocations.area_en", $locationsArr); 
            //         $query2->orWhereIn("propertylocations.streetorbuild_en", $locationsArr); 
            //     }
            //  })
        
            //  ->where(function ($query3) use($featuresArr){
            //     if(count($featuresArr) > 0){ 
            //         $query3->whereIn("features.feature_en", $featuresArr); 
            //         $query3->orWhereIn("features.feature_ar", $featuresArr); 
            //     }
            //  })
             ->distinct()
        ->where("wishlists.user_id",$user->id)
            ->get(array('properties.*','propertydetails.beds','propertydetails.baths',
            'propertydetails.area','agents.name_en','agents.name_ar','agents.mobile','propertytypes.typeName_en',
            'propertytypes.typeName_ar','agencies.logo',"purpose","wishlists.status as wishlist_status","propertylocations.lat","propertylocations.lng"));

            return response()->json(["success" =>true,"wishlists"=> $filteredproperties]);

        }else{
            return response()->json(["success" =>false,"msg"=> "no user exist"]);
  
        }
    }
}
