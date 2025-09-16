<?php

namespace App\Http\Controllers\backend\StudentAccount;

use App\Models\User;
use App\Models\terms;
use App\Models\Result;
use App\Models\classes;
use App\Models\student;
use App\Models\subject;
use App\Models\Clearance;
use App\Models\TermCalendar;
use Illuminate\Http\Request;
use App\Models\SchoolSetting;
use App\Models\PsychoAssessment;
use App\Models\academic_sessions;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\AssignClassSubjectStudent;

class StudentAccountController extends Controller
{
    // LOGGED OUT STUDENT
    public function StudentLogout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }//end method


    public function StudentProfile(){

        //GET AUTHENTICATED LOGGED IN USERS(STUDENT)
        $id = Auth::user()->id;
        $StudentData = User::findOrFail($id);
        $studentphoto = student::where('user_id', $id)->first();
        return view('backend.student_account.student_profile_view', compact('StudentData', 'studentphoto'));
    
    
    }//end method
   

    //STUDENT CHECK RESULT LOGIC
    public function StudentResultForm(){
     $student = student::where('user_id', Auth::id())->firstOrFail();

     //Fetch distinct classes, terms, and sessions this student has result for
     $classIds = Result::where('student_id', $student->id)->pluck('class_id')->unique();

     $terms = Result::where('student_id', $student->id)->pluck('term')->unique();
     
     $sessions = Result::where('student_id', $student->id)->pluck('session')->unique();

     $classes = classes::whereIn('id', $classIds)->get();


        return view('backend.student_account.result_form', compact('classes', 'terms', 'sessions'));


    }// end method



    //STUDENT RESULTS VIEWS
    public function StudentResultView(Request $request){

        $request->validate([
            'class_id' =>'required|integer',
            'term' =>'required|string',
            'session' =>'required|string',
        ]);

        $classId = $request->class_id;
        $terms = $request->term;
        $sessions= $request->session;

        //GET AUTHENTICATED LOGGED IN USERS(STUDENT)
        $student = student::where('user_id', Auth::id())->firstOrFail();


        //CHECK IF STUDENT SELECTED CLASS, TERM AND SESSION MATCH
        $notMatch = Clearance::where('student_id', $student->id)
        ->where('class_id', $classId)
        ->where('term', $terms)
        ->where('session', $sessions)->first();

        //conditional statement to check if student result is cleared by the admin
        if(!$notMatch){
            return redirect()->back()->with(
                'error', 'Sorry, we couldn\'t find any results for the selected class, term, and session.');
        }


        //CLEAR STUDENT RESULT BY ADMIN
        $isCleared = Clearance::where('student_id', $student->id)
        ->where('class_id', $classId)
        ->where('term', $terms)
        ->where('session', $sessions)
        ->where('is_cleared', 1)->exists();

        //conditional statement to check if student result is cleared by the admin
        if(!$isCleared){
            return redirect()->back()->with(
                'error', 'You are not cleared to view this result. Please contact the admin');
        }

        // ressult attached to subject to determine
        // the student result for the selected combo
        $results = Result::with('subject')
        ->where('student_id', $student->id)
        ->where('class_id', $classId)
        ->where('term', $terms)
        ->where('session', $sessions)->orderBy('subject_id', 'asc')->get();

        //conditional statement to check if result exist
            if(!$results){
                return redirect()->back()->with(
                    'error', 'No result found for the selected class, term, and session.');
            }

            //Academic Calender
            $calender = TermCalendar::where('term', $terms)
            ->where('session', $sessions)->first();
            $nextTermBegins = $calender?->next_term_begins;

            //get student header from schoolsettings for logo, individual pictures etc..
            $settings = SchoolSetting::first();

            //get student class
            $class = classes::find($classId);

            //student assessment
            $assessment = PsychoAssessment::where([
                'student_id' => $student->id,
                'class_id' => $classId,
                'term' => $terms,
                'session' => $sessions,
            ])->first();



            //grading for total, percentage etc.. at the top student result
            $grandTotal = $results->sum('total');
            $subjectCount = $results->count();
            $percentage = $subjectCount > 0 ? round($grandTotal/$subjectCount) : 0;


            $remark = match(true){
                $percentage >= 70 =>'EXELLENT', 
                $percentage >= 60 =>'GOOD',
                $percentage >= 50 =>'CREDIT',
                $percentage >= 45 =>'PASS',
                $percentage >= 40 =>'WEAK PASS',
                default=> 'FAIL',
            };
            
            $student->score_summary = [

                'score' => $grandTotal,
                'percentage' => $percentage,
                'remark' => $remark,
            ];
            
            $totals = $student->score_summary;

            return view('backend.student_account.result_view', 
            compact('results','assessment', 'terms', 'sessions', 'class', 'student', 'nextTermBegins', 'settings', 'totals'));

    }// end method

    //STUDENT SUBJECTS VIEW
     public function StudentSubjects()
    {
        // Logged-in student
        $student = Student::where('user_id', Auth::id())->first();

        // Current term & session
        $currentTerm = DB::table('terms')->where('is_current', 1)->value('name');
        $currentSession = DB::table('academic_sessions')->where('is_current', 1)->value('name');

        // Get subjects for this student
        $subjectIds = AssignClassSubjectStudent::where('student_id', $student->id)
            ->where('term', $currentTerm)
            ->where('session', $currentSession)
            ->pluck('subject_id');

        $subjects = subject::whereIn('id', $subjectIds)->get();

        return view('backend.student_account.student_subjects', compact('subjects', 'currentTerm', 'currentSession'));
    }
}
