<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Propertystatus;

class PropertystatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $propertystatus = Propertystatus::all();
        return response()->json(['propertystatus' => $propertystatus]);
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
        $propertystatus = new Propertystatus;
        $propertystatus->property_id = $request->property_id;
        $propertystatus->status_id = $request->status_id;
        $propertystatus->save();
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
        $propertystatus = Propertystatus::findOrFail($id);
        return response()->json($propertystatus);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $propertystatus = Propertystatus::findOrFail($id);
        return response()->json($propertystatus);
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
        $propertystatus = Propertystatus::findOrFail($id);
        $propertystatus->property_id = $request->property_id;
        $propertystatus->status_id = $request->status_id;
        $propertystatus->update();
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
        $propertystatus = Propertystatus::findOrFail($id);
        $propertystatus->delete();
        return response()->json('successfully deleted');
    }
}
