<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    //

    protected $fillable = 
    [
        'video',
        'course_id'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
