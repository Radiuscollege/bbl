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
        $this->middleware('teacher', ['only' => ['mark', 'getstudent', 'getaveragemark']]);
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

    public function toggle(Request $request, $id)
    {
        if (request('student') && Auth::user()->isTeacher()) {
            $student = request('student');
        } else {
            $student = Student::where('student_id', Auth::user()->getID())->pluck('id')->first();
        }

        $exists = StudentModules::where('student_id', $student)->where('module_id', $id)->first();

        $array = [
            'student_id' => $student,
            'module_id' => $id,
            'begin_date' => Carbon::now()->toDateString()
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

        $exists = StudentModules::where('student_id', request('student'))->where('module_id', $id)->first();

        $beginDate = new Carbon(request('beginDate'));

        $finishDate = new Carbon(request('finishDate'));
        
        $array = [
            'student_id' => request('student'),
            'module_id' => $id,
            'mark' => request('mark'),
            'approved_by' => $user,
            'begin_date' => $beginDate->toDateString(),
            'finish_date' => $finishDate->toDateString(),
            'note' => request('note')
        ];

        if (!$exists) {
            $exists = StudentModules::create($array);
        } else {
            $exists->update($array);
        }

        return $exists;
    }
}
