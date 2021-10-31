<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Hash;
class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['api','jwt.verify']);
    }
    public function changePassword(Request $request){
  
        // $request->validate([
        //     'newPassword'=>'required',
        //    // 'confirmPassword' => 'required|same:newPassword', 
        // ]);
        $user = auth()->user();
        //return $user;
       
        if($user){
            if (!(Hash::check($request->oldPassword, auth()->user()->password))) {
                return response()->json(["success"=>false,'msg'=>"Your current password does not matches with the password you provided. Please try again."]);
            } else        
            if(strcmp($request->oldPassword, $request->newPassword) == 0){
                //Current password and new password are same
                return response()->json(["success"=>false,'msg'=>"New Password cannot be same as your current password. Please choose a different password."]);
            } 
            else        
            if(strcmp($request->newPassword, $request->confirmPassword) != 0){
                //Current password and new password are same
                return response()->json(["success"=>false,'msg'=>"New Password and confirm  password mismatch."]);
            }else{
                User::find(auth()->user()->id)->update(['password'=> Hash::make($request->newPassword)]);
                return response()->json(["success"=>true,'msg'=>"successfully updated your password"]);

            }
    
          
        } else{
            return response()->json(["success"=>false,'msg'=>"user does not exist"]);
        }
        
    }

    public function changePicture(Request $request)
    {
        //return $request->all();
        $user = auth()->user();
        //return $user;
        if($user){
        

             if($request->hasFile('NewProfilePic'))
             {
                 //return $request->NewProfilePic;
               
                $NewProfilePic = $request->NewProfilePic;

                $filename   = time(). '.' . $NewProfilePic->getClientOriginalExtension();
                \Image::make($NewProfilePic)->save(public_path('uploads/profiles/' . $filename));


                // $name = time().'.' . explode('/', explode(':', substr($NewProfilePic, 0, strpos($NewProfilePic, ';')))[1])[1];
                // \Image::make($request->get('NewProfilePic'))->save(public_path('uploads/profiles/').$name);
                $user->profile=$filename;
                $user->update();
                return response()->json(["success"=>true,'msg'=>"successfully update profile pic","user"=>$user]);

              }

            // $user->pro
          


        }else{
            return response()->json(["success"=>false,'msg'=>"user does not exist"]);

        }
    }
    
}
