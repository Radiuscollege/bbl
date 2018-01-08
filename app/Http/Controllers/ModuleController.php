<?php

namespace App\Http\Controllers;

use App\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function add()
    {
        return view('module');
    }

    public function showall()
    {
        return Module::all();
    }

    public function loadmodule($id)
    {
        return Module::with('cohorts')->find($id);
    }

    public function editmodule(Request $request, $id)
    {
        $module = Module::findOrFail($id);
        $module->name = request('name');
        $module->sub_description = request('subDescription');
        $module->week_duration = request('weekDuration');
        $module->long_description = request('longDescription');

        $cohorts = request('cohorts');
        $module->cohorts()->sync($cohorts);
        $module->save();
        return $module;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('modulelist');
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
            'subDescription' => 'max:80',
            'weekDuration' => 'required|numeric'
        ]);

        $module = Module::create([
            'name' => request('name'),
            'sub_description' => request('subDescription'),
            'week_duration' => request('weekDuration'),
            'long_description' => request('longDescription')
        ]);

        $cohorts = request('cohorts');
        $module->cohorts()->attach($cohorts);

        return redirect('module');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('editmodule');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function edit(Module $module)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Module $module)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function destroy(Module $module)
    {
        //
    }
}
