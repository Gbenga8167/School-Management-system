<?php

namespace App\Http\Controllers\backend;

use App\Models\User;
use App\Models\terms;
use App\Models\CBTTest;
use App\Models\academic_session;
use App\Models\classes;
use App\Models\subject;
use App\Models\Teacher;
use App\Models\CBTQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


// THE TEACHER CBT CONTROLLER
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

        $notification = array(
            'message' => 'Question added successfully!!',
            'alert-type' => 'success'
        );
    
        //redirect back to same page
    
       return redirect()->back()->with($notification );

    }//end method StoreQuestions



// Edit ALL CBTQuestion
public function edit($id)
{
    $cbtTest = CBTTest::with('questions')->findOrFail($id); // Fetch the test and its related questions
    return view('backend.teacher_account.cbt_test_question.cbt_questions_edit', compact('cbtTest'));
}

//EDIT SPECIFIC QUESTION
public function EditSpecificQuestion($id)
{
    $question = CBTQuestion::findOrFail($id); // Fetch the question by ID
    return view('backend.teacher_account.cbt_test_question.update_specific_question', compact('question'));
}

// UPDATE SPECIFIC QUESTION
public function update(Request $request, $id)
{
    $question = CBTQuestion::findOrFail($id);

    $request->validate([
        'question_text' => 'required|string',
        'option_a' => 'required|string',
        'option_b' => 'required|string',
        'option_c' => 'required|string',
        'option_d' => 'required|string',
        'correct_option' => 'required|string|in:A,B,C,D',
        'mark' => 'required|numeric',
    ]);

    $question->update([
        'question_text' => $request->question_text,
        'option_a' => $request->option_a,
        'option_b' => $request->option_b,
        'option_c' => $request->option_c,
        'option_d' => $request->option_d,
        'correct_option' => $request->correct_option,
        'mark' => $request->mark,
    ]);

    return redirect()->back()->with('success', 'Question updated successfully!');
  
   
}// end method

//Delete CBTQuestion
public function destroy($id)
{
    $question = CBTQuestion::findOrFail($id);
    $question->delete();

    return redirect()->back()->with('success', 'Question deleted successfully!');
}


public function DestroyAllCBTTestQuestion($id)
{

    $cbtTest = CBTTest::with('questions')->findOrFail($id);

    // Delete all related questions
    foreach ($cbtTest->questions as $question) {
        $question->delete();
    }

    // Delete the CBT test
    $cbtTest->delete();

    return redirect()->back()->with('success', 'CBT Test and all related questions deleted successfully!');
}





  /** TEACHER CBT RESULTS CHECK
     * Show the CBT results filter form
     */
        // Show list of CBT tests created by this teacher
public function results()
{
    // current term & session
    $currentTerm = \DB::table('terms')->where('is_current', 1)->value('name');
    $currentSession = \DB::table('academic_sessions')->where('is_current', 1)->value('name');

    // logged-in teacher
    $teacher = \App\Models\Teacher::where('user_id', auth()->id())->first();

    // fetch ONLY results for this teacher's CBT tests
    $results = \DB::table('c_b_t_attempts')
        ->join('c_b_t_tests', 'c_b_t_attempts.cbt_test_id', '=', 'c_b_t_tests.id')
        ->join('students', 'c_b_t_attempts.student_id', '=', 'students.id')
        ->join('users', 'students.user_id', '=', 'users.id')
        ->join('classes', 'c_b_t_tests.class_id', '=', 'classes.id')
        ->join('subjects', 'c_b_t_tests.subject_id', '=', 'subjects.id')
        ->select(
            'users.name as student_name',
            'classes.class_name as class_name',
            'subjects.subject_name as subject_name',
            'c_b_t_tests.term',
            'c_b_t_tests.session',
            'c_b_t_tests.assessment_type',
            'c_b_t_attempts.score',
            'c_b_t_attempts.id as attempt_id'
        )
        ->where('c_b_t_tests.term', $currentTerm)
        ->where('c_b_t_tests.session', $currentSession)
        ->where('c_b_t_tests.teacher_id', $teacher->id) // 🔑 restrict by teacher
        ->get();

    return view('backend.teacher_account.cbt_test_question.cbt_results_index', compact('results', 'currentTerm', 'currentSession'));
}

        
      public function retake($attemptId)
{
    try {
        \DB::transaction(function () use ($attemptId) {
            $attempt = \DB::table('c_b_t_attempts')->where('id', $attemptId)->first();

            if (!$attempt) {
                throw new \Exception('Attempt not found.');
            }

            // Delete all answers linked to this attempt
            \DB::table('c_b_t_answers')->where('cbt_attempt_id', $attemptId)->delete();

            // Delete the attempt itself
            \DB::table('c_b_t_attempts')->where('id', $attemptId)->delete();
        });

        return back()->with([
            'message' => 'Previous attempt deleted. Student can now retake the test afresh.',
            'alert-type' => 'success'
        ]);

    } catch (\Exception $e) {
        return back()->with([
            'message' => $e->getMessage(),
            'alert-type' => 'error'
        ]);
    }
}
  

}
