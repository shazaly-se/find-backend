<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Propertfeature;
class PropertyfeaturesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $propertyfeatures = Propertfeature::all();
        return response()->json(['propertyfeatures' => $propertyfeatures]);
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
        $propertyfeature = new Propertfeature;
        $propertyfeature->property_id = $request->property_id;
        $propertyfeature->feature_id = $request->feature_id;
        $propertyfeature->status = $request->status;
        $propertyfeature->save();
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
        $propertyfeature = Propertfeature::findOrFail($id);
        return response()->json($propertyfeature);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $propertyfeature = Propertfeature::findOrFail($id);
        return response()->json($propertyfeature);
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
        $propertyfeature = Propertfeature::findOrFail($id);
        $propertyfeature->property_id = $request->property_id;
        $propertyfeature->feature_id = $request->feature_id;
        $propertyfeature->status = $request->status;
        $propertyfeature->update();
        return response()->json("successfully updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $propertyfeature = Propertfeature::findOrFail($id);
        $propertyfeature->delete();
        return response()->json('successfully deleted');
    }
}
