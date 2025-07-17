<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PsychomotorAssessment extends Model
{
    protected $fillable = [
        'student_id',
        'class_id',
        'term_id',
        'session_id',
        'academic_session_id',
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
    ];
}
