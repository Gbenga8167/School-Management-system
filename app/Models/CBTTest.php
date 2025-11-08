<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CBTTest extends Model
{
    protected $fillable = [
        'class_id',
        'subject_id',
        'teacher_id',
        'title',
        'term',
        'session',
        'assessment_type',
        'duration_minutes',
        'start_time',
        'end_time',     		
    ];

    //A test has many questions
    public function questions(){
        return $this->hasMany(CBTQuestion::class, 'cbt_test_id');
    }

    //A test has many student attempts
    public function attempts(){
        return $this->hasMany(CBTAttempt::class, 'cbt_test_id');
    }

    //Link to subject
    public function subject(){
        return $this->belongsTo(Subject::class);
    }

    //Link to class
    public function class(){
        return $this->belongsTo(Classes::class);
    }

    
    //Link to teacher
    public function teacher(){
        return $this->belongsTo(Teacher::class);
    }

}
