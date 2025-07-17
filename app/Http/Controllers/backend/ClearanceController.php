<?php

namespace App\Http\Controllers\backend;

use App\Models\Clearance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AssignClassSubjectStudent;

class ClearanceController extends Controller
{
    public function toggleClearance($studentId){

        $clearance =  Clearance::firstOrNew([
            'student_id' => $studentId,
            'class_id' => request('class_id'),
            'term' => request('term'),
            'session' => request('session'),
        ]);

        $clearance->is_cleared = !$clearance->is_cleared;
        $clearance->save();

        return response()->json(['message'=>'Student Clearance Updated']);

    }//end method


    public function clearAll(Request $request){

   
        
        $students = AssignClassSubjectStudent::where([
            'class_id' => $request->class_id,
            'term' => $request->term,
            'session' => $request->session,
        ])->pluck('student_id');

        
        dd($request->all());
        foreach($students as $studentId){
            Clearance::updateOrCreate([
            'student_id' => $studentId,
            'class_id' => $request->class_id,
            'term' => $request->term,
            'session' => $request->session, 
            ],
            [
                'is_cleared' => true
            ]);
        }
        return response()->json(['message' => 'All Students Cleared Successfully.']);

    }//end method
}
