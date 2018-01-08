<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentModules extends Model
{
    protected $fillable = [
        'student_id', 'module_id', 'mark', 'approved_by', 'begin_date', 'finish_date'
    ];

    protected $hidden = [
        'deleted_at', 'created_at', 'updated_at'
    ];

    protected $appends = ['began'];

    public function getBeganAttribute()
    {
        return $this->attributes['begin_date'] != null;
    }

    public function student()
    {
        return $this->belongsTo('App\Student');
    }
}
