<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class subject extends Model
{
    protected $guarded = [];


     //declaring relationship btw classes model and subject model
     //classes_subject stands for the pivot name
     //a pivot table can only accept foreign id which is subject_id and classes_id
     //withPivot() is used to push status colomn simply bcos the table can only allow ids 

     public function classes(): BelongsToMany{

        return $this->belongsToMany(classes::class, 'classes_subject', 'subject_id', 'classes_id',)->withPivot
        ('status');

    }



    //raltionship for teacher's CBT(A teacher is assigned 
     //to many subject and a subject and class have many teacher)
     public function assignedTeachers(){
        return $this->belongsToMany(Teacher::class, 'assigned_class_subject_teachers',
         'subject_id', //foreign key on pivot table pointing to subjects
          'teacher_id'//foreign key on pivot table pointing to teachers
          );
    }

}
