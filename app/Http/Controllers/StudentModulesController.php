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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $student = Student::where('student_id', Auth::user()->getID())->first();
        $modules = Cohort::where('id', $student->cohort_id)->with(
            [
                'modules.studentModules' => function ($query) use ($student) {
                    $query->where('student_id', $student->id);
                }
            ]
        )->get();

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

    //Check if its student or teacher (to prevent students from toggling other students modules)
    //then either add or remove the begin_date
    public function toggle(Request $request, $id)
    {
        if (request('student') && Auth::user()->isTeacher()) {
            $student = request('student');
        } else {
            $student = Student::where('student_id', Auth::user()->getID())->first();
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

    //Adds or updates mark
    public function mark(Request $request, $id)
    {
        $exists = StudentModules::where('student_id', request('student'))->where('module_id', $id)->first();

        $beginDate = new Carbon(request('beginDate'));

        $finishDate = new Carbon(request('finishDate'));
        
        $array = [
            'student_id' => request('student'),
            'module_id' => $id,
            'mark' => request('mark'),
            'approved_by' => Auth::user()->getID(),
            'begin_date' => $beginDate->toDateString(),
            'finish_date' => $finishDate->toDateString(),
            'note' => request('note')
        ];

        if (!$exists) {
            $exists = StudentModules::create($array);
        } else {
            if (request('mark') == null && request('pass') == false) {
                $exists->update([
                    'student_id' => request('student'),
                    'module_id' => $id,
                    'mark' => null,
                    'approved_by' => null,
                    'begin_date' => $beginDate->toDateString(),
                    'finish_date' => null,
                    'note' => request('note')
                ]);
            } else {
                $exists->update($array);
            }
        }

        return $exists;
    }

    //get student with all the related existing studentmodules
    public function getStudent($id)
    {
        $student = Student::with([
            'cohort.modules.studentModules' => function ($query) use ($id) {
                $query->where('student_id', $id);
            } , 'cohort.modules.studentModules.user:name'
        ])->findOrFail($id);

        return $student;
    }
}
