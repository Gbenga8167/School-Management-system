<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolSetting extends Model
{
    protected $fillable = [
        'name',
        'address',
        'motto',
        'logo',
    		
    ];
}
