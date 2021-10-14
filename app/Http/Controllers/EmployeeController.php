<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use Hash;
use App\Mail\UserMail;
use Mail;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::join('users','users.id','employees.user_id')
        ->get(array('employees.*','users.email as email','users.active as active','users.profile as profile'));
        return response()->json(["employees" => $employees]);
    }

    public function store(Request $request)
    {
         //return $request->all();
       // $myarray  = $request->selected;

        $user = new User;
        $employee = new Employee;

        $user->name = $request->name;
        $user->email =$request->email;
        $user->role = 3;
        $user->password = Hash::make($request->password);
 
        if($request->get('image'))
        {
           $image = $request->get('image');
           $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
           \Image::make($request->get('image'))->save(public_path('uploads/profiles/').$name);
           $user->profile=$name;
         }

         if($user->save())
         {
           
            $employee ->user_id = $user->id;
            $employee ->name_en = $request->name;
            $employee ->name_ar = $request->name_ar;
            $employee ->mobile = $request->mobile;
      
            $employee->save();
            
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

    public function show($id)
    {
        $employee = Employee::findOrFail($id);
        return response()->json($employee);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employee = Employee::join('users','users.id','employees.user_id')
        ->join('roles','roles.id','users.role')
        ->where('employees.id',$id)
        ->first(array('employees.*','users.email as email','users.active as active',
                      'users.profile as profile','users.role','roles.id as role_id',
                      'roles.role_name_en as role_name_en','users.profile as profile',
                      'roles.role_name_ar as role_name_ar'));
       
        return response()->json($employee);
    }
    public function update(Request $request, $id)
    {
     
        $employee =  Employee::find($id);
        $user = User::where('id',$employee->user_id)->first();
        

        $employee ->name_en = $request->name;
        $employee ->name_ar = $request->name_ar;
        $employee ->mobile = $request->mobile;
        if($employee->update())
        {
            $user->name=$request->name;
            $user->email=$request->email;
            $user->active=$request->active;
            $user->role=$request->role;
               if($request->get('image'))
        {
           $image = $request->get('image');
           $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
           \Image::make($request->get('image'))->save(public_path('uploads/profiles/').$name);
           $user->profile=$name;
         }
            if($user->update()){
                return "data updated";
              
            }
        }
       

   

       
      
        
    }

}
