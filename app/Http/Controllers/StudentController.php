<?php

namespace App\Http\Controllers;

use App\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('teacher');
    }

    public function add()
    {
        return view('addstudent');
    }

    public function showall()
    {
        return Student::with('cohort')->get();
    }

    public function loadstudent(Student $student, $id)
    {
        return Student::find($id);
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
            'studentNumber' => 'required|max:20',
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
    public function show(Student $student, $id)
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
    public function update(Request $request, Student $student)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        //
    }
}
