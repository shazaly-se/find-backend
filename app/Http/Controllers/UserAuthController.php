<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OwnerOrNormalUser;

use Hash;
use Validator;

use Carbon\Carbon;
//use Cache;
use DB;
class UserAuthController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware(['api','jwt.verify']);
    // }
    public function userOnlineStatus()
    {
        $users = User::all();
       // return $users;
       $usersArr = array();
       foreach( $users as  $user)
       if($user->isOnline()){
        array_push($usersArr, $user->email);
       }

       return $usersArr;
       
       
    }

    public function chatUsers(){

        $user = auth()->user();

        if($user){

            $users = DB::table("messages")->join("users","users.id","=","messages.reciever")
            ->distinct()
            
            //->where("users.id","!=",)
            // ->where(function ($query) use($id){
            //     $query->where('reciever', $id)
            //     ->orWhere('sender', $id); 
            //    })
      
               ->where(function ($query1) use($user){
                 $query1->where('reciever', $user->id)
                 ->orWhere('sender', $user->id); 
                })
                ->orderBy("messages.created_at","desc")
                ->get(array("users.*"));
            // $users = User::distinct()->join("messages","messages.sender","users.id")
             
            // ->get();
            return response()->json(["success"=>true,'users' => $users]);
                  }
        else{
            return response()->json(["success"=>false,'msg' => "user does not exist"]);  
        }

       

    }


    public function login(Request $request)
    {
        //return "hello biniam";
        $request->validate([
            'email'=>'required',
            'password'=>'required'
        ]);
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['errors' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function register()
    {
        //return "hello biniam";
       // return request('fullName');
        // User::create([
        //     'name' =>request('fullName'),
        //     'email' =>request('email'),
        //     'role' =>request('userType'),
        //     'password' => Hash::make(request('password')),
        // ]);

        $ownerOrNormalUser = new OwnerOrNormalUser;

        $user = new User;
        $user->name = $request->name;
        $user->email =$request->email;
        $user->active=1;
        $user->role = $request->userType;
        $user->password = Hash::make($request->password);
        if($request->get('image'))
        {
           $image = $request->get('image');
           $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
           \Image::make($request->get('image'))->save(public_path('uploads/profiles/').$name);
           $user->profile=$name;
         }
        if($user->save()){
            if($user->role== 2){

            }elseif($user->role == 3){

            } else
            if($user->role == 4){
                
            } else
            if($user->role == 5){
                $ownerOrNormalUser->name = $request->name; 
                $ownerOrNormalUser->name_ar = $request->name_ar; 
                $ownerOrNormalUser->userType = $request->userType; 
                $ownerOrNormalUser->mobile = $request->mobile; 
                if($request->get('image'))
                {
                   $image = $request->get('image');
                   $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                   \Image::make($request->get('image'))->save(public_path('uploads/profiles/').$name);
                   $ownerOrNormalUser->profile=$name;
                 }
                 $ownerOrNormalUser->save();

            }

        }

        return $this->login(request());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
}
