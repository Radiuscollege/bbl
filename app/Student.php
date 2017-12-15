<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    public function student()
    {
        return $this->hasMany('App\StudentModules');
    }
}
