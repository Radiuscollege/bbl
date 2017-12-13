<?php

namespace App\Http\Controllers;

use App\Cohort;
use Illuminate\Http\Request;

class CohortController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('teacher');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Cohort::all();
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
        $validatedData = $request->validate([
            'name' => 'required|max:40',
        ]);
        //if(auth::user()->type == 'teacher')
        //return back();
        //$cohort = Cohort::create($request->all());
        return Cohort::create([ 'name' => request('name') ]);
        //dd($request);

        //$cohort = new Cohort;
        
        //$cohort->name = $request->name();
        //$cohort->save();

        //return $cohort;
        //return response()->json($cohort, 201);
        //return back()->with('success', 'Cohort created succesfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cohort  $cohort
     * @return \Illuminate\Http\Response
     */
    public function show(Cohort $cohort)
    {
        return Cohort::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Cohort  $cohort
     * @return \Illuminate\Http\Response
     */
    public function edit(Cohort $cohort)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cohort  $cohort
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cohort $cohort)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cohort  $cohort
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cohort $cohort)
    {
        //
    }
}
