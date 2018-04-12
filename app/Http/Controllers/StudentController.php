<?php

namespace App\Http\Controllers;

use Validator;
use App\User;
use App\Student;
use App\Cohort;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('teacher', ['except' => ['showAll', 'showOwn']]);
    }

    public function add()
    {
        return view('addstudent');
    }

    //return list of students to teacher or show studentinfo to student itself
    public function showAll()
    {
        if (Auth::user()->isTeacher()) {
            return Student::with('cohort')->get();
        } else {
            $student = Student::where('user_id', Auth::user()->getID())->first();
            $modules = Cohort::where('id', $student->cohort_id)->with(
                [
                    'modules.studentModules' => function ($query) use ($student) {
                        $query->where('user_id', $student->id);
                    }
                ]
            )->get();
    
            return $modules;
        }
    }

    public function showOwn()
    {
        return Student::where('user_id', Auth::user()->getID())->first();
    }

    //show specific student
    public function loadStudent(Student $student, $id)
    {
        return Student::find($id);
    }

    public function studentSearch()
    {
        return view('studentsearch');
    }

    //splits the search term and returns students that contain the search term
    public function studentSearchResult(Request $request, $value)
    {
        $words = explode(' ', $value);
        /* not functioning, can't search on cohortname
        return Student::with(['cohort' => function ($query) use ($words) {
            foreach ($words as $word) {
                $query->orWhere('name', 'LIKE', '%' . $word . '%');
            }
        }])->where(function ($query) use ($words) {
        */
        return Student::with('cohort')->where(function ($query) use ($words) {
            foreach ($words as $word) {
                $query->orWhere('user_id', 'LIKE', '%' . $word . '%');
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
        //checks if user_id doesnt exist yet
        $validatedData = $request->validate([
            'user_id' => 'required|unique:students,user_id|max:20',
            'cohorts' => 'required',
            'first_name' => 'required|max:40',
            'prefix' => 'max:40',
            'last_name' => 'required|max:40',
            'date' => 'required|date',
        ]);

        $user = User::find(request('user_id'));
        if (!$user) {
            $user = new User();
            $user->id = request('user_id');
            $user->name = request('first_name')
            . ((!request('prefix')) ? ' ' : (' ' . request('prefix') . ' ')) . request('last_name');
            $user->email = request('user_id') . '@edu.rocwb.nl';
            $user->type = 'student';
            $user->save();
        }

        $date = strtotime(request('date'));
        $finalDate = Carbon::createFromTimestamp($date)->toDateString();

        $student = Student::create([
            'user_id' => request('user_id'),
            'cohort_id' => request('cohorts'),
            'first_name' => request('first_name'),
            'prefix' => request('prefix'),
            'last_name' => request('last_name'),
            'started_on' => $finalDate,
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //validates and checks if user_id already exists except the previous id
        Validator::make($request->all(), [
            'user_id' => ['required', 'max:20',
            Rule::unique('students')->ignore(request('user_id'), 'user_id')],
            'first_name' => 'required|max:40',
            'prefix' => 'max:40',
            'last_name' => 'required|max:40',
            'date' => 'required|date',
        ])->validate();



        $date = strtotime(request('date'));
        $finalDate = Carbon::createFromTimestamp($date)->toDateString();

        $student = Student::findOrFail($id);

        $user = User::find($student->user_id);
        if ($user) {
            $user->id = request('user_id');
            $user->name = request('first_name')
            . ((!request('prefix')) ? ' ' : (' ' . request('prefix') . ' ')) . request('last_name');
            $user->email = request('user_id') . '@edu.rocwb.nl';
            $user->type = 'student';
            $user->save();
        }

        $student->update([
            'user_id' => request('user_id'),
            'first_name' => request('first_name'),
            'prefix' => request('prefix'),
            'last_name' => request('last_name'),
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
