<?php

namespace App\Http\Controllers\TeacherBackendController;

use session;
use App\Models\User;
use App\Models\terms;
use App\Models\classes;
use App\Models\student;
use App\Models\teacher;
use Illuminate\Http\Request;
use App\Models\academic_session;
use App\Models\PsychoAssessment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\AssignClassSubjectStudent;

class TeacherPsychomotorController extends Controller
{
    public function showSelectedForm(){

        //get the logged in (authenticated) users id  
       $teacher = Auth()->id();

       //check if the logged in user is teacher
       $AssignedClassTeacher = teacher::where('user_id', $teacher)->first();
          
        //only fetch classes where the teacher is assigned as class teacher
        $classes = classes::where('class_teacher_id', $AssignedClassTeacher->id)->get();
        
        $terms = terms::where('is_current', true)->get();
        $sessions = academic_session::where('is_current', true)->get();

        return view('backend.teacher_account.select_psychomotor', compact('classes', 'terms', 'sessions'));
    
    }//end method


    public function LoadStudent(Request $request){

   
         $request->validate([
            'class_id' => 'required',
            'term_id' => 'required',
            'session_id' => 'required',
        ]);

        //get logged in user 
        $userid = Auth::user();

        //check if the logged in user is a class teacher
        //at
        $assignedteacher = $userid->teacher->id ?? null;

    
        //Ensure security : only assigned teacher can access
        $class = classes:: where('class_teacher_id',  $assignedteacher)->first();
        if(!$class){
            return back()->with('error', 'You are not authorized 
            to access the psychomotor Assessment.
             Only class teachers can access this section');
        }

       //check if the logged in user is a class teacher
        $terms = terms::where('is_current', true)->value('name');
        $sessions = academic_session::where('is_current', true)->value('name');
        
        //check the student id in the assign_class_subject_students matching the request
        $assignments = DB::table('assign_class_subject_students')
        ->where('class_id', $class->id)
        ->where('term', $terms)
        ->where('session', $sessions)
        ->get();

        $studentId = $assignments->pluck('student_id');
        $students = DB::table('students')
        ->whereIn('id', $studentId)
        ->get();


        $existingAssessment = PsychoAssessment::where('class_id', $class->id)
        ->where('term', $terms)
        ->where('session', $sessions)
        ->get()
        ->keyBy('student_id');
  
        return view('backend.teacher_account.assess_psychomotor',compact('students', 'class', 'terms', 'sessions', 'existingAssessment'));
    }


    public function StorePsychomotor(Request $request){
      $request->validate([
        'class_id' => 'required|integer',
        'term' => 'required|string',
        'session' => 'required|string',
        'assessments' => 'required|array|min:1',
        'assessments.*.student_id' => 'required|integer|exists:students,id',
        'assessments.*.attendance' => 'nullable|in:A,B,C,D,E,F',
         'assessments.*.punctuality' => 'nullable|in:A,B,C,D,E,F',
         'assessments.*.neatness' => 'nullable|in:A,B,C,D,E,F',
          'assessments.*.honesty' => 'nullable|in:A,B,C,D,E,F',
         'assessments.*.musical' => 'nullable|in:A,B,C,D,E,F',
         'assessments.*.initiative' => 'nullable|in:A,B,C,D,E,F',
        'assessments.*.creativity' => 'nullable|in:A,B,C,D,E,F',
         'assessments.*.sport' => 'nullable|in:A,B,C,D,E,F',
          'assessments.*.perseverance' => 'nullable|in:A,B,C,D,E,F',
          'assessments.*.cooperation' => 'nullable|in:A,B,C,D,E,F',
           'assessments.*.cooperation' => 'nullable|in:A,B,C,D,E,F',
           'assessments.*.teacher_comment' => 'nullable|string|max:255',

      ]);
    
  
    
  
        foreach($request->input('assessments') as $index => $assessment){
        
           PsychoAssessment::updateOrcreate([
                'student_id' =>  $assessment['student_id'],
                'class_id' => $request->class_id,
                'term' =>  $request->term,
                'session' =>   $request->session,],

                [
                    
                'teacher_id' =>   Auth::user()->teacher->id,
                'attendance' => $assessment['attendance'] ?? null,
                'punctuality' => $assessment['punctuality'] ?? null,
                'neatness' => $assessment['neatness'] ?? null,
                'honesty' => $assessment['honesty'] ?? null,
                'musical' => $assessment["musical"] ?? null,
                'initiative' => $assessment['initiative'] ?? null,
                'creativity' => $assessment['creativity'] ?? null,
                'sport' => $assessment['sport'] ?? null,
                'perseverance' => $assessment['perseverance'] ?? null,
                'cooperation' => $assessment['cooperation'] ?? null,
                'teacher_comment' => $assessment['teacher_comment'] ?? null,
            ]); 
         
            
        }
        $notification = array(
            'message' => ' Psychomotor Assessments Saved Successfully!',
            'alert-type' => 'info'
        );

        //redirect back to same page

  return redirect()->back()->with($notification);
        

    }
    

}
