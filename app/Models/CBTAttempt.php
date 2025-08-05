<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CBTAttempt extends Model
{
    protected $fillable = [
        'cbt_test_id',
        'student_id',
        'score',
        'started_at',
        'submitted_at',
        'duration_used',
        'status',
        		
    ];

    // belongs to a CBT test
    public function test(){
        return $this->belongsTo(CBTTest::class, 'cbt_test_id');
    }


     // belongs to a student
     public function student(){
        return $this->belongsTo(student::class);
    }

     // belongs to a many answers
     public function answers(){
        return $this->hasMany(CBTAnswer::class, 'cbt_attempt_id');
    }

}
