<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignedClassSubjectTeacher extends Model
{
    protected $guarded = [];


    public function subject(){
        return $this->belongsTo(subject::class, 'subject_id');
    }

    public function class(){
        return $this->belongsTo(classes::class, 'class_id');
    }

    public function teacher(){
        return $this->belongsTo(teacher::class, 'teacher_id');
    }

}
