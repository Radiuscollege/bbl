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

    //Get progress by checking how many weeks have been approved
    //divided by how many weeks there are
    public function getProgressAttribute()
    {
        if ($this->cohort->modules->isNotEmpty() && $this->studentModules->isNotEmpty()) {
            $avg = [];
            foreach ($this->studentModules as $studentModule) {
                if ($studentModule->approved_by != null) {
                    array_push($avg, $studentModule->module->week_duration);
                }
            }
            return (array_sum($avg) / $this->cohort->modules->sum('week_duration')) * 100;
        } else {
            return 0;
        }
    }
    
    //Calculate estimated progress by looking at how many weeks the student is in
    //divided by the amount it actually takes
    public function getEstimatedProgressAttribute()
    {
        if ($this->studentModules()->exists()) {
            $startedOn = new Carbon($this->attributes['started_on']);
            $lastDate = new Carbon($this->studentModules()->latest('finish_date')->first()->finish_date);
            
            return $startedOn->diffInWeeks($lastDate) / $this->cohort->modules->sum('week_duration') * 100;
        } else {
            return 0;
        }
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
