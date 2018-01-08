<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cohort extends Model
{
    protected $fillable = [
        'name',
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function students()
    {
        return $this->hasMany('App\Student');
    }

    public function modules()
    {
        return $this->belongsToMany('App\Module');
    }
}
