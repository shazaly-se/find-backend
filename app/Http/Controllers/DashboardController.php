<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Agency;
use App\Models\Agent;
use App\Models\Property;
use DB;
class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['api','jwt.verify']);
    }
    public function index(Request $request){
       // return "testing";
       $user = auth()->user();
     //  return $user;
     if($user){
// statrt
        if($user->role == 1){

            $agencies = Agency::join("emirates","emirates.id",'=',"agencies.address")
            ->select('emirates.emirate_en as name', DB::raw('COUNT(agencies.id) as value'))
            ->groupBy('emirates.emirate_en')
            ->get();

            $properties = Property::join("propertylocations","propertylocations.property_id","properties.id")
            ->select('propertylocations.emirate_en as name', DB::raw('COUNT(properties.id) as value'))
            ->groupBy('propertylocations.emirate_en')
            ->get();

            $sales = Property::join("propertydetails","propertydetails.property_id",'=',"properties.id")
            ->select('purpose', DB::raw('COUNT(properties.id) as property_count'))
            ->where('purpose', 2)
            ->groupBy('purpose')
            ->get();

            $rents = Property::join("propertydetails","propertydetails.property_id",'=',"properties.id")
            ->select('purpose', DB::raw('COUNT(properties.id) as property_count'))
            ->where('purpose', 1)
            ->groupBy('purpose')
            ->get();

            $totalagencies = Agency::get();
          //  ->where('agency_id', $agency->id)
            //->groupBy('agency_id')
            $total = 0;
           foreach($totalagencies as $totalagency){
          $total = $total +1;
           }

           $bymonths=Property::join("propertydetails","propertydetails.property_id",'=',"properties.id")
           ->join("purposes","purposes.id",'=',"propertydetails.purpose")
           ->select(
             
            //  DB::raw("(Count(purposes.name)) as rent"),
              DB::raw("(DATE_FORMAT(properties.created_at, '%m-%Y')) as name")
              )
              ->orderBy('properties.created_at')
             // ->where("purpose",1)
            
              ->groupBy(DB::raw("DATE_FORMAT(properties.created_at, '%m-%Y')"))
              ->get();

      foreach( $bymonths as  $bymonth){

      
              $bymonth->rent = DB::table('properties')
              ->join("propertydetails","propertydetails.property_id","=","properties.id")
                    ->select('purpose', DB::raw('COUNT(properties.id) as rent_count'))
                    ->where('propertydetails.purpose', 1)
                    
                    ->where(DB::raw("DATE_FORMAT(properties.created_at, '%m-%Y')"), $bymonth->name)
                    ->groupBy('purpose')->get();

                    $bymonth->sale = DB::table('properties')
                    ->join("propertydetails","propertydetails.property_id","=","properties.id")
                          ->select('purpose', DB::raw('COUNT(properties.id) as sale_count'))
                          ->where('propertydetails.purpose', 2)
                         
                          ->where(DB::raw("DATE_FORMAT(properties.created_at, '%m-%Y')"), $bymonth->name)
                          ->groupBy('purpose')->get();

      }
           // return $bymonths;



            return response()->json(["agencies"=>$agencies,"properties" =>$properties,"total_agencies"=>$total,"sales" =>$sales,"rents" =>$rents,"agents"=>[],"locations"=>[],"purpose"=>[],"bymonth"=>$bymonths]);


        }else
        if($user->role == 2){
           // return "agency";
            $agency = Agency::where("user_id",$user->id)->first();
           // return $agency;
           if($agency){
            $sales = Property::join("propertydetails","propertydetails.property_id",'=',"properties.id")
            ->select('agency_id', DB::raw('COUNT(properties.id) as property_count'))
            ->where('purpose', 2)
            ->where('agency_id', $agency->id)
            ->groupBy('agency_id')
            ->get();

       $rents = Property::join("propertydetails","propertydetails.property_id",'=',"properties.id")
            ->select('agency_id', DB::raw('COUNT(properties.id) as property_count'))
            ->where('purpose', 1)
            ->where('agency_id', $agency->id)
            ->groupBy('agency_id')
            ->get();
            $properties = Property::join("propertylocations","propertylocations.property_id","properties.id")
            ->select('propertylocations.emirate_en as name', DB::raw('COUNT(properties.id) as value'))
            ->where('properties.agency_id', $agency->id)
            ->groupBy('propertylocations.emirate_en')
            ->get();

            // $agents = Agent::join("agencies","agencies.id",'=',"agents.agency_id")
            // ->select('agency_id', DB::raw('COUNT(agents.id) agent_count'))
            // ->where('agency_id', $agency->id)
            // ->groupBy('agency_id')
            // ->get();

            $agents = Agent::join("emirates","emirates.id",'=',"agents.address")
            ->select('emirates.emirate_en as name', DB::raw('COUNT(agents.id) as value'))
            ->groupBy('emirates.emirate_en')
            ->get();
           // return $agents;

            $totalagents = Agent::where("agency_id",$agency->id)->get();
            //  ->where('agency_id', $agency->id)
              //->groupBy('agency_id')
              $total = 0;
             foreach($totalagents as $totalagent){
            $total = $total +1;
             }

            // $locations= Property::join("propertylocations","propertylocations.property_id","properties.id")
            //                       ->where("propertylocations.agency_id",$agency->id)
            //                       ->get();

                                  $purpose = Property::join("propertydetails","propertydetails.property_id",'=',"properties.id")
                                  ->join("purposes","purposes.id",'=',"propertydetails.purpose")
                                  ->select('purposes.name', DB::raw('COUNT(properties.id) as value'))
                               
                                  ->where('agency_id', $agency->id)
                                  ->groupBy('purposes.name')
                                  ->get();   

                                 $bymonths=Property::join("propertydetails","propertydetails.property_id",'=',"properties.id")
                                 ->join("purposes","purposes.id",'=',"propertydetails.purpose")
                                 ->select(
                                   
                                  //  DB::raw("(Count(purposes.name)) as rent"),
                                    DB::raw("(DATE_FORMAT(properties.created_at, '%m-%Y')) as name")
                                    )
                                    ->orderBy('properties.created_at')
                                   // ->where("purpose",1)
                                    ->where('agency_id', $agency->id)
                                    ->groupBy(DB::raw("DATE_FORMAT(properties.created_at, '%m-%Y')"))
                                    ->get();

                            foreach( $bymonths as  $bymonth){

                            
                                    $bymonth->rent = DB::table('properties')
                                    ->join("propertydetails","propertydetails.property_id","=","properties.id")
                                          ->select('purpose', DB::raw('COUNT(properties.id) as rent_count'))
                                          ->where('propertydetails.purpose', 1)
                                          ->where('agency_id', $agency->id)
                                          ->where(DB::raw("DATE_FORMAT(properties.created_at, '%m-%Y')"), $bymonth->name)
                                          ->groupBy('purpose')->get();

                                          $bymonth->sale = DB::table('properties')
                                          ->join("propertydetails","propertydetails.property_id","=","properties.id")
                                                ->select('purpose', DB::raw('COUNT(properties.id) as sale_count'))
                                                ->where('propertydetails.purpose', 2)
                                                ->where('agency_id', $agency->id)
                                                ->where(DB::raw("DATE_FORMAT(properties.created_at, '%m-%Y')"), $bymonth->name)
                                                ->groupBy('purpose')->get();

                            }


                                  
                                  


       return response()->json(["agents"=>$agents,"properties" =>$properties,"total_agents"=>$total,"sales" =>$sales,"rents" =>$rents,"locations"=>[],"purpose"=>[],"bymonth"=>$bymonths,"ip"=> \Request::ip()]);

           }else{
            return response()->json(["sales" =>0,"rents" =>0,"agents"=>0]);
           }
         
    //return $rents;
        }
        else
        if($user->role == 3){
            $agent = Agent::where("user_id",$user->id)->first();

            //return $agent;
             
             if($agent){

                $sales = Property::join("propertydetails","propertydetails.property_id",'=',"properties.id")
                ->select('agent_id', DB::raw('COUNT(properties.id) as property_count'))
                ->where('purpose', 2)
                ->where('agent_id', $agent->id)
                ->groupBy('agent_id')
                ->get();

           $rents = Property::join("propertydetails","propertydetails.property_id",'=',"properties.id")
                ->select('agent_id', DB::raw('COUNT(properties.id) as property_count'))
                ->where('purpose', 1)
                ->where('agent_id', $agent->id)
                ->groupBy('agent_id')
                ->get();

                $locations= Property::join("propertylocations","propertylocations.property_id","properties.id")
                ->where("propertylocations.agent_id",$agent->id)
                ->get();

                $purpose = Property::join("propertydetails","propertydetails.property_id",'=',"properties.id")
                ->join("purposes","purposes.id",'=',"propertydetails.purpose")
                ->select('purposes.name', DB::raw('COUNT(properties.id) as value'))
             
                ->where('agent_id', $agent->id)
                ->groupBy('purposes.name')
                ->get();   


           return response()->json(["sales" =>$sales,"rents" =>$rents]);
             }else{
                return response()->json(["sales" =>0,"rents" =>0]);
             }
         
        }else
        if($user->role == 4){
            return "normal user";
        }

// end
     }

    }
}
