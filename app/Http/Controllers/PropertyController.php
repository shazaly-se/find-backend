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
use App\Models\Feature;
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
      if($request->selectedAgent > 0){
         $agency=Agency::where('user_id',$user->id)->first();
      }else{
         $agent=Agent::where('user_id',$user->id)->first();
      }

    

      $request->validate([
         'title_en'=>'required',
         'title_ar'=>'required'
     ]);


     
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
        }else{
         $property->agency_id  = $agent->agency_id;
         $property->agent_id  = $agent->id; 
        }
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
                 $propertydetails->area  = $request->square_area;
                 $propertydetails->purpose  = $request->purpose;
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
              }else{
               $propertylocation->agency_id  = $agent->agency_id;
               $propertylocation->agent_id  = $agent->id; 
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

                $healthandfitness = $request->get('healthandfitness');
                $amenitiesfeatures = $request->get('amenitiesfeatures');
                $miscellaneous = $request->get('miscellaneous');
                $securityandtechnology = $request->get('securityandtechnology');
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
             }

                return response($property->id);
         }    
    }

    public function upload(Request $request) {
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
                 return response()->json("saved successfully");
    }

    public function edit($id){
      // return $id;

      $property= Property::join("propertydetails","propertydetails.property_id","=","properties.id")
      ->join("categories","categories.id","=","properties.category_id")
      ->join("propertytypes","propertytypes.id","=","properties.propertytypes_id")
      ->join("propertylocations","propertylocations.property_id","=","properties.id")
      ->join("agents","agents.id","=","properties.agent_id")
      ->where("properties.id",$id)
      ->first(array("properties.*","propertydetails.purpose","propertydetails.beds","propertydetails.baths","propertydetails.area",
      "propertydetails.rent_frequency","propertydetails.permitnumber","propertydetails.min_contract_period","propertydetails.vacating_period",
      "propertydetails.maintainance_fee","propertydetails.paid_by","propertydetails.furnishing",
      "categories.id as category_id","categories.name_en as category_name_en","categories.name_ar as category_name_ar",
      "propertytypes.id as propertytype_id","propertytypes.typeName_en","propertytypes.typeName_ar",
      "propertylocations.location_name_en as address","propertylocations.location_name_ar as address_ar","propertylocations.country_en","propertylocations.country_ar",
      "propertylocations.emirate_en","propertylocations.emirate_ar","propertylocations.area_en","propertylocations.area_ar",
      "propertylocations.streetorbuild_en","propertylocations.streetorbuild_ar","propertylocations.lat","propertylocations.lng",
      "agents.id as agent_id","agents.name_en as agent_name_en","agents.name_ar as agent_name_ar",
   ));

   $medias = Media::where("property_id",$id)->get();

   $amenities = Propertyfeature::where("property_id",$id)->get();

   $healthandfitness= Propertyfeature::join("properties","properties.id","=","propertyfeatures.property_id")
   ->join("features","features.id","=","propertyfeatures.feature_id")
   ->where("properties.id",$id)
   ->where('group_id',1)->get();

   $features= Propertyfeature::join("properties","properties.id","=","propertyfeatures.property_id")
   ->join("features","features.id","=","propertyfeatures.feature_id")
   ->where("properties.id",$id)
   ->where('group_id',2)->get();

   $miscellaneous= Propertyfeature::join("properties","properties.id","=","propertyfeatures.property_id")
   ->join("features","features.id","=","propertyfeatures.feature_id")
   ->where("properties.id",$id)
   ->where('group_id',3)->get();

   $securityandtechnology= Propertyfeature::join("properties","properties.id","=","propertyfeatures.property_id")
   ->join("features","features.id","=","propertyfeatures.feature_id")
   ->where("properties.id",$id)
   ->where('group_id',4)->get();
   // $healthandfitness= Feature::where('group_id',1)->get();
   // $features= Feature::where('group_id',2)->get();
   // $miscellaneous = Feature::where('group_id',3)->get();
   // $securityandtechnology = Feature::where('group_id',4)->get();

      return response()->json(["property" =>$property,"medias"=>$medias,"healthandfitness" =>$healthandfitness,
      "features"=>$features,"miscellaneous"=>$miscellaneous,"securityandtechnology"=>$securityandtechnology]);
    }

    public function deletemedia($id){
       $media = Media::findOrFail($id);
       $media->delete();
       return response()->json('successfully deleted');
    }

    public function update($id,Request $request){
      $user = auth()->user();
      
      if($request->selectedAgent > 0){
         $agency=Agency::where('user_id',$user->id)->first();
      }else{
         $agent=Agent::where('user_id',$user->id)->first();
      }

      $property =  Property::findOrFail($id); 
      $propertydetails =  Propertydetail::where('property_id',$id)->first(); 
      $propertylocation =  Propertylocation::where('property_id',$id)->first(); 
      $old_propertyfeature = Propertyfeature::where("property_id",$id)->get();
      foreach($old_propertyfeature as $old_profet)
      {
         $old_profet->delete();
      }
      
      //return $propertylocations;
      $imagesName = [];
      $response = [];

      $property->category_id  = $request->category_id;
      $property->user_id  = $user->id;

      

      if($request->selectedAgent > 0){
         $property->agency_id  = $agency->id;
         $property->agent_id  = $request->selectedAgent; 
     }else{
      $property->agency_id  = $agent->agency_id;
      $property->agent_id  = $agent->id; 
     }
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

     if($property->update()){

      $propertydetails->property_id   = $property->id ;
                 $propertydetails->beds  = $request->bedroom;
                 $propertydetails->baths  = $request->bathroom;
                 $propertydetails->area  = $request->square_area;
                 $propertydetails->purpose  = $request->purpose;
                 $propertydetails->furnishing  = $request->firnished;
                 $propertydetails->rent_frequency  = $request->rent_frequency;
                 $propertydetails->permitnumber  = $request->permit_number;
                 $propertydetails->min_contract_period  = $request->min_contract_period;
                 $propertydetails->vacating_period  = $request->vacating_period;
                 $propertydetails->maintainance_fee  = $request->maintainance_fee;
                 $propertydetails->paid_by  = $request->paid_by;
                 $propertydetails->update();

                 //$propertylocation->property_id = $property->id;

                 if($request->selectedAgent > 0){
                  $propertylocation->agency_id = $agency->id;
                  $propertylocation->agent_id  = $request->selectedAgent; 
              }else{
               $propertylocation->agency_id  = $agent->agency_id;
               $propertylocation->agent_id  = $agent->id; 
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
                 $propertylocation->update();


                 $healthandfitness = $request->get('healthandfitness');
                 $amenitiesfeatures = $request->get('amenitiesfeatures');
                 $miscellaneous = $request->get('miscellaneous');
                 $securityandtechnology = $request->get('securityandtechnology');

                // return $healthandfitness;
                  for($i=0;$i< count($healthandfitness); $i++){
                      $propertyfeature=new Propertyfeature;
                      $propertyfeature->property_id=$property->id;
                      $propertyfeature->feature_id=$healthandfitness[$i]["id"];
                      $propertyfeature->status=$healthandfitness[$i]["status"];
          
                 
                       $propertyfeature->save();
                  }
                for($i=0;$i< count($amenitiesfeatures); $i++){
                  $propertyfeature=new Propertyfeature;
                  $propertyfeature->property_id=$property->id;
                  $propertyfeature->feature_id=$amenitiesfeatures[$i]["id"];
                  $propertyfeature->status=$amenitiesfeatures[$i]["status"];
                   $propertyfeature->save();
              }
                for($i=0;$i< count($miscellaneous); $i++){
                  $propertyfeature=new Propertyfeature;
                  $propertyfeature->property_id=$property->id;
                  $propertyfeature->feature_id=$miscellaneous[$i]["id"];
                  $propertyfeature->status=$miscellaneous[$i]["status"];
          
            
                   $propertyfeature->save();
              }
                for($i=0;$i< count($securityandtechnology); $i++){
                  $propertyfeature=new Propertyfeature;
                  $propertyfeature->property_id=$property->id;
                  $propertyfeature->feature_id=$securityandtechnology[$i]["id"];
                  $propertyfeature->status=$securityandtechnology[$i]["status"];
                  $propertyfeature->save();
              }

              return response($property->id);
        
     }


    }

}
