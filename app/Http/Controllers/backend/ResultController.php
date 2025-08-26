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

             if($students->isEmpty()){
                return back()->with('error', 'No record found for the selected Class, Term, and Session.');
            }
        

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

    public function StoreResults(Request $request){

        
         //validate the request
         $request->validate([

            'results' => 'required|array',
            'results.*.student_id' => 'required|integer|exists:students,id',
            'results.*.ca1' => 'nullable|numeric|min:0|max:10',
            'results.*.ca2' => 'nullable|numeric|min:0|max:10',
            'results.*.ca3' => 'nullable|numeric|min:0|max:10',
            'results.*.exam' => 'nullable|numeric|min:0|max:70',
            'subject_id' => 'required|integer',
            'term' => 'required|string',
            'session' => 'required|string',


        ]);
       

        foreach($request->results as $result){


            $total = $result['ca1'] + $result['ca2'] + $result['ca3'] + $result['exam'];
        
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
                    'ca3' =>$result['ca3'],
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

    
}

