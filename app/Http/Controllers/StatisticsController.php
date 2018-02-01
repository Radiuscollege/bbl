<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Module;
use App\Student;
use App\Cohort;

class StatisticsController extends Controller
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
        return view('statistics');
    }

    public function showstatistics()
    {
        $students = Student::where('graduated', false)->count();

        $cohorts = Cohort::withCount(['students as student_cohort_count'])->with('students')->get();

        $moduleInfo = Module::withCount(['studentModules as student_modules_count' => function ($query) {
            $query->where('approved_by', null);
        },
        'studentModules as finished_modules_count' => function ($query) {
            $query->where('approved_by', '!=', null);
        }, 'studentModules as avg_mark' => function ($query) {
            $query->select(DB::raw('avg(mark) average'));
        },
        ])->with(['studentModules.student'])
        ->get();
 
        return [ "cohorts" => $cohorts, "student_amount" => $students, "module_info" => $moduleInfo ];
    }
}
