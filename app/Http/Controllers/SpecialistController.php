<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Specialist;
class SpecialistController extends Controller
{
    public function index()
    {
        $specialists_en = Specialist::get(array('specialists.value','specialists.label as label'));
        $specialists_ar = Specialist::get(array('specialists.value','specialists.label_ar as label'));
        return response()->json(['specialists_en' => $specialists_en,'specialists_ar' => $specialists_ar]);
    }
}
