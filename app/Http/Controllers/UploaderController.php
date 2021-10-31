<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Browse;
class UploaderController extends Controller
{
    public function upload(Request $request){
       // return $request->all();
        if($request->has('files')) {
          foreach($request->file('files') as $image) {
              $filename = time().rand(1,100) .  '.'.$image->getClientOriginalExtension();
              $image->move('uploads/browse/', $filename);

              $media = new Browse; 

             // $media->property_id=$request->property_id;
              $media->image=$filename;
              $media->save();
              return response()->json(["success"=>true,"data"=>["code"=> 220,"files"=>[$filename],"baseurl"=>"http://10.39.1.76/findproperties/public/uploads/browse/","messages"=>[],
              "isImages"=>[true]]]);

              
          }


          $response["status"] = "successs";
          $response["message"] = "Success! image(s) uploaded";
      }

    }

    public function download(){
       $files = Browse::first(array("browse.image as name"));
       return response()->json($files);
    }


}
