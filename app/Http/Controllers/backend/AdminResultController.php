<?php

namespace App\Http\Controllers\backend;

use App\Models\Term;
use App\Models\Result;
use App\Models\Classes;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use App\Models\AcademicSession;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\AssignClassSubjectStudent;
use App\Models\AssignedClassSubjectTeacher;

class AdminResultController extends Controller
{
    //ADMIN RESULT UPLOAD METHOD
    public function showSelectedAdminForm(){

        $classes = Classes::all();
 
        $terms = Term::where('is_current', true)->get();
        $sessions = AcademicSession::where('is_current', true)->get();
        return view('backend.admin_profile.admin.upload_result.admin_result_upload', compact('classes','terms', 'sessions'));
    }//end method
 
 
 
    public function LoadAdminResultsTable(Request $request){
     $terms = $request->term;
     $sessions = $request->session;
     $classId = $request->class_id;
 
     //1. GET STUDENTS IDS IN THIS CLASS
     $studentIds = AssignClassSubjectStudent::where('class_id', $classId )
     ->where('term', $terms)
     ->where('session', $sessions)
     ->pluck('student_id')
     ->unique();
 
     //2. Get actual student records
 
     $students = Student::whereIn('id', $studentIds)
     ->orderBy('name')->get();
 
     //3.subject IDs offered in that class this term/session
     
     $subjectIds = AssignClassSubjectStudent::where('class_id', $classId)
     ->where('term', $terms)
     ->where('session', $sessions)
     ->pluck('subject_id')
     ->unique();
 
 
     //4. Get actual subject records
 
     $subjects = Subject::whereIn('id', $subjectIds)
     ->orderBy('subject_name')->get();
 
 
     $assignments = AssignClassSubjectStudent::where('class_id', $classId)
     ->where('term', $terms)
     ->where('session', $sessions)
     ->get()
     ->groupBy(fn($r)=>$r->subject_id.'_'.$r->student_id);
 
     //7. Get class name 
     $classes = Classes::find($request->class_id);
 
 
     //5. existing results, grouped key => subjectid_studentid
 
     $results = Result::where('class_id', $classId)
     ->where('term', $terms)
     ->where('session', $sessions)
     ->get()
     ->groupBy(fn($r)=>$r->subject_id.'_'.$r->student_id);
     
     //if no result found
     if($assignments->isEmpty()){
         return back()->with('error', 'No record found for the selected Class, Term, and Session.');
     }
 
     return view('backend.admin_profile.admin.upload_result.upload_result_table', 
     compact('subjects', 'students', 'results', 'classes', 'terms', 'sessions', 'classId', 'assignments'));
    }//end method
 
 
 
 
    public function StoreAdminResultsTable(Request $request){
 
  /*       
     //validate the request
     $request->validate([
 
        'results' => 'required|array',
        'results.*.student_id' => 'required|integer|exists:students,id',
        'results.*.ca1' => 'nullable|numeric|min:0|max:20',
        'results.*.ca2' => 'nullable|numeric|min:0|max:20',
        'results.*.exam' => 'nullable|numeric|min:0|max:60',
        'subject_id' => 'required|integer',
        'term' => 'required|string',
        'session' => 'required|string',
 
 
    ]);
    */
 
    $request->validate([
 
     'results' => 'required|array',
    ]);
   
 
    foreach($request->results as $studentId => $rows){
     
     foreach($rows as $row){
     
         
 if(empty($row['student_id'])){
     continue;
 }
  
 
  $ca1 = is_numeric($row['ca1'] ?? null) ? $row['ca1'] : 0;
  $ca2 = is_numeric($row['ca2'] ?? null) ? $row['ca2'] : 0;
  $ca3 = is_numeric($row['ca3'] ?? null) ? $row['ca3'] : 0;
  $exam = is_numeric($row['exam'] ?? null) ? $row['exam'] : 0;
 
  $total = $ca1 + $ca2 + $ca3 + $exam;
    
        $grade = match(true){
            $total >= 70 =>'A', 
            $total >= 60 =>'B',
            $total >= 50 =>'C',
            $total >= 45 =>'D',
            $total >= 40 =>'E',
            default=> 'F',
        };
      
        $remark = match($grade){
            'A' => 'Excellent',
            'B' => 'Good',
            'C' => 'Credit',
            'D' => 'Pass',
            'E' => 'Weak Pass',
            default=> 'Fail',
        };
 
 
        Result::updateOrCreate([
            'student_id' => $row['student_id'],
            'subject_id' => $studentId,
            'class_id' => $request->class_id,
            'term' => $request->term,
            'session' => $request->session,
        ],
            
            [
                'ca1' =>$ca1,
                'ca2' =>$ca2,
                'ca3' =>$ca3,
                'exam' =>$exam,
                'total' =>$total,
                'grade' =>$grade,
                'remark' =>$remark,
 
        ]);
     }
 
 
        
    }
 
    $notification = array(
        'message' => ' Result Uploaded Successfully',
        'alert-type' => 'info'
    );
 
    //redirect back to same page
 
 return redirect()->back()->with($notification);
 
 }//end method
 
}
