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

    //Check if its student or teacher (to prevent students from toggling other students modules)
    //then either add or remove the begin_date
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
            $exists->update($array);
        }

        return $exists;
    }

    //get student with all the related existing studentmodules
    public function getstudent($id)
    {
        $student = Student::with([
            'cohort.modules.studentModules' => function ($query) use ($id) {
                $query->where('student_id', $id);
            } , 'cohort.modules.studentModules.user:name'
        ])->findOrFail($id);

        return $student;
    }

    //Gets all the modules with mark and names of a student that this current student has too
    public function getaveragemark(Request $request, $id)
    {
        $nameArray = array();
        $studentArray = array();
        $averageMarkArray = array();
        $markArray = array();

        //get all modules what is marked
        $student = Student::with([
            'cohort.modules.studentModules' => function ($query) use ($id) {
                $query->whereNotNull('mark')->where('student_id', $id);
            }
        ])->findOrFail($id);
        
        $studentModules = $student->cohort->modules;

        $otherStudents = Student::with([
            //'cohort.modules' => function ($query) use ($studentModules) {
            //    foreach ($studentModules as $module) {
            //        $query->where('id', $module->id);
            //    }
            //},
            'cohort.modules.studentModules' => function ($query) use ($id) {
                $query->whereNotNull('mark')->where('student_id', '!=', $id);
            }
        ])->findOrFail($id);

        $otherStudentModules = $otherStudents->cohort->modules;

        //prepare the data for the Vue Chart (average, 0.0, etc)
        foreach ($otherStudentModules as $osm) {
            $avg = array();
            foreach ($osm->studentModules as $studentModule) {
                if ($studentModule->mark && $studentModule->mark != "0") {
                    array_push($avg, $studentModule->mark);
                }
            }
            array_push($studentArray, $osm->name);
            array_push($averageMarkArray, array_sum($avg)/count($avg), 0.0);
        }

        foreach ($studentModules as $sm) {
            if ($sm->studentModules->first()) {
                array_push($nameArray, $sm->name);
                array_push($markArray, $sm->studentModules->first()->mark, 0.0);
            }
        }

        return [ "studentlabels" => $studentArray, "labels" => $nameArray, "marks" => $markArray, "averageMarks" => $averageMarkArray ];
    }
}
