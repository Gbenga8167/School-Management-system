<?php

namespace App\Http\Controllers\backend;

use App\Models\User;
use App\Models\classes;
use App\Models\subject;
use App\Models\teacher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\AssignedClassSubjectTeacher;

class TeacherController extends Controller
{
    public function AddTeacher(){
        return view('backend.teacher.add_teachers');

    }// end method



    public function StoreTeacher(Request $request){

        $AlreadyEmail = User::where('email', $request->email)->first();
        if($AlreadyEmail){
    
                 $notification = array(
                'message' => ' Email Already exist',
                'alert-type' => 'info'
            );
    
            //redirect back to same page
    
      return redirect()->back()->with($notification);
            
    
        }



        $AlreadyExist = User::where('user_name', $request->username)->first();
        if($AlreadyExist){
    
                 $notification = array(
                'message' => ' Username Already exist',
                'alert-type' => 'info'
            );
    
            //redirect back to same page
    
      return redirect()->back()->with($notification);
            
    
        }
    
        


        // Create User
    $user = User::create([
        'name' => $request->full_name,
        'password' => Hash::make($request->password),
        'email' => $request->email,
        'role' => $request->role,
        'user_name' => $request->username,
    ]);



    // Step 3: If student, create student record
    if ($user->role == 2) {
        $teacher = $user->teacher()->create([
            'name' => $request->full_name,
            'address' => $request->address,
            'nationality' => $request->nationality,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'qualification' => $request->qualification,
            'work_experience' => $request->experience,
            'marital_status' => $request->marital,
            'State_of_origin' => $request->State_of_origin,
            'phone_number' => $request->phone_number,
  

            
        ]);

        
    }

            //checking if admin is also updating his profile photo along with other data
            if( $request->hasFile('photo')){
    
                //save the request photo in a variable
                $file = $request->file('photo');
        
                //update the admin profile image in the image folder directory, to avoid show previous image repeatedly
                @unlink(public_path('uploads/teachers_photos/'.$teacher->photo));
        
                //generating unique name for the image 
                $imageName = date('YmdHi'). '.' .$file->getClientOriginalName(); // sample-> 20250118.pic_name.png
        
                //move the photo to the uploads directory
                $file->move(public_path('uploads/teachers_photos'), $imageName);
        
                //save new admin profile image in the database
                $teacher['photo'] = $imageName;

                $teacher->save();
        
            }
            //save data
     $teacher->save();
 
     $notification = array(
         'message' => 'Teacher Added Successfully!',
         'alert-type' => 'success'
     );
 
     //redirect back to same page
  
     return redirect()->route('manage.teacher')->with($notification);
 
 
     }//end method


     public function ManageTeacher(){
        $teachers = teacher::all();
        return view('backend.teacher.manage_teacher', compact('teachers'));
    }//end method


    public function EditTeacher($id){
        $teachers = teacher::find($id);
        return view('backend.teacher.edit_teacher', compact('teachers'));

    }//end method


    public function UpdateTeacher(Request $request){
        $id = $request->id;
        $teacher = teacher::find($id);
        $teacher->name = $request->full_name;
        $teacher->address = $request->address;
        $teacher->nationality = $request->nationality;
        $teacher->phone_number = $request->phone_number;
        $teacher->qualification = $request->qualification;
        $teacher->work_experience = $request->experience;
        $teacher->marital_status = $request->marital;
        $teacher->state_of_origin = $request->State_of_origin;
        $teacher->dob = $request->dob;
        //checking if admin is also updating his profile photo along with other data
     if( $request->hasFile('photo')){
 
         //save the request photo in a variable
         $file = $request->file('photo');
 
         //generating unique name for the image 
         $imageName = date('YmdHi'). '.' .$file->getClientOriginalName(); // sample-> 20250118.pic_name.png
 
         //move the photo to the uploads directory
         $file->move(public_path('uploads/teachers_photos'), $imageName);
 
         //save new admin profile image in the database
         $teacher['photo'] = $imageName;
 
     }
     $teacher->gender = $request->gender;
    
     //save data
     $teacher->save();


 
 
     $notification = array(
         'message' => 'Teacher Updated Successfully!',
         'alert-type' => 'success'
     );
 
  
     return redirect()->route('manage.teacher')->with($notification);
 
 
     }//end Method


     public function DeleteTeacher($id){
        $teacher = teacher::find($id);
        @unlink(public_path('uploads/teachers_photos/'.$teacher->photo));
        $teacher->delete();

        //delete teachers and the related user
        if($teacher->user){
            $teacher->user->delete();
        }
        $teacher->delete();

        
        $notification = array(
            'message' => 'Teacher Deleted Successfully!',
            'alert-type' => 'info'
        );
    
        //redirect back to same page
     
        return redirect()->route('manage.teacher')->with($notification);


    }


    public function AssignSubjectTeacher(){

        $teachers = teacher::all();
        $subjects = subject::all();
        $classes  = classes::all();
        
        return view('backend.teacher.assign_teacher_subject', compact('teachers', 'subjects', 'classes'));

    }// end method

// FetchStudent class from database
    public function FetchStudent(Request $request){
        $class_id = $request->class_id;
        $class = classes::with('subjects')->where('id', $class_id)->first();
        $class_subjects = $class->subjects;
        for($i=0; $i < count($class_subjects); $i++){
            $subject_data[$i] = 

            '<input class="form-check-input" name="subject_ids[]" value="'.$class_subjects[$i]->id.'" type="checkbox" id="formCheck1"></label>
            <label for="english">'. $class_subjects[$i]->subject_name.'</label> <br>';
        }

return response()->json(['subjects'=>$subject_data]);

    }
 
    //end method
    

// store subject teacher

    public function StoreAssignSubjectTeacher(Request $request){


        $AlreadyExist =  AssignedClassSubjectTeacher::where('teacher_id', $request->teacher_id)
        ->where('class_id', $request->class_id)
        ->where('subject_id', $request->subject_ids)
        ->first();


        if($AlreadyExist){

                 $notification = array(
                'message' => ' Assign Subject Class Teacher Already exist',
                'alert-type' => 'info'
            );

            //redirect back to same page
  
      return redirect()->back()->with($notification);
            

        }else{
            
        $request->validate([

            'teacher_id' => 'required',
            'class_id' => 'required',
            'subject_ids' => 'required|array',
            'session' => 'required|string',
            'term' => 'required'
        ]);

        $sub_count = count($request->subject_ids);
        for($i=0; $i < $sub_count; $i++){

            AssignedClassSubjectTeacher::create([
                'teacher_id' => $request->teacher_id, 
                'subject_id' => $request->subject_ids[$i],
                 'class_id' =>$request->class_id, 
                 'session' =>$request->session,
                 'term' => $request->term
    
            ]);
        }
       


        $notification = array(
            'message' => 'Subject Teacher Assigned Succesfully',
            'alert-type' => 'info'
        );
    
        //redirect back to same page
    
        return redirect()->back()->with($notification);
    
       }

    }
// end method

public function ViewAssignSubjectTeacher(){

    $manageAssigns = AssignedClassSubjectTeacher::with(['teacher', 'subject', 'class'])
    ->orderBy('teacher_id')
    ->get();


    return view('backend.teacher.manage_assign_subject_teacher', compact('manageAssigns'));
  
}
// end method


public function EditAssignSubjectTeacher($id){
    $AssignSubjectTeacher = AssignedClassSubjectTeacher::findOrFail($id);
    $teachers = teacher::all();
    $subjects = subject::all();
    $classes  = classes::all();

    return view('backend.teacher.edit_assign_subject_teacher', compact('AssignSubjectTeacher', 'teachers', 'subjects', 'classes'));

}//end method


public function UpdateAssignSubjectTeacher(Request $request){

    $id = $request->id;

    $request->validate([
        'teacher_id' => 'required',
        'class_id' => 'required',
        'subject_id' => 'required',
        'term' => 'required',
        'session' => 'required',
    ]);

    AssignedClassSubjectTeacher::findOrFail($id)->update([

        'teacher_id' => $request->teacher_id,
        'class_id' => $request->class_id,
        'subject_id' => $request->subject_id,
        'term' => $request->term,
        'session' => $request->session,
    ]);

    $notification = array(
        'message' => 'Subject Teacher Assigned Updated Succesfully',
        'alert-type' => 'info'
    );

    //redirect back to same page

    return redirect()->route('manage.assign.subject.teacher')->with($notification);

}// end method

public function DeleteAssignSubjectTeacher($id){
    AssignedClassSubjectTeacher::findOrFail($id)->delete();

         $notification = array(
        'message' => ' Subject Teacher Assigned Deleted Succesfully',
        'alert-type' => 'info'
    );

    //redirect back to same page

    return redirect()->route('manage.assign.subject.teacher')->with($notification);


}

}