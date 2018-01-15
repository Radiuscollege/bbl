<?php

namespace App\Http\Controllers;

use App\StudentModules;
use App\Student;
use App\Cohort;
use App\Module;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentModulesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('teacher', ['only' => ['mark', 'getstudent']]);
    }

    public function toggle(Request $request, $id)
    {
        $student = Student::where('student_id', Auth::user()->getID())->pluck('id')->first();
        $user = User::where('id', Auth::user()->getID())->pluck('id')->first();
        $exists = StudentModules::where('student_id', $student)->where('module_id', $id)->first();

        $array = [
            'student_id' => $student,
            'module_id' => $id,
            'begin_date' => Carbon::now()->toDateString(),
            'finish_date' => null,
            'approved_by' => $user,
        ];

        if (!$exists) {
            $exists = StudentModules::create($array);
        } elseif (!$exists['approved_by']) {
            if (request('began') == true) {
                $exists->update(['begin_date' => Carbon::now()->toDateString() ]);
            } else {
                $exists->update(['begin_date' => null ]);
            }
        }

        return;
    }

    public function mark(Request $request, $id)
    {
        $user = User::where('id', Auth::user()->getID())->pluck('id')->first();
        $studentModule = StudentModules::find($id);

        if (request('passed')) {
            $studentModule->mark = request('mark');
            $studentModule->finish_date = Carbon::now()->toDateString();
        }
        
        $studentModule->approved_by = $user;
        $studentModule->save();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cohort = Student::where('student_id', Auth::user()->getID())->pluck('cohort_id')->first();
        $modules = Cohort::where('id', $cohort)->with('modules.studentModules')->get();

        return $modules;
    }

    public function getstudent($id)
    {
          $student = Student::with([
            'cohort', 'cohort.modules' , 'cohort.modules.studentModules' => function ($query) use ($id) {
                    $query->where('student_id', $id);
            } , 'cohort.modules.studentModules.user:name'
            ])->findOrFail($id);

        return $student;
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\StudentModules  $studentModules
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StudentModules  $studentModules
     * @return \Illuminate\Http\Response
     */
    public function edit(StudentModules $studentModules)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StudentModules  $studentModules
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StudentModules $studentModules)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StudentModules  $studentModules
     * @return \Illuminate\Http\Response
     */
    public function destroy(StudentModules $studentModules)
    {
        //
    }
}
