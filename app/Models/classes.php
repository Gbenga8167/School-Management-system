<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class classes extends Model
{
    protected $guarded = [];


    //classes_subject stands for the pivot name
     //a pivot table can only accept foreign id which is subject_id and classes_id
     //withPivot() is used to push status colomn simply bcos the table can only allow ids 
    //declaring relationship btw classes model and subject model
    
    public function subjects(): BelongsToMany{

        return $this->belongsToMany(subject::class, 'classes_subject', 'classes_id', 'subject_id',)->withPivot
        ('status');

    }
    
    // retationship between class and their respective teachers to
    // handle some special task which other subject teacher can not handle 
    
    public function classTeacher(){
        return $this->belongsTo(teacher::class, 'class_teacher_id');
    }

    


    //raltionship for teacher's CBT(A teacher is assigned 
     //to many subject and a subject and class have many teacher)
     public function assignedTeachers(){
        return $this->belongsToMany(teacher::class, 'assigned_class_subject_teachers',
         'class_id', //foreign key on pivot table pointing to classes
          'teacher_id'//foreign key on pivot table pointing to teachers
          );
    }

   
}






