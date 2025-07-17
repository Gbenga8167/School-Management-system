<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class student extends Model
{
    protected $guarded = [];

    //declaring relationship btw classes model and student model
    public function class(): BelongsTo{

        return $this->belongsTo(classes::class, 'class_id', 'id',);
    }


    public function user(){
        return $this->belongsTo(User::class);
    }

 
    //raltionship for student assigned to class in the current term/session in teacher psychomotor
    public function AssignedClassSubjectToStudents(){
        return $this->hasMany(AssignClassSubjectStudent::class);
    }
}
