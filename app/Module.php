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

    protected $appends = ['used'];

    public function getUsedAttribute()
    {
        return $this->studentModules()->exists();
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
