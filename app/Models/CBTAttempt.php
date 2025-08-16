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

    // ✅ Add this line to cast timestamps as Carbon instances
    protected $dates = ['started_at','submitted_at','created_at','updated_at'];

    // belongs to a CBT test
    public function test(){
        return $this->belongsTo(CBTTest::class, 'cbt_test_id');
    }

    // belongs to a student
    public function student(){
        return $this->belongsTo(student::class);
    }

    // has many answers
    public function answers(){
        return $this->hasMany(CBTAnswer::class, 'cbt_attempt_id');
    }
}
