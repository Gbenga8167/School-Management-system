<?php

namespace App\Http\Controllers\backend;

use App\Models\classes;
use App\Models\subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SubjectController extends Controller
{
    public function CreateSubject(){
        return view('backend.subjects.create_subject');
    }// end method


    public function StotreSubject(Request $request){

        //validate user input
        $request->validate([

            'subject_name' => 'required',
            'status' => 'required',
        ]);
        //second method of inserting into database

        $AlreadyExist = subject::where('subject_name', $request->subject_name)->first();
        if($AlreadyExist){

                 $notification = array(
                'message' => ' Subject Already exist',
                'alert-type' => 'info'
            );

            //redirect back to same page
  
      return redirect()->back()->with($notification);
            

        }else{

            subject::create([
                'subject_name' => $request->subject_name,
                'status' => $request->status
        
                 //end insert
               ]);
        
              
        
               $notification = array(
                'message' => 'Subject Created Succesfully',
                'alert-type' => 'info'
            );
        
            //redirect back to same page
        
            return redirect()->route('manage.subject')->with($notification);
        
        }
    }//end method

    public function ManageSubject(){

        $subjects = subject::all();
        return view('backend.subjects.manage_subject', compact('subjects'));

    }// end method


    public function EditSubject($id){

        $subject = subject::find($id);
        return view('backend.subjects.edit_subject', compact('subject'));

    }// end method

    public function UpdateSubject(Request $request){

        //validate user input
        $request->validate([

            'subject_name' => 'required',
            'status' => 'required',
        ]);

        $id = $request->id;
        subject::find( $id)->update([
            'subject_name' => $request->subject_name,
            'status' => $request->status
        ]);

        $notification = array(
            'message' => 'Subject Updated Succesfully',
            'alert-type' => 'info'
        );
    
        //redirect back to same page
    
        return redirect()->route('manage.subject')->with($notification);

    }//end method

    public function DeleteSubject($id){
        subject::find( $id)->delete();

             $notification = array(
            'message' => ' Subject Deleted Succesfully',
            'alert-type' => 'info'
        );
    
        //redirect back to same page
    
        return redirect()->route('manage.subject')->with($notification);
    }// end method



     // Subject Combination All Method

     public function AddSubjectCombination(){
        $subjects = subject::all();
        $classes = classes::all();

        return view('backend.subjects.add_subject_combination', compact('subjects', 'classes'));
    }//end method



    public function StoreSubjectCombination(Request $request){


        $AlreadyExist = DB::table("classes_subject")->where('classes_id', $request->class_id)->where('subject_id', $request->subject_ids)->first();
        if($AlreadyExist){
                $notification = array(
                'message' => ' Subject Combination Already exist',
                'alert-type' => 'info'
            );

            //redirect back to same page
  
      return redirect()->back()->with($notification);
            

        }else{

            $class = classes::find($request->class_id);
            $subject = $request->subject_ids;
            $class->subjects()->attach($subject);
  
            $notification = array(
          'message' => ' Combination Done Succesfully',
          'alert-type' => 'info'
      );
  
      //redirect back to same page
  
      return redirect()->back()->with($notification);
            
        }


        
      }//end method


      public function ManageSubjectCombination(){

        //creating a relationship btw classes_suject(pivot-table),subject tables and classes tables
        
                $results = DB::table('classes_subject')
                           ->join('classes', 'classes_subject.classes_id', 'classes.id')
                           ->join('subjects', 'classes_subject.subject_id', 'subjects.id')
                           ->select(
                            'classes_subject.*',
                            'classes.class_name',
                            'subjects.subject_name'
                           )
                           ->get();
        
                           return view('backend.subjects.manage_subject_combination', compact('results'));
        
            }//end method



            public function DeactivateSubjectCombination($id){
                $status = DB::table("classes_subject")->select('status')->where('id', $id)->first();
                if($status->status == 1){
                    DB::table("classes_subject")->where('id', $id)->update([
                        'status' => 0
            
                    ]);
                    $notification = array(
                        'message' => ' Subject De-activated Succesfully',
                        'alert-type' => 'info'
                    );
                
                    //redirect back to same page
                
                    return redirect()->back()->with($notification);
            
                }elseif($status->status == 0){
                    DB::table("classes_subject")->where('id', $id)->update([
                        'status' => 1
            
                    ]);
                    $notification = array(
                        'message' => ' Subject Activated Succesfully',
                        'alert-type' => 'info'
                    );
                
                    //redirect back to same page
                
                    return redirect()->back()->with($notification);
                }
            
            
            
               }//end method

             

              
}
