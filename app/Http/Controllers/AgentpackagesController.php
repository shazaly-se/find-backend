<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agentpackage;

class AgentpackagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $agentpackage = Agentpackage::all();
        return response()->json(['agentpackage' => $agentpackage]);
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
        $agentpackage = new Agentpackage;
        $agentpackage->agent_id = $request->agent_id;
        $agentpackage->package = $request->package;
        $agentpackage->regular = $request->regular;
        $agentpackage->featured = $request->featured;
        $agentpackage->premium = $request->premium;
        $agentpackage->save();
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
        $agentpackage = Agentpackage::findOrFail($id);
        return response()->json($agentpackage);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $agentpackage = Agentpackage::findOrFail($id);
        return response()->json($agentpackage);
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
        $agentpackage = Agentpackage::findOrFail($id);
        $agentpackage->agent_id = $request->agent_id;
        $agentpackage->package = $request->package;
        $agentpackage->regular = $request->regular;
        $agentpackage->featured = $request->featured;
        $agentpackage->premium = $request->premium;
        $agentpackage->update();
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
        $agentpackage = Agentpackage::findOrFail($id);
        $agentpackage->delete();
        return response()->json('successfully deleted');
    }
}
