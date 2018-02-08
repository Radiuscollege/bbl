<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Student extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'student_id', 'cohort_id', 'first_name', 'prefix', 'last_name', 'started_on'
    ];

    protected $hidden = [
        'deleted_at', 'created_at', 'updated_at'
    ];

    protected $appends = ['progress', 'estimated_progress'];

    public function getProgressAttribute()
    {
        return $this->studentModules->where('approved_by', '!=', null)->count() / $this->cohort->modules->count() * 100;
    }

    public function getEstimatedProgressAttribute()
    {
        $started_date = new Carbon($this->attributes['started_on']);
        return $this->cohort->modules->sum('week_duration') / $started_date->diffInWeeks(Carbon::today()) * 100;
    }
    
    public function studentModules()
    {
        return $this->hasMany('App\StudentModules');
    }

    public function cohort()
    {
        return $this->belongsTo('App\Cohort');
    }
}
