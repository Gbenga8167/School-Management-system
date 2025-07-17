<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TermCalendar extends Model
{
   protected $fillable = [
    'session',
    'term',
    'next_term_begins'
   ];

   //tells eloquent to treat next_term_begins as a real carbon date
   protected $casts = [
    'next_term_begins' => 'date'
   ];
}
