<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentModules extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'student_id', 'module_id', 'mark', 'approved_by', 'begin_date', 'finish_date', 'note'
    ];

    protected $hidden = [
        'deleted_at', 'created_at', 'updated_at'
    ];

    protected $appends = ['began', 'expected_date', 'pass', 'teacher', 'date_difference'];

    protected $dates = ['deleted_at'];

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

    public function getDateDifferenceAttribute()
    {
        $begin = new Carbon($this->attributes['begin_date']);
        $end = new Carbon($this->attributes['finish_date']);
        return $begin->diffInWeeks($end);
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
