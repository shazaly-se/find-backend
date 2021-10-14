<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
class WebsiteUserController extends Controller
{
    public function wishlist(Request $request)
    {
        $wishlist = new Wishlist;
        $old_property= Wishlist::where("property_id",$request->property_id)->first();
        if($old_property){
           
            $old_property->delete();
            return response()->json(["success" => "successfully deleted"]);
        }else{
            $wishlist->user_id=0; 
            $wishlist->property_id=$request->property_id; 
            $wishlist->status=1; 
            $wishlist->save();
            return response()->json(["success" => "successfully added"]);
        }
        
   
       
    }
}
