<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Propertydetail;
use App\Models\Media;
use App\Models\Propertyfeature;
use App\Models\Propertylocation;
use App\Models\Agency;
use App\Models\Agent;
use Auth;
use JWTAuth;


use Validator;

class PropertyController extends Controller
{
    public function index(){
        return "index";
    }

    public function store(Request $request){

      $user = auth()->user();
      //return $user;
      if($request->selectedAgent > 0){
         $agency=Agency::where('user_id',$user->id)->first();
          // return response()->json(['agency'=>$agency]);
      }else{
         $agent=Agent::where('user_id',$user->id)->first();
        // return response()->json(['agent'=>$agent]);
      }
     
      

     
    

        $media = new Media; 
        $property = new Property; 
         $propertydetails = new Propertydetail; 

         $propertfeature = new Propertyfeature; 
         $propertylocation = new Propertylocation; 
         
         $imagesName = [];
         $response = [];

         $property->category_id  = $request->category_id;
         $property->user_id  = $user->id;

         

         if($request->selectedAgent > 0){
            $property->agency_id  = $agency->id;
            $property->agent_id  = $request->selectedAgent; 
            // return response()->json(['agency'=>$agency]);
        }else{
         $property->agency_id  = $agent->agency_id;
         $property->agent_id  = $agent->id; 
          // return response()->json(['agent'=>$agent]);
        }
         // selected agent or from auth user
         $property->propertytypes_id   = $request->propertytypes_id ;
         $property->title_en  = $request->title_en;
         $property->title_ar  = $request->title_ar;
         $property->details_en  = $request->description_en;
         $property->details_ar  = $request->description_ar;
         $property->price  = $request->price;
         $property->status_id  = 1;
        
         if($request->get('featuredimage'))
         {
            $image = $request->get('featuredimage');
            $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            \Image::make($request->get('featuredimage'))->save(public_path('uploads/properties/').$name);
            $property->image=$name;
          }
        else {
            $response["status"] = "failed";
            $response["message"] = "Failed! image(s) not uploaded";
        }

         if($property->save()){
 
                 $propertydetails->property_id   = $property->id ;
                 $propertydetails->beds  = $request->bedroom;
                 $propertydetails->baths  = $request->bathroom;
                // $propertydetails->kitchens  = $request->kitchens;
                 $propertydetails->area  = $request->square_area;
                 $propertydetails->purpose  = $request->purpose;
                 // $propertydetails->completion_status  = $request->completion_status;
                 // $propertydetails->ownership_status  = $request->ownership_status;
                 $propertydetails->furnishing  = $request->firnished;
                 $propertydetails->rent_frequency  = $request->rent_frequency;
                 $propertydetails->permitnumber  = $request->permit_number;
                 $propertydetails->min_contract_period  = $request->min_contract_period;
                 $propertydetails->vacating_period  = $request->vacating_period;
                 $propertydetails->maintainance_fee  = $request->maintainance_fee;
                 $propertydetails->paid_by  = $request->paid_by;
                 $propertydetails->save();

                 $propertylocation->property_id = $property->id;

                 if($request->selectedAgent > 0){
                  $propertylocation->agency_id = $agency->id;
                  $propertylocation->agent_id  = $request->selectedAgent; 
                  // return response()->json(['agency'=>$agency]);
              }else{
               $propertylocation->agency_id  = $agent->agency_id;
               $propertylocation->agent_id  = $agent->id; 
                // return response()->json(['agent'=>$agent]);
              }

             


                 $propertylocation->location_name_en  = $request->address;
                 $propertylocation->location_name_ar  = $request->address_ar;
                 $propertylocation->country_en  = $request->country;
                 $propertylocation->country_ar  = $request->country_ar;
                 $propertylocation->emirate_en  = $request->emirate;
                 $propertylocation->emirate_ar  = $request->emirate_ar;
                 $propertylocation->area_en  = $request->area;
                 $propertylocation->area_ar  = $request->area_ar;
                 $propertylocation->lat  = $request->lat;
                 $propertylocation->lng  = $request->lng;
                 $propertylocation->streetorbuild_en  = $request->streetorbuild;
                 $propertylocation->streetorbuild_ar  = $request->streetorbuild_ar;
                 $propertylocation->save();
                 


                //  foreach($request->healthandfitness as $healthandfitness)
                //   {
                //       return $healthandfitness;
                //     //$propertfeature
                //      if($healthandfitness->checked ==true){
                //           $propertfeature->status=1;
                //      }else{
                //         $propertfeature->status=0;
                //      }
                //      $propertfeature->save();

                //   }

                $healthandfitness = $request->get('healthandfitness');
                $amenitiesfeatures = $request->get('amenitiesfeatures');
               // return $amenitiesfeatures;
                $miscellaneous = $request->get('miscellaneous');
                $securityandtechnology = $request->get('securityandtechnology');
         
         
               // healthandfitness;
                 for($i=0;$i< count($healthandfitness); $i++){
                     $propertyfeature=new Propertyfeature;
                     $propertyfeature->property_id=$property->id;
                     $propertyfeature->feature_id=$healthandfitness[$i]["id"];
                   
         
                      if($healthandfitness[$i]["checkedcheckbox"] ==1){
         
                         $propertyfeature->status=1;
                      }else{
                         $propertyfeature->status=0;
                      }
                      $propertyfeature->save();
                 }
         
                 
               // amenitiesfeatures;
               for($i=0;$i< count($amenitiesfeatures); $i++){
                 $propertyfeature=new Propertyfeature;
                 $propertyfeature->property_id=$property->id;
                 $propertyfeature->feature_id=$amenitiesfeatures[$i]["id"];
               
         
                  if($amenitiesfeatures[$i]["checkedcheckbox"] ==1){
         
                     $propertyfeature->status=1;
                  }else{
                     $propertyfeature->status=0;
                  }
                  $propertyfeature->save();
             }
         
             
               // miscellaneous;
               for($i=0;$i< count($miscellaneous); $i++){
                 $propertyfeature=new Propertyfeature;
                 $propertyfeature->property_id=$property->id;
                 $propertyfeature->feature_id=$miscellaneous[$i]["id"];
               
         
                  if($miscellaneous[$i]["checkedcheckbox"] ==1){
         
                     $propertyfeature->status=1;
                  }else{
                     $propertyfeature->status=0;
                  }
                  $propertyfeature->save();
             }
         
             
               //securityandtechnology;
               for($i=0;$i< count($securityandtechnology); $i++){
                 $propertyfeature=new Propertyfeature;
                 $propertyfeature->property_id=$property->id;
                 $propertyfeature->feature_id=$securityandtechnology[$i]["id"];
               
         
                  if($securityandtechnology[$i]["checkedcheckbox"] ==1){
         
                     $propertyfeature->status=1;
                  }else{
                     $propertyfeature->status=0;
                  }
                  $propertyfeature->save();
                 // echo $healthandfitness[$i]["checkedcheckbox"];
             }

             

            
                return response($property->id);

                  
        
         }


 


     
        
    }

    public function upload(Request $request) {
      //  return $request->property_id;

       
        if($request->has('images')) {
            foreach($request->file('images') as $image) {
                $filename = time().rand(1,100) .  '.'.$image->getClientOriginalExtension();
                $image->move('uploads/properties/', $filename);

                $media = new Media; 

                $media->property_id=$request->property_id;
                $media->image=$filename;
                $media->save();
            }
            $response["status"] = "successs";
            $response["message"] = "Success! image(s) uploaded";
        }

        else {
            $response["status"] = "failed";
            $response["message"] = "Failed! image(s) not uploaded";
        }
         // end upload files
 
                 return response()->json("saved successfully");
    }

}
