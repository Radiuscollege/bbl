<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class StudentModules extends Model
{
    protected $fillable = [
        'student_id', 'module_id', 'mark', 'approved_by', 'begin_date', 'finish_date', 'note'
    ];

    protected $hidden = [
        'deleted_at', 'created_at', 'updated_at'
    ];

    protected $appends = ['began', 'pass'];

    public function getBeganAttribute()
    {
        return $this->attributes['begin_date'] != null;
    }

    public function getPassAttribute()
    {
        return $this->attributes['approved_by'] != null;
    }

    public function student()
    {
        return $this->belongsTo('App\Student');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'approved_by');
    }

    public function module()
    {
        return $this->belongsTo('App\Module');
    }
}
