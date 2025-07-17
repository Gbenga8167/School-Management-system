<?php

namespace App\Http\Controllers\backend;

use session;
use App\Models\terms;
use App\Models\classes;
use App\Models\student;
use Illuminate\Http\Request;
use App\Models\academic_session;
use App\Models\PsychoAssessment;
use App\Http\Controllers\Controller;
use App\Models\AssignClassSubjectStudent;

class PrincipalCommentController extends Controller
{
    public function PrincipalCommentForm(){

        $classes = classes::all();
        $terms = terms::where('is_current', true)->get();
        $sessions = academic_session::where('is_current', true)->get();
        return view('backend.admin_profile.principal_comments.form', compact('classes', 'terms', 'sessions'));
        
    }//end method


    public function PrincipalCommentLoad(Request $request){

        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'term' => 'required',
            'session' => 'required',

        ]);
        $classes = classes::all();
        $terms = terms::where('is_current', true)->get();
        $sessions = academic_session::where('is_current', true)->get();

        $assignedStudentIds = AssignClassSubjectStudent::where('class_id', $request->class_id)
        ->where('term', $request->term)
        ->where('session', $request->session)
        ->pluck('student_id');

        $students = student::whereIn('id', $assignedStudentIds)->get();

        if($students->isEmpty()){
            return back()->with('error', 'No students found for this class, term and session.');
        }


        //Fetch pricipal comment for each student

        foreach($students as $student){
            $student->assessment = PsychoAssessment::where([
                'student_id' => $student->id, 
                'class_id' =>  $request->class_id, 
                'term' => $request->term, 
                'session' => $request->session, 
            ])->first();
        }
        return view('backend.admin_profile.principal_comments.form',
         compact('classes', 'terms', 'sessions', 'students'))
        ->with([
            'selected_class_id' => $request->class_id,
            'selected_term' => $request->term,
            'selected_session' => $request->session,
        ]);
        
    }//end method


    public function PrincipalCommentSubmit(Request $request){
            $request->validate([
            'class_id' => 'required|exists:classes,id',
            'term' => 'required',
            'session' => 'required',
            'comments' => 'array',

        ]);

        $assignStudentIds = AssignClassSubjectStudent::where('class_id', $request->class_id)
        ->where('term', $request->term)
        ->where('session', $request->session)
        ->pluck('student_id');

        foreach($request->comments as $studentId =>$comment){

            if (!in_array($studentId, $assignStudentIds->toArray())) continue;

            if(trim($comment) === '') continue;

             PsychoAssessment::updateOrCreate(
                [
                'student_id' =>(int)$studentId, 
                'class_id' => (int)$request->class_id,
                'term' => $request->term, 
                'session' => $request->session, 
                ],
                [
                    'principal_comment' => $comment,

                ],
            );
        }

        
        return back()->with('success', 'Principal comments saved successfully!');

      }//end method


}
