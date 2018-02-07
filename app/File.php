<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'location',  'module_id'
    ];

    public function modules()
    {
        return $this->belongsTo('App\Module');
    }
}
