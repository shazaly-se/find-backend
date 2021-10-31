<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Technical;
class TechnicalmessageController extends Controller
{
    public function sendmessage(Request $request){
      //  return $request->all();
      $technicalmessage =  new Technical; 
      $technicalmessage->phone= $request->phone;
      $technicalmessage->email= $request->email;
      $technicalmessage->message= $request->message;
      $technicalmessage->save();
     
      return response()->json(["success"=>true,"msg"=>"message sent successfully !"]);
    }
}
