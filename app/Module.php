<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Module extends Model
{
    protected $fillable = [
        'name',  'sub_description', 'week_duration', 'long_description'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    protected $appends = ['used', 'avg_duration'];

    public function getUsedAttribute()
    {
        return $this->studentModules()->exists();
    }

    public function getAvgDurationAttribute()
    {
        return $this->studentModules()->get()->avg('date_difference');
    }

    public function cohorts()
    {
        return $this->belongsToMany('App\Cohort');
    }
    
    public function studentModules()
    {
        return $this->hasMany('App\StudentModules');
    }
}
