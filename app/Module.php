<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = [
        'name',  'sub_description', 'week_duration', 'long_description'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function cohorts()
    {
        return $this->belongsToMany('App\Cohort');
    }
    
    public function studentModules()
    {
        return $this->hasMany('App\StudentModules')->where('student_id', 11);
    }
}
