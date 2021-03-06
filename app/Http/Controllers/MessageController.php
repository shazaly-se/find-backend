<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\Message;
use App\Models\User;
use App\Models\MessageChat;
use Carbon\Carbon;
class MessageController extends Controller
{

    public function users($id){
        // $users = MessageChat::rightJoin("users","users.id","=","messages.sender")
       
        // ->where('users.id',"!=",$id)
        // ->orderBy("messages.created_at","desc")
        // ->distinct()
        // ->get();
        //return $users;
        $users= User::where('id',"!=",$id)->get();
         return response()->json(['users' =>$users]);
    }
    public function index($id){

        $user = auth()->user();
        //return $user;
       // return response()->json(['current' =>$user,"sended user" =>$id]);

       if($user){

        $messages= MessageChat::where(function ($query) use($id){
          $query->where('reciever', $id)
          ->orWhere('sender', $id); 
         })

         ->where(function ($query1) use($user){
           $query1->where('reciever', $user->id)
           ->orWhere('sender', $user->id); 
          })

       ->get();


       return response()->json(["success"=>true,'messages' =>$messages]);

       }else{
        return response()->json(["success"=>false,'msg' =>"no user exist"]);

       }

        
    }

    public function saveMessage(Request $request){

     $user = auth()->user();
        $sender = auth()->user();
        $reciever = User::where("id",$request->reciever)->first(array("id as reciever_id","name"));
        if($sender){
      if($reciever){
        $newmessage= new MessageChat;
        $newmessage->sender = $sender->id;
        $newmessage->reciever = $reciever->reciever_id;
        $newmessage->message = $request->messageBody;
        $newmessage->recieved = $request->recieved;
        $newmessage->save();
        event(new Message($sender->id,$reciever->reciever_id,$request->messageBody,$request->recieved,Carbon::now()->toDateTimeString()));
        return ["success" =>true,"msg"=>"successfully sent."];

      }else{
        return ["success" =>false,"msg"=>"No reciever for message"];  
      }

        } else{
            return ["success" =>false,"msg"=>"user does not exist"]; 
        }
    

    
       
    }
}
