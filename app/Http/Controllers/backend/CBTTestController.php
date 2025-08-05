<?php

namespace App\Http\Controllers\backend;

use App\Models\User;
use App\Models\CBTTest;
use App\Models\classes;
use App\Models\subject;
use App\Models\teacher;
use App\Models\CBTQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


// THE TEACHER CBT
class CBTTestController extends Controller
{
    public function CBTCreate(){

       
        $user = Auth::user();
        $teacher  = $user->teacher;
        if(! $teacher){
            abort(403, 'only teachers can create CBT tests');
        }

        $teacherId = $teacher->id;
        //only subjects and classes assigned to this teacher
        $assignedSubject = subject::whereHas('assignedTeachers', function ($query) use ($teacherId){
            $query->where('teacher_id', $teacherId); 
        })->get();


        $assignedClasses = classes::whereHas('assignedTeachers', function($query) use ($teacherId){
            $query->where('teacher_id', $teacherId); 
        })->get();
        return view('backend.teacher_account.cbt_test_question.cbt_test_create', compact('assignedSubject', 'assignedClasses'));
    
    
    }//end method



    public function CBTStore(Request $request){
       
        $request->validate([
            'title' => 'required|string',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'term' => 'required|string',
            'session' => 'required|string',
            'duration_minutes' => 'required|integer',
            'assessment_type' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'date|nullable',
        ]);

        $user = Auth::user();
        $teacher  = $user->teacher;
        $teacherId = $teacher->id;


        //check if teacher is actually assigned to this class and subject
        $isAssigned = DB::table('assigned_class_subject_teachers')
        ->where('teacher_id', $teacherId)
        ->where('class_id', $request->class_id)
        ->where('subject_id', $request->subject_id)
        ->exists();

        if(!$isAssigned){

             $notification = array(
                'message' => 'You are not assigned to this subject in the selected class.',
                'alert-type' => 'error'
            );
        
            //redirect back to same page
        
           return redirect()->back()->with($notification );
        }


         //check duplicated CBTTest(subject,class,term and session already exist?)
         $exixts = CBTTest::where('class_id', $request->class_id)
         ->where('subject_id', $request->subject_id)
         ->where('term', $request->term)
         ->where( 'session', $request->session,)  
         ->exists();
 
         if($exixts){
 
              $notification = array(
                 'message' => 'A CBT for this class, subject, term, and session already exist.',
                 'alert-type' => 'error'
             );
         
             //redirect back to same page
         
            return redirect()->back()->with($notification );
         }

        CBTTest::create([
            'title' => $request->title,
            'class_id' => $request->class_id,
            'subject_id' => $request->subject_id,
            'term' => $request->term,
            'session' => $request->session,
            'duration_minutes' => $request->duration_minutes,
            'assessment_type' => $request->assessment_type,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'teacher_id' =>$teacherId,
        ]);

        $notification = array(
            'message' => 'CBT Test created successfully!',
            'alert-type' => 'success'
        );
    
        //redirect back to same page
    
       return redirect()->back()->with($notification );
       
    }//end method 
    
    
    //teacher cbt logic to show cbt test created by the logged-in Teacher
    public function Index(){

        $user = Auth::user();
        $teacher  = $user->teacher;
        $teacherId = $teacher->id;

        $cbtTests = CBTTest::where('teacher_id', $teacherId)
        ->with(['subject', 'class'])//eager load relationships
        ->orderBy('created_at', 'desc')->get();

        return view('backend.teacher_account.cbt_test_question.cbt_index', compact('cbtTests'));

    }//end method  
    




    
   //create CBT question for teacher controller 
    public function CreateQuestions($cbtTestId){
        $cbtTest = CBTTest::with(['class', 'subject'])->findOrFail($cbtTestId);

        return view('backend.teacher_account.cbt_test_question.create_questions', compact('cbtTest'));

    }//end method 


     //store CBT question for teacher controller 
     public function StoreQuestions(Request $request, $cbtTestId){
        $cbtTest = CBTTest::findOrFail($cbtTestId);

        $validate = $request->validate([
            'questions' => 'required|array',
            'questions.*.question_text' => 'required|string',
            'questions.*.option_a' => 'required|string',
            'questions.*.option_b' => 'required|string',
            'questions.*.option_c' => 'required|string',
            'questions.*.option_d' => 'required|string',
            'questions.*.correct_option' => 'required|in:A,B,C,D',
            'questions.*.mark' => 'required|numeric|min:1',
        ]);

        foreach($validate['questions'] as $question){
            CBTQuestion::create([
                'cbt_test_id' => $cbtTest->id,
                'question_text' => $question['question_text'],
                'option_a' => $question['option_a'],
                'option_b' => $question['option_b'],
                'option_c' => $question['option_c'],
                'option_d' => $question['option_d'],
                'correct_option' => $question['correct_option'],
                'mark' => $question['mark'],
            ]);
        }

        return redirect()->back()->with('success', 'Question added successfully');

    }//end method StoreQuestions
}
