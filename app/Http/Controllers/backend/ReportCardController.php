<?php

namespace App\Http\Controllers\backend;

use App\Models\terms;
use App\Models\Result;
use App\Models\classes;
use App\Models\student;
use App\Models\Clearance;
use App\Models\TermCalendar;
use Illuminate\Http\Request;
use App\Models\SchoolSetting;
use App\Models\academic_session;
use App\Models\PsychoAssessment;
use App\Http\Controllers\Controller;

class ReportCardController extends Controller
{
    public function ShowReportSelectForm(){

        $classes = classes::orderBy('class_name')->get(); 

          //Fetch only the current term and session (admin controlled)
        $terms = terms::where('is_current', true)->get();
        $sessions = academic_session::where('is_current', true)->get();
    
        return view('backend.admin_profile.report.report_card_form_selector', 
        compact('classes', 'terms', 'sessions'));
    } //end method


    public function Index(){

        //GET SUBJECT STUDENT RESULT TABLE FOR EACH STUDENT 
        //SELECTED BY ADMIN IN THE CLASS, SESSION AND TERM
        //A. validate query parameter
        
        request()->validate([
            'class_id' => 'required|exists:classes,id',
            'term_id' => 'required|exists:terms,id',
            'session_id' => 'required|exists:academic_sessions,id',
        ]);

        $class = classes::findOrFail(request('class_id'));
        $term = terms::findOrFail(request('term_id'));
        $session = academic_session::findOrFail(request('session_id'));

        $calender = TermCalendar::where('term', $term->name)
        ->where('session', $session->name)->first();

        $nextTermBegins = $calender?->next_term_begins;
        //B. Fetch students assigned to that class in this term and session
        
        $students = student::whereIn('id', function($q) use($class,$term,$session)
        {
            $q->select('student_id')->from('assign_class_subject_students')
            ->where([
                'class_id' => $class->id,   
                'term' => $term->name,  
                'session' => $session->name,
            ]);
        })->orderBy('name')->get();

        //C. Attach class with summary totals to each student

        foreach ($students as $student){
            $student->report_class = $class->class_name;

            $results = Result::where([
                'student_id' => $student->id,
                'term' => $term->name,
                'session' => $session->name,
            ])->get();

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


            //ADD PSYCHOMOTO/ AFFECTIVE ROW
            $student->psychomotor = PsychoAssessment::where(
                [
                    'student_id' => $student->id,
                    'class_id' => $class->id,
                    'term' => $term->name,
                    'session' => $session->name,
                ])->first();

                $student->clearance = Clearance::where([
                    'student_id' => $student->id,
                    'class_id' =>  $class->id,
                    'term' => $term,
                    'session' => $session,
                ])->first();
        }
    
        return view('backend.admin_profile.report.report_card', [
            'settings' => SchoolSetting::first(),
            'students' => $students,
            'term' => $term,
            'session' => $session,
            'class' => $class,
            'nextTermBegins' => $nextTermBegins,
            'selected_class_id' => $class,
            'selected_term' => $term,
            'selected_session' => $session,

        ]);

    } //end method

     
}
