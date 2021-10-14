<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Propertytype;

class PropertytypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $propertytypes = Propertytype::all();
        return response()->json(['propertytypes' => $propertytypes]);
    }

    public function allpropertytypes()
    {
        $propertytypes = Propertytype::all();
        return response()->json(['propertytypes' => $propertytypes]);
    }




    public function propertytypeByCatId($id)
    {
        $propertytypes = Propertytype::where('category_id',$id)->get();
        return response()->json(['propertytypes' => $propertytypes]);
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
        $propertytypes = new Propertytype;
        $propertytypes->category_id = $request->category_id;
        $propertytypes->typeName_en = $request->typeName_en;
        $propertytypes->typeName_ar = $request->typeName_ar;
        $propertytypes->furnishedornot = $request->furnishedornot;
        $propertytypes->rentornot = $request->rentornot;
        $propertytypes->readyoffplan = $request->readyoffplan;
        $propertytypes->occupancy = $request->occupancy;
        $propertytypes->bedandbath = $request->bedandbath;
        $propertytypes->save();
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
        $propertytype =  Propertytype::join('categories','categories.id','=','propertytypes.category_id')
        ->where('propertytypes.id',$id)->first(array('propertytypes.*','categories.id as category_id','categories.name_en as name_en','categories.name_ar as name_ar'));
     
        return response()->json($propertytype);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //return response()->json("work");
        $propertytype =  Propertytype::join('categories','categories.id','=','propertytypes.category_id')
        ->where('propertytypes.id',$id)->first(array('propertytypes.*','categories.id as category_id','categories.name_en as name_en','categories.name_ar as name_ar'));
       // $propertytype = Propertytype::findOrFail($id);
        
        return response()->json($propertytype);
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
        $propertytypes =   Propertytype::findOrFail($id);
        $propertytypes->category_id = $request->category_id;
        $propertytypes->typeName_en = $request->typeName_en;
        $propertytypes->typeName_ar = $request->typeName_ar;
        $propertytypes->furnishedornot = $request->furnishedornot;
        $propertytypes->rentornot = $request->rentornot;
        $propertytypes->readyoffplan = $request->readyoffplan;
        $propertytypes->occupancy = $request->occupancy;
        $propertytypes->bedandbath = $request->bedandbath;
        $propertytypes->update();
        return response()->json("successfully UPDATED");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $propertytype = Propertytype::findOrFail($id);
        $propertytype->delete();
        return response()->json('successfully deleted');
    }
}
