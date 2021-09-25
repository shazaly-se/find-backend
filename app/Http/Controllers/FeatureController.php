<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feature;
class FeatureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allfeatures_en= Feature::get(array("features.id as value","features.feature_en as label"));

        $allfeatures_ar= Feature::get(array("features.id as value","features.feature_ar as label"));

        $healthandfitness= Feature::where('group_id',1)->get();
        $features= Feature::where('group_id',2)->get();
        $miscellaneous = Feature::where('group_id',3)->get();
        $securityandtechnology = Feature::where('group_id',4)->get();
        return response()->json(["healthandfitness" => $healthandfitness,
        "features" => $features,"miscellaneous" => $miscellaneous,
        "securityandtechnology" => $securityandtechnology,"allfeatures_en"=>$allfeatures_en,"allfeatures_ar"=>$allfeatures_ar]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $feature = new Feature;
        $feature->feature_en = $request->feature_en;
        $feature->feature_ar = $request->feature_ar;
        $feature->group_id = $request->group_id;
        if ($request->hasfile('icon_image')) {
            $file =$request->file('icon_image');
          
                $name =  $file->hashName();
                $file->move(public_path() . '/featureicons/', $name);
                $feature->icon_image = $name; 
                }

        $feature->save();
        return response()->json("successfully added");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $feature = Feature::findOrFail($id);
        return response()->json($feature);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $feature = Feature::findOrFail($id);
        return response()->json($feature);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    { 
        $feature = Feature::findOrFail($id);
        //return $feature;
        $feature->feature_en = $request->feature_en;
        $feature->feature_ar = $request->feature_ar;
        $feature->group_id = $request->group_id;

        if ($request->hasfile('icon_image')) {
                $file =$request->file('icon_image');
                $name =  $file->hashName();
                $file->move(public_path() . '/featureicons/', $name);
                $feature->icon_image = $name;
                }

        $feature->update();
        return response()->json("success updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $feature = Feature::findOrFail($id);
        $feature->delete();
        return response()->json("success deleted");
    }
}
