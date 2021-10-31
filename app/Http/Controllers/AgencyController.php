<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agency;
use App\Models\User;
use App\Models\Employee;
use App\Models\Property;
use App\Models\Agent;
use App\Models\Language;
use Hash;
use App\Mail\UserMail;
use Mail;
use DB;

class AgencyController extends Controller
{
    public function index()
    {
      $agencies = Agency::join('users','users.id','agencies.user_id')->select('agencies.*','users.active','users.email')
       ->withCount('agents')->withCount('property')->get();

       //return $agencies;
       
       $agents = Agent::join('users','users.id','agents.user_id')
       ->join('countries','countries.id','agents.nationality')
       ->join('agencies','agencies.id','agents.agency_id')
       ->join('jobs','jobs.id','agents.job_id')
       ->select('agents.*','users.profile',"countries.country_enNationality",
       "countries.country_arNationality","agencies.name_en as agency_en","agencies.name_ar as agency_ar","agencies.logo",
       "jobs.job_title_en","jobs.job_title_ar")
       ->withCount('property')->get();

        foreach($agents as $agent){
       $agent->languages = Language::join("agentlanguages","agentlanguages.language_id","languages.value")->where("agent_id",$agent->id)->get();
                      }

      return response()->json(["agencies" => $agencies,"agents" => $agents]);
    }

    public function store(Request $request)
    {

   
        $v = Validator::make($request->all(), [
          'name' => 'required',
          'email' => 'required',
      ]);

          if ($v->fails())
          {
              return response()->json(["success"=>false,"error"=>$v->errors()]);
          }

  try{
    $user = new User;
    $agency = new Agency;
    $user->name = $request->name;
    $user->email =$request->email;
    $user->role = 2;
    $user->active = $request->active;
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
        $agency ->user_id = $user->id;
        $agency ->name_en = $request->name;
        $agency ->name_ar = $request->name_ar;
        $agency ->mobile = $request->mobile;
        $agency ->tradelicense = $request->tradelicense;
        $agency ->paytype = $request->paytype;
        $agency ->address = $request->address;
        $agency ->totalpackage = $request->totalpackage;
        $agency ->basic = $request->basic;
        $agency ->featured = $request->featured;
        $agency ->premium = $request->premium;
        if($request->get('image'))
        {
           $image = $request->get('image');
           $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
           \Image::make($request->get('image'))->save(public_path('uploads/profiles/').$name);
           $agency ->logo=$name;
         }
        $agency->save();
        // $email = 'shazaly.se@gmail.com';
   
        // $user = [
        //     'name' => $request->name,
        //     'username' => $request->email,
        //     'password' => $request->password
        // ];
        // Mail::to($email)->send(new UserMail($user));
   
        // return response()->json("Mail sent! ");
     }

  }catch(\Exception $e){

  return response()->json(["success"=>false,"msg"=>"something wrong","error"=> $e->getMessage()]);

  }

     
    }

    public function edit($id)
    {
      $agency = Agency::join('users','users.id','agencies.user_id')
      ->join('emirates','emirates.id','agencies.address')
      ->where('agencies.id',$id)
      ->first(array('agencies.*','users.email','users.profile','users.active',"emirates.id as address_id","emirates.emirate_en","emirates.emirate_ar"));
       
        return response()->json($agency);
    }

    public function show($id){

      $agency = Agency::join('users','users.id','agencies.user_id')
      ->join('emirates','emirates.id','agencies.address')
      ->where('agencies.id',$id)
      ->select('agencies.*','users.profile',"emirates.id as address_id","emirates.emirate_en","emirates.emirate_ar")->withCount('agents')->withCount('property')->get();
      $agency_name = Agency::join('users','users.id','agencies.user_id')
      ->where('agencies.id',$id)
      ->first("agencies.*",);
      $properties = Property::join("propertydetails","propertydetails.property_id","=","properties.id")
      ->where("agency_id",$id)->get(array("properties.*","propertydetails.beds","propertydetails.baths","propertydetails.area","propertydetails.purpose"));
      $agents = Agent::join('users','users.id','agents.user_id')
      ->where('agents.agency_id',$id)
      ->select('agents.*','users.profile')->with('language')->withCount('agentproperty')->get();
      return response()->json(['agency_name'=>$agency_name,"agency" =>$agency,"properties" =>$properties,"agents" =>$agents]);
    }
    public function search(){
    }
    public function update(Request $request, $id)
    { 
        $agency =Agency::find($id);
        $user   =User::where('id',$agency->user_id)->first();
        $agency ->name_en = $request->name;
        $agency ->name_ar = $request->name_ar;
        $agency ->mobile = $request->mobile;
        $agency ->tradelicense = $request->tradelicense;
        $agency ->paytype = $request->paytype;
        $agency ->address = $request->address;
        $agency ->totalpackage = $request->totalpackage;
        $agency ->basic = $request->basic;
        $agency ->featured = $request->featured;
        $agency ->premium = $request->premium;
        if($agency->update())
        {
            $user->name=$request->name;
            $user->email=$request->email;
            $user->active=$request->active;
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
    public function destroy($id)
    {
        $agency = Agency::findOrFail($id);
        $user = User::where('id',$agency->user_id)->first();
        $user->delete();
        $agency->delete();
        return response()->json('successfully deleted');
    }
    public function filteragency(Request $request)
    {
      $agencies = Agency::join('users','users.id','agencies.user_id')->select('agencies.*','users.active')
      ->withCount('agents')->withCount('property')->get();
      $agents = Agent::join('users','users.id','agents.user_id')
      ->join('countries','countries.id','=','agents.nationality')
      ->join('agentlanguages','agentlanguages.agent_id','=','agents.id')
      ->join('languages','languages.value','=','agentlanguages.language_id')
      ->join('agencies','agencies.id','agents.agency_id')
      ->join('jobs','jobs.id','agents.job_id')
      ->where(function ($query1) use($request){
      if($request->selectedNationality > 0){ $query1->where('agents.nationality', $request->selectedNationality); }
      if($request->selectedArea > 0){ $query1->where('selectedArea', $request->selectedArea); }
      if($request->selectedLanguage  >0){  $query1->where('agentlanguages.language_id', $request->selectedLanguage); }
      })->distinct() ->select('agents.*','users.profile',"countries.country_enNationality",
      "countries.country_arNationality","agencies.name_en as agency_en","agencies.name_ar as agency_ar","agencies.logo",
      "jobs.job_title_en","jobs.job_title_ar")
      ->withCount('agentproperty')->get();
      foreach($agents as $agent){
        $agent->language = Language::join('agentlanguages','agentlanguages.language_id','=','languages.value')->distinct()
        ->where("agentlanguages.agent_id",$agent->id)
        ->get();
      }
      return response()->json(["agencies" => $agencies,"agents" => $agents]);
    }
}
