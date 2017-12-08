<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        if (Auth::check())
        {
            if (Auth::user()->isTeacher())
            {
                return view('teacher');
            }
            else
            {
                return view('student');
            }
        }
        else
        {
            return redirect('amoclient/redirect');
        }
    }
}
