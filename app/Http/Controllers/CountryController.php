<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\Agent;
use App\Models\Language;
class CountryController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:api');
    // }
    public function countries()
    {
        return response()->json(['countries'=>Country::all()]);
    } 
    public function agentinfo()
    {
        $nationality = Agent::join("countries","countries.id","=","agents.nationality")
        ->distinct()
        ->get(array("countries.id","countries.country_enNationality","countries.country_arNationality"));

        $languages = Agent::join("agentlanguages","agentlanguages.agent_id","=","agents.id")
        ->join("languages","languages.value","=","agentlanguages.language_id")
        ->distinct()
        ->get(array("languages.value as id","languages.label as language_en","languages.label_ar as language_ar"));


        return response()->json(['nationality' => $nationality,"languages" =>$languages]);
    }
}
