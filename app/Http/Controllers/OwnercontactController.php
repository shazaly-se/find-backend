<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\Message;
use App\Models\User;
use App\Models\MessageChat;
use Carbon\Carbon;

class OwnercontactController extends Controller
{
    public function saveMessage(Request $request){


           $sender = auth()->user();
          // return response()->json(["sender"=>$sender,"request"=>$request->all()]);


          // return $sender;
          $locationsArr = array();
          if(count($request->selectedAgents) > 0){
            for($i=0;$i< count($request->selectedAgents); $i++){

                $reciever = User::where("id",$request->selectedAgents[$i]['value'])->first(array("id as reciever_id","name"));
   
                $newmessage= new MessageChat;
                $newmessage->sender = $sender->id;
                $newmessage->reciever = $reciever->reciever_id;
                $newmessage->message = $request->messageBody;
                $newmessage->recieved = $request->recieved;
                $newmessage->save();
                event(new Message($sender->id,$reciever->reciever_id,$request->messageBody,$request->recieved,Carbon::now()->toDateTimeString()));


              //  array_push($locationsArr, $selectedAgents[$i]['location']);
            }
                       return ["success" =>true];

          }


          
           
           
          // return response()->json(['success' =>true]);
           
   
   
       }
}
