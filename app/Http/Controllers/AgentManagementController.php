<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agent;
use App\Models\User;
use App\Models\AgentLanguage;
use App\Models\AgentSpecialist;
use App\Models\Language;
use App\Models\Specialist;
use Hash;
use App\Mail\OfferMail;
use Mail;
class AgentManagementController extends Controller
{
    public function index()
    {
        $agents = Agent::join('users','users.id','agents.user_id')
        ->get(array('agents.*','users.email as email','users.active as active','users.profile as profile'));
        return response()->json(["agents" => $agents]);
    }
    public function store(Request $request)
    {
     
       // return $request->all();
       
       $languagesarray  = $request->selected;
       $specialistsarray  = $request->selectedspecialists;
       $agent = new Agent;
       $user = new User;
       $user->name = $request->name;
       $user->email =$request->email;
       $user->active=$request->active;
       $user->role = 3;
       $user->password = Hash::make($request->password);

       if($request->get('image'))
       {
          $image = $request->get('image');
          $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
          \Image::make($request->get('image'))->save(public_path('uploads/profiles/').$name);
          $user->profile=$name;
        }
        if($user->save()){

            $agent->user_id=$user->id;
            $agent->name=$request->name;
            $agent->name_ar=$request->name_ar;
            $agent->email=$request->email;
            $agent->mobile=$request->mobile;
            $agent->whatsapp=$request->whatsapp;
            $agent->land=$request->land;
            $agent->gender=$request->gender;
            $agent->nationality=$request->nationality;
            $agent->experience=$request->experience;
            $agent->facebook=$request->facebook;
            $agent->twitter=$request->twitter;
            $agent->instegram=$request->instegram;
            $agent->linkedin=$request->linkedin;
            $agent->qouta=$request->qouta;
      
            if($agent->save()){
                foreach ($languagesarray as $key => $value) {
                     AgentLanguage::insertGetId(
                        ['agent_id' => $agent->id,
                        'language_id' => $value['value']]
                    );
                }

                foreach ($languagesarray as $key => $value) {
                    AgentSpecialist::insertGetId(
                       ['agent_id' => $agent->id,
                       'specialist_id' => $value['value']]
                   );
               }

            }
            $email = 'shazaly.se@gmail.com';
            $offer = [
                'name' => $request->name,
                'username' => $request->email,
                'password' => $request->password
            ];
            Mail::to($email)->send(new OfferMail($offer));
            return response()->json("Mail sent! ");

        }
    }

    public function edit($id)
    {
        $agents = Agent::join('users','users.id','agents.user_id')
        ->join('countries','countries.id','agents.nationality')
        ->where('agents.id',$id)
        ->first(array('agents.*','users.email as email',
        'users.active as active','users.profile as profile',
        'countries.id as country_id','countries.country_enNationality as country_enNationality',
        'countries.country_arNationality as country_arNationality'));

  
        $languages_en= AgentLanguage::join("languages","languages.value",'=','agentlanguages.language_id')
        ->where("agent_id",$agents->id)->get(array("languages.value","languages.label"));
        $languages_ar= AgentLanguage::join("languages","languages.value",'=','agentlanguages.language_id')
        ->where("agent_id",$agents->id)->get(array("languages.value","languages.label_ar as label"));

        $alllanguages_en= Language::get(array("languages.value","languages.label"));
        $alllanguages_ar= Language::get(array("languages.value","languages.label_ar as label"));

        $specialists_en= AgentSpecialist::join("specialists","specialists.value",'=','agentspecialists.specialist_id')
        ->where("agent_id",$agents->id)->get(array("specialists.value as value","specialists.label"));
        $specialists_ar= AgentSpecialist::join("specialists","specialists.value",'=','agentspecialists.specialist_id')
        ->where("agent_id",$agents->id)->get(array("specialists.value as value","specialists.label_ar as label"));
        $allspecialists_en= Specialist::get(array("specialists.value as value","specialists.label"));
        $allspecialists_ar= Specialist::get(array("specialists.value as value","specialists.label_ar as label"));

        return response()->json(["agents" => $agents,"alllanguages_en"=>$alllanguages_en,"alllanguages_ar"=>$alllanguages_ar,
        "languages_en"=>$languages_en,"languages_ar"=>$languages_ar,
        "allspecialists_en"=>$allspecialists_en,
        "allspecialists_ar" =>$allspecialists_ar,
        "specialists_en"=>$specialists_en,"specialists_ar" =>$specialists_ar]);
    }

    public function update(Request $request, $id)
    {
        $languagesarray  = $request->selected;
        $specialistsarray  = $request->selectedspecialists;

        $agent =  Agent::findOrFail($id);
        $user =  User::where('id',$agent->user_id)->first();
        //return $request->all();

        $agent->name=$request->name;
        $agent->name_ar=$request->name_ar;
        $agent->email=$request->email;
        $agent->mobile=$request->mobile;
        $agent->whatsapp=$request->whatsapp;
        $agent->land=$request->land;
        $agent->gender=$request->gender;
        $agent->nationality=$request->nationality;
        $agent->experience=$request->experience;
        $agent->facebook=$request->facebook;
        $agent->twitter=$request->twitter;
        $agent->instegram=$request->instegram;
        $agent->linkedin=$request->linkedin;
        $agent->qouta=$request->qouta;

       if ($agent->update()){

        $user->name = $request->name;
        $user->email =$request->email;
        $user->active=$request->active;
 
        if($request->get('image'))
        {
           $image = $request->get('image');
           $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
           \Image::make($request->get('image'))->save(public_path('uploads/profiles/').$name);
           $user->image=$name;
         }
         $user->update();

         AgentLanguage::where('agent_id',$agent->id)->delete();
         AgentSpecialist::where('agent_id',$agent->id)->delete();

         foreach ($languagesarray as $key => $value) {
            AgentLanguage::insertGetId(
               ['agent_id' => $agent->id,
               'language_id' => $value['value']]
           );
       }

       foreach ($languagesarray as $key => $value) {
           AgentSpecialist::insertGetId(
              ['agent_id' => $agent->id,
              'specialist_id' => $value['value']]
          );
      }

       }

    
    }

    public function destroy($id)
    {
       
        $agent = Agent::findOrFail($id);
        $user = User::where('id',$agent->user_id)->first();
        $user->delete();
        $agent->delete();
        return response()->json('successfully deleted');

    }

  
}
