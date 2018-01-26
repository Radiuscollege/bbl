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

    protected $appends = ['began', 'expected_date', 'pass', 'teacher'];

    public function getBeganAttribute()
    {
        return $this->attributes['begin_date'] != null;
    }

    //check if the begin date exists, it added an expected date (from Now) without it
    public function getExpectedDateAttribute()
    {
        if ($this->attributes['begin_date'] != null) {
            $date = new Carbon($this->attributes['begin_date']);
            return $date->addWeeks($this->module->week_duration)->toDateString();
        } else {
            return;
        }
    }

    public function getPassAttribute()
    {
        return $this->attributes['approved_by'] != null;
    }

    public function getTeacherAttribute()
    {
        return $this->user['name'];
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
