<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignClassSubjectStudent extends Model
{
    protected $guarded = [];

    
    public function student(){
        return $this->belongsTo(student::class, 'student_id');
    }


    public function subject(){
        return $this->belongsTo(subject::class, 'subject_id');
    }

    public function class(){
        return $this->belongsTo(classes::class, 'class_id');
    }
}
