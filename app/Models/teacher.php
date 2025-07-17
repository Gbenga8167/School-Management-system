<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class teacher extends Model
{
    protected $guarded = [];
    

    public function user(){
        return $this->belongsTo(User::class);
    }

    //ASSIGN CLASS RELATIONSHIP
    public function assignment(){
        return $this->hasMany(classsubjectteacher::class);
    }


    //ASSIGN SUBJECT TEACHER RELATIONSHIP
    //TO DETERMINE THE ASSIGNED SUBJECT TO TEACHER
    
    public function assignedsubjectteacher(){
        return $this->hasMany(AssignedClassSubjectTeacher::class, 'teacher_id');
    }


    // retationship between class and their respective teachers to
    // handle some special task which other subject teacher can not handle 
    
    public function class(){
        return $this->hasMany(classes::class, 'class_teacher_id');
    }
}
