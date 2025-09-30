<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
   protected $fillable = [
      
    'student_id',
    'class_id',
    'subject_id',
    'term',
    'session',
    'ca1',
    'ca2',
    'ca3',
    'exam',
    'total',
    'grade',
    'remark',

   ];

   public function subject(){
      return $this->belongsTo(Subject::class);
  }

}
