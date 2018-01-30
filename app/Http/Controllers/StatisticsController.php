<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Module;

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
        $moduleInfo = Module::withCount(['studentModules',
        'studentModules as finished_modules_count' => function ($query) {
            $query->where('approved_by', '!=', null);
        }, 'studentModules as avg' => function ($query) {
            $query->select(DB::raw('avg(mark) average'));
        }
        ])->get();
 
        return $moduleInfo;
    }
}
