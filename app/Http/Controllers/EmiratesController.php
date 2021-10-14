<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class EmiratesController extends Controller
{
    public function index(){
         $emirates= DB::table("emirates")->get();
        return response()->json(['emirates' =>$emirates]);
    }

}
