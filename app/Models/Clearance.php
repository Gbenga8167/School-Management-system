<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clearance extends Model
{
    protected $fillable = [
        'student_id',
        'class_id',
        'term',
        'session',
        'is_cleared',
      		
    ];
}
