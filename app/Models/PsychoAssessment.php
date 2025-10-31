<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PsychoAssessment extends Model
{
    protected $fillable = [
        'student_id',
        'class_id',
        'term',
        'session',
        'teacher_id',
        'attendance',
        'punctuality',
        'neatness',
        'honesty',
        'musical',
        'initiative',
        'creativity',
        'sport',
        'perseverance',	
        'cooperation',
        'teacher_comment',	
        'principal_comment',		
    ];
}
