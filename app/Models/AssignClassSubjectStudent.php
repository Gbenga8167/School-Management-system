<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignClassSubjectStudent extends Model
{
    protected $guarded = [];

    
    public function student(){
        return $this->belongsTo(Student::class, 'student_id');
    }


    public function subject(){
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function class(){
        return $this->belongsTo(Classes::class, 'class_id');
    }
}
