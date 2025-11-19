<?php

namespace App\Http\Controllers\backend;

use App\Models\Term;
use App\Models\Result;
use App\Models\Classes;
use App\Models\Student;
use App\Models\Clearance;
use App\Models\TermCalendar;
use Illuminate\Http\Request;
use App\Models\SchoolSetting;
use App\Models\AcademicSession;
use App\Models\PsychoAssessment;
use App\Http\Controllers\Controller;

class ReportCardController extends Controller
{
    public function ShowReportSelectForm(){

        $classes = Classes::orderBy('class_name')->get(); 

          //Fetch only the current term and session (admin controlled)
        $terms = Term::where('is_current', true)->get();
        $sessions = AcademicSession::where('is_current', true)->get();
    
        return view('backend.admin_profile.report.report_card_form_selector', 
        compact('classes', 'terms', 'sessions'));
    } //end method


    public function Index(){

        // ALL STUDENT RESULT

        //GET SUBJECT STUDENT RESULT TABLE FOR EACH STUDENT 
        //SELECTED BY ADMIN IN THE CLASS, SESSION AND TERM
        //A. validate query parameter
        
        request()->validate([
            'class_id' => 'required|exists:classes,id',
            'term_id' => 'required|exists:terms,id',
            'session_id' => 'required|exists:academic_sessions,id',
        ]);

        $class = Classes::findOrFail(request('class_id'));
        $term = Term::findOrFail(request('term_id'));
        $session = AcademicSession::findOrFail(request('session_id'));

        $calender = TermCalendar::where('term', $term->name)
        ->where('session', $session->name)->first();

        $nextTermBegins = $calender?->next_term_begins;
        //B. Fetch students assigned to that class in this term and session
        
        $students = Student::whereIn('id', function($q) use($class,$term,$session)
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
                    'term' => $term->name,
                    'session' => $session->name,
                ])->first();
        }
    
        return view('backend.admin_profile.report.report_card', [
            'settings' => SchoolSetting::first(),
            'students' => $students,
            'term' => $term,
            'session' => $session,
            'class' => $class,
            'nextTermBegins' => $nextTermBegins,
            //'selected_class_id' => $class,
            //'selected_term' => $term,
            //'selected_session' => $session,

        ]);

    } //end method



    //SINGLE STUDENT RESULT
    public function SingleStudentReport($student_id, $class_id, $term_id, $session_id)
{
    $class = Classes::findOrFail($class_id);
    $term = Term::findOrFail($term_id);
    $session = AcademicSession::findOrFail($session_id);

    $student = Student::findOrFail($student_id);

    // Fetch student's results
    $results = Result::where([
        'student_id' => $student->id,
        'term' => $term->name,
        'session' => $session->name,
    ])->get();

    // compute summary
    $grandTotal = $results->sum('total');
    $subjectCount = $results->count();
    $percentage = $subjectCount > 0 ? round($grandTotal / $subjectCount) : 0;

    $remark = match (true) {
        $percentage >= 70 => 'EXCELLENT',
        $percentage >= 60 => 'GOOD',
        $percentage >= 50 => 'CREDIT',
        $percentage >= 45 => 'PASS',
        $percentage >= 40 => 'WEAK PASS',
        default => 'FAIL',
    };
    
    // Psychomotor / affective
    $psychomotor = PsychoAssessment::where([
        'student_id' => $student->id,
        'class_id' => $class->id,
        'term' => $term->name,
        'session' => $session->name,
    ])->first();

    // Clearance status
    $clearance = Clearance::where([
        'student_id' => $student->id,
        'class_id' => $class->id,
        'term' => $term->name,
        'session' => $session->name,
    ])->first();

    $student->report_class = $class->class_name;
    $student->score_summary = [
        'score' => $grandTotal,
        'percentage' => $percentage,
        'remark' => $remark
    ];
    $student->psychomotor = $psychomotor;
    $student->clearance = $clearance;

    // Calendar
    $calendar = TermCalendar::where([
        'term' => $term->name,
        'session' => $session->name,
    ])->first();

    return view('backend.admin_profile.report.single_report_card', [
        'settings' => SchoolSetting::first(),
        'student' => $student,
        'results' => $results,
        'term' => $term,
        'session' => $session,
        'class' => $class,
        'nextTermBegins' => $calendar?->next_term_begins,
    ]);
}


     
}
