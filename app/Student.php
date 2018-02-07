<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    protected $appends = ['progress', 'cohort_name'];

    public function getProgressAttribute()
    {
        return ($this->studentModules->where('approved_by', '!=', null)->count() / $this->cohort->modules->count()) * 100;
    }

    public function getCohortNameAttribute()
    {
        return $this->cohort->name;
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
