<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Hash;
use App\Mail\UserMail;
use Mail;
class UserController extends Controller
{

    public function roles()
    {
        return response()->json(['roles'=>Role::all()]);
    }
    public function index()
    {
        return "index";
    }

    public function store(Request $request)
    {
       $user= User::create([
            'name' =>$request->name,
            'email' =>$request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);
        
        $email = 'shazaly.se@gmail.com';
   
        $user = [
            'name' => $request->name,
            'username' => $request->email,
            'password' => $request->password
        ];
        Mail::to($email)->send(new UserMail($user));
   
        return response()->json("Mail sent! ");


    }
}
