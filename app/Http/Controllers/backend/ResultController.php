<?php

namespace App\Http\Controllers\backend;

use App\Models\terms;
use App\Models\Result;
use App\Models\classes;
use App\Models\student;
use App\Models\subject;
use Illuminate\Http\Request;
use App\Models\academic_session;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\AssignClassSubjectStudent;
use App\Models\AssignedClassSubjectTeacher;

class ResultController extends Controller
{
    public function showSelectedForm(){

        //get the logged in (authenticated) users id  
       $teacherId = Auth::user()->teacher->id;

       //Get all the classes assigned to this teacher
       $assignments = AssignedClassSubjectTeacher::
       where('teacher_id', $teacherId)
       ->pluck('class_id')
       ->unique();
          
        //fetch the actual class and subject record
        $classes = classes::
        whereIn('id', $assignments)->get();

        //Fetch only the current term and session (admin controlled)
        $terms = terms::where('is_current', true)->get();
        $sessions = academic_session::where('is_current', true)->get();

        //dd($assignments->pluck('subject_id'));
        //dd( $subjects);
        
        return view('backend.teacher_account.select_result', compact('classes', 'terms', 'sessions'));
    
    }//end method



    //Ajax GET SUBJECT IN SELECT FORM FOR RESULT
    public function getSubjectByClass(Request $request){

        //get the logged in (authenticated) users id  
        $teacherId = Auth::user()->teacher->id;
        
        $classId = $request->input('class_id');
    
        $subjectIds = AssignedClassSubjectTeacher::
        where('teacher_id', $teacherId)
        ->where('class_id', $classId)
        ->pluck('subject_id');


        $subjects = subject::whereIn('id', $subjectIds)->get();
        return response()->json($subjects);
    }//end method


    public function LoadResult(Request $request){
    
        //validate the request
        $request->validate([
            'class_id' => 'required|integer',
            'subject_id' => 'required|integer',
            'term' => 'required|string',
            'session' => 'required|string',
        ]);


        //Fetch students for this class_subject assignment
        $students = AssignClassSubjectStudent::
          where([
         'class_id' => $request->class_id,
         'subject_id' => $request->subject_id,
         'term' => $request->term,
         'session' => $request->session,
          ])->with('student')->get()->pluck('student');
        

        //Get existing result if any
        $existingResults = Result::
        where([
            'class_id' => $request->class_id,
            'subject_id' => $request->subject_id,
            'term' => $request->term,
            'session' => $request->session,
             ])->get()->keyBy('student_id');

             $classes = classes::find($request->class_id);
             $subjects = subject::find($request->subject_id);

        return view('backend.teacher_account.result_upload', [
            'students' => $students,
            'existingResults' => $existingResults,
            'class_id' => $request->class_id,
            'subject_id' => $request->subject_id,
            'term' => $request->term,
            'session' => $request->session,
            'classes' => $classes,
            'subjects' => $subjects,
        ]);

    }//end method


    /*public function SaveResultRow(Request $request){
         //validate the request
         $request->validate([

            'student_id' => 'required|integer|exists:students,id',
            'class_id' => 'required|integer',
            'subject_id' => 'required|integer',
            'term' => 'required|string',
            'session' => 'required|string',
            'ca1' =>  'required|numeric|min:0|max:20',
            'ca2' =>  'required|numeric|min:0|max:20',
            'exam' =>  'required|numeric|min:0|max:60',
        ]);

        $total = $request->ca1 + $request->ca2 + $request->exam;
        $grade = match(true){
            $total >= 70 =>'A', 
            $total >= 60 =>'B',
            $total >= 50 =>'c',
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
            'student_id' =>$request->student_id,
            'class_id' =>$request->class_id,
            'subject_id' =>$request->subject_id,
            'term' =>$request->term,
            'session' =>$request->session,
        ],
            
            [
                'ca1' =>$request->ca1,
                'ca2' =>$request->ca2,
                'exam' =>$request->exam,
                'total' =>$request->total,
                'grade' =>$request->grade,
                'remark' =>$request->remark,

        ]);

        return response()->json([
            'total' => $total,
            'garde' => $grade,
            'remark' => $remake,
            'status' => 'saved',
        ]);

    }// end method */


    public function StoreResults(Request $request){

        
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
       

        foreach($request->results as $result){


            $total = $result['ca1'] + $result['ca2'] + $result['exam'];
        
            $grade = trim(strval(match(true){
                $total >= 70 =>'A', 
                $total >= 60 =>'B',
                $total >= 50 =>'C',
                $total >= 45 =>'D',
                $total >= 40 =>'E',
                default=> 'F',
            }));
          
            $remark = match($grade){
                'A' => 'Excellent',
                'B' => 'Good',
                'C' => 'Credit',
                'D' => 'Pass',
                'E' => 'Weak Pass',
                default=> 'Fail',
            };


            Result::updateOrCreate([
                'student_id' => $result['student_id'],
                'class_id' => $request->class_id,
                'subject_id' => $request->subject_id,
                'term' => $request->term,
                'session' => $request->session,
            ],
                
                [
                    'ca1' =>$result['ca1'],
                    'ca2' =>$result['ca2'],
                    'exam' =>$result['exam'],
                    'total' =>$total,
                    'grade' =>$grade,
                    'remark' =>$remark,
    
            ]);
        }

        $notification = array(
            'message' => ' Result Uploaded Successfully',
            'alert-type' => 'info'
        );

        //redirect back to same page

  return redirect()->back()->with($notification);

    }//end method






//ADMIN RESULT UPLOAD METHOD
    public function showSelectedAdminForm(){

       $classes = classes::all();

       $terms = terms::where('is_current', true)->get();
       $sessions = academic_session::where('is_current', true)->get();
       return view('backend.admin_profile.admin.upload_result.admin_result_upload', compact('classes','terms', 'sessions'));
   }//end method



   public function LoadAdminResultsTable(Request $request){
    $terms = terms::where('is_current', true)->firstOrFail();
    $sessions = academic_session::where('is_current', true)->firstOrFail();
    $classId = $request->class_id;

    //1. GET STUDENTS IDS IN THIS CLASS
    $studentIds = AssignClassSubjectStudent::where('class_id', $classId )
    ->where('term', $terms->name)
    ->where('session', $sessions->name)
    ->pluck('student_id')
    ->unique();

    //2. Get actual student records

    $students = student::whereIn('id', $studentIds)
    ->orderBy('name')->get();

    //3.subject IDs offered in that class this term/session
    
    $subjectIds = AssignClassSubjectStudent::where('class_id', $classId)
    ->where('term', $terms->name)
    ->where('session', $sessions->name)
    ->pluck('subject_id')
    ->unique();


    //4. Get actual subject records

    $subjects = subject::whereIn('id', $subjectIds)
    ->orderBy('subject_name')->get();

    //5. existing results, grouped key => subjectid_studentid

    $results = Result::where('class_id', $classId)
    ->where('term', $terms->name)
    ->where('session', $sessions->name)
    ->get()
    ->groupBy(fn($r)=>$r->subject_id.'_'.$r->student_id);


    //6. 
    $assignments = AssignClassSubjectStudent::where('class_id', $classId)
    ->where('term', $terms->name)
    ->where('session', $sessions->name)
    ->get()
    ->groupBy(fn($r)=>$r->subject_id.'_'.$r->student_id);

    //7. Get class name 
    $classes = classes::find($request->class_id);

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
 $exam = is_numeric($row['exam'] ?? null) ? $row['exam'] : 0;

 $total = $ca1 + $ca2 + $exam;
   
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

