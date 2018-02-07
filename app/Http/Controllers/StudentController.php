<?php

namespace App\Http\Controllers;

use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('teacher', ['except' => ['showall']]);
    }

    public function add()
    {
        return view('addstudent');
    }

    public function showAll()
    {
        if (Auth::user()->isTeacher()) {
            return Student::with('cohort')->get();
        } else {
            return Student::where('student_id', Auth::user()->getID())->first();
        }
    }

    public function loadStudent(Student $student, $id)
    {
        return Student::find($id);
    }

    public function studentSearch()
    {
        return view('studentsearch');
    }

    //splits the search term and returns near identical students
    public function studentSearchResult(Request $request, $value)
    {
        $words = explode(' ', $value);
        /*
        return Student::with(['cohort' => function ($query) use ($words) {
            foreach ($words as $word) {
                $query->orWhere('name', 'LIKE', '%' . $word . '%');
            }
        }])->where(function ($query) use ($words) {
        */
        return Student::with('cohort')->where(function ($query) use ($words) {
            foreach ($words as $word) {
                $query->orWhere('student_id', 'LIKE', '%' . $word . '%');
            }
        })->orWhere(function ($query) use ($words) {
            foreach ($words as $word) {
                $query->orWhere('first_name', 'LIKE', '%' . $word . '%');
            }
        })->orWhere(function ($query) use ($words) {
            foreach ($words as $word) {
                $query->orWhere('last_name', 'LIKE', '%' . $word . '%');
            }
        })->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('studentlist');
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
            'studentNumber' => 'required|unique:students,student_id|max:20',
            'cohorts' => 'required',
            'firstName' => 'required|max:40',
            'prefix' => 'max:40',
            'lastName' => 'required|max:40',
            'date' => 'required|date',
        ]);

        $date = strtotime(request('date'));
        $finalDate = Carbon::createFromTimestamp($date)->toDateString();

        $student = Student::create([
            'student_id' => request('studentNumber'),
            'cohort_id' => request('cohorts'),
            'first_name' => request('firstName'),
            'prefix' => request('prefix'),
            'last_name' => request('lastName'),
            'started_on' => $finalDate,
            'graduated' => false,
        ]);

        $cohorts = request('cohorts');
        $stu = Student::find($student->id);
        $stu->cohort()->associate($cohorts);

        return;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        return view('student');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'firstName' => 'required|max:40',
            'prefix' => 'max:40',
            'lastName' => 'required|max:40',
            'date' => 'required|date',
        ]);

        $date = strtotime(request('date'));
        $finalDate = Carbon::createFromTimestamp($date)->toDateString();

        $student = Student::findOrFail($id);

        $student->update([
            'first_name' => request('firstName'),
            'prefix' => request('prefix'),
            'last_name' => request('lastName'),
            'started_on' => $finalDate,
        ]);

        return $student;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student, $id)
    {
        $student = Student::findOrFail($id);
        $student->studentModules()->delete();

        return response()->json($student->delete());
    }
}
