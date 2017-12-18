<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{

    protected $fillable = [
        'student_id', 'cohort_id', 'first_name', 'prefix', 'last_name', 'graduated', 'started_on'
    ];

    protected $hidden = [
        'deleted_at', 'created_at', 'updated_at'
    ];
    
    public function studentmodules()
    {
        return $this->hasMany('App\StudentModules');
    }

    public function cohort()
    {
        return $this->belongsTo('App\Cohort');
    }
}
