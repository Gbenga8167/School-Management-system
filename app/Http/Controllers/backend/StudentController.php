<?php

namespace App\Http\Controllers\backend;

use App\Models\User;
use App\Models\classes;
use App\Models\student;
use App\Models\subject;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\AssignClassSubjectStudent;

class StudentController extends Controller
{
    public function AddStudent(){
        $classes = classes::all();
        return view('backend.student.add_student_view', compact('classes'));
    }//end method


public function StoreStudent(Request $request)
{

/*
 //Step 1: Validate the input
    $request->validate([
        'full_name' => 'required|string|max:255',
        'email' => ['required', 'email', Rule::unique('users', 'email')],
        'password' => 'required|min:6',
        'roll_id' => 'required',
        'class_id' => 'required',
        'dob' => 'required|date',
        'gender' => 'required',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    


    

    // To disallow Duplication of Email

    $AlreadyEmail = User::where('email', $request->email)->first();
    if($AlreadyEmail){

             $notification = array(
            'message' => ' Email Already exist',
            'alert-type' => 'info'
        );

        //redirect back to same page

  return redirect()->back()->with($notification);
        

    }



         // To disallow multi-User roll-id

    $AlreadyExist = student::where('roll_id', $request->roll_id)->first();
    if($AlreadyExist){

             $notification = array(
            'message' => ' Student Roll Id Already exist',
            'alert-type' => 'info'
        );

        //redirect back to same page

  return redirect()->back()->with($notification);
        

    }

*/

    // To disallow Duplication of Email

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


    


    // Step 2: Create User
    $user = User::create([
        'name' => $request->full_name,
        'password' => Hash::make($request->password),
        'email' => $request->email,
        'role' => $request->role,
        'user_name' => $request->username,
    ]);

    
    // Step 3: If student, create student record
    if ($user->role == 3) {
        $student = $user->student()->create([
            'name' => $request->full_name,
            'roll_id' => $request->roll_id,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'parent_name' => $request->parent_name,
            'parent_occupation' => $request->parent_occupation,
            'parent_gender' => $request->parent_gender,
            'State_of_origin' => $request->State_of_origin,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'nationality' => $request->nationality,

            
        ]);

        
    }

            //checking if admin is also updating his profile photo along with other data
            if( $request->hasFile('photo')){
    
                //save the request photo in a variable
                $file = $request->file('photo');
        
                //update the admin profile image in the image folder directory, to avoid show previous image repeatedly
                @unlink(public_path('uploads/student_photos/'.$student->photo));
        
                //generating unique name for the image 
                $imageName = date('YmdHi'). '.' .$file->getClientOriginalName(); // sample-> 20250118.pic_name.png
        
                //move the photo to the uploads directory
                $file->move(public_path('uploads/student_photos'), $imageName);
        
                //save new admin profile image in the database
                $student['photo'] = $imageName;

                $student->save();
        
            }
            //save data
    $student->save();


    // Step 5: Redirect with notification
    $notification = [
        'message' => 'Student Added Successfully!',
        'alert-type' => 'success',
    ];

    return redirect()->route('manage.student')->with($notification);
}




     public function ManageStudent(){
        $students = student::all();
        return view('backend.student.manage_student', compact('students'));
    }//end method

    public function EditStudent($id){
        $students= student::find($id);
        $classes = classes::all();
        return view('backend.student.edit_student_view', compact('students', 'classes'));



    }// end method

    public function UpdateStudent(Request $request){
      $id = $request->id;
      $student = student::find($id);
      $student->name = $request->full_name;
       $student->roll_id = $request->roll_id;
       $student->dob = $request->dob;
       $student->gender = $request->gender;
       $student->status = $request->status;
       $student->parent_name = $request->parent_name;
       $student->parent_occupation = $request->parent_occupation;
       $student->parent_gender = $request->parent_gender;
       $student->State_of_origin = $request->State_of_origin;
       $student->phone_number = $request->phone_number;
       $student->address = $request->address;
       $student->nationality = $request->nationality;



       //checking if admin is also updating his profile photo along with other data
    if( $request->hasFile('photo')){

        //save the request photo in a variable
        $file = $request->file('photo');

         //update the profile image in the image folder directory, to avoid show previous image repeatedly
         @unlink(public_path('uploads/student_photos/'.$student->photo));

        //generating unique name for the image 
        $imageName = date('YmdHi'). '.' .$file->getClientOriginalName(); // sample-> 20250118.pic_name.png

        //move the photo to the uploads directory
        $file->move(public_path('uploads/student_photos'), $imageName);

        //save new admin profile image in the database
        $student['photo'] = $imageName;


    }
      //save data
      $student->save();
  

    $notification = array(
        'message' => 'Student Updated Successfully!',
        'alert-type' => 'success'
    );

    //redirect back to same page
 
    return redirect()->route('manage.student')->with($notification);
    }// end method

    public function DeleteStudent($id){
        
        
        $student = student::find($id);
        @unlink(public_path('uploads/student_photos/'.$student->photo));
        $student->Delete();

        if($student->user){
            $student->user->delete();
        }
        $student->delete();

        $notification = array(
            'message' => 'Student Deleted Successfully!',
            'alert-type' => 'info'
        );
    
        //redirect back to same page
     
        return redirect()->route('manage.student')->with($notification);


    }

    //ASSIGN STUDENT CLASS SUBJECT
    public function AssignStudentClassSubject(){
        $students = student::all();
        $subjects = subject::all();
        $classes  = classes::all();
        
        return view('backend.student.assign_student_class_subject', compact('students', 'subjects', 'classes'));
    }//end method


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


    // store subject teacher

    public function StoreStudentClassSubject(Request $request){

        $AlreadyExist =  AssignClassSubjectStudent::where('student_id', $request->student_id)
        ->where('class_id', $request->class_id)
        ->where('subject_id', $request->subject_ids)
        ->first();


        if($AlreadyExist){

                 $notification = array(
                'message' => ' Details Already exist',
                'alert-type' => 'info'
            );

            //redirect back to same page
  
      return redirect()->back()->with($notification);
            

        }else{
            
        $request->validate([

            'student_id' => 'required',
            'class_id' => 'required',
            'subject_ids' => 'required|array',
            'session' => 'required|string',
            'term' => 'required'
        ]);

        $sub_count = count($request->subject_ids);
        for($i=0; $i < $sub_count; $i++){

            AssignClassSubjectStudent::create([
                'student_id' => $request->student_id, 
                'subject_id' => $request->subject_ids[$i],
                 'class_id' =>$request->class_id, 
                 'session' =>$request->session,
                 'term' => $request->term
    
            ]);
        }
       


        $notification = array(
            'message' => 'Assigned Succesfully',
            'alert-type' => 'info'
        );
    
        //redirect back to same page
    
        return redirect()->back()->with($notification);
    
       }

    }
// end method



public function ManageAssignStudentClassSubject(){

    $manageAssigns = AssignClassSubjectStudent::with(['student', 'subject', 'class'])
    ->orderBy('student_id')
    ->get();


    return view('backend.student.manage_assign_student_class_subject', compact('manageAssigns'));
  
}
// end method


public function EditAssignStudentClassSubject($id){
    $AssignSubjectstudent = AssignClassSubjectStudent::findOrFail($id);
    $students = student::all();
    $subjects = subject::all();
    $classes  = classes::all();

    return view('backend.student.edit_assign_student_class_subject', compact('AssignSubjectstudent', 'students', 'subjects', 'classes'));

}//end method


public function UpdateAssignStudentClassSubject(Request $request){

    $id = $request->id;

    $request->validate([
        'student_id' => 'required',
        'class_id' => 'required',
        'subject_id' => 'required',
        'term' => 'required',
        'session' => 'required',
    ]);

    AssignClassSubjectStudent::findOrFail($id)->update([

        'student_id' => $request->student_id,
        'class_id' => $request->class_id,
        'subject_id' => $request->subject_id,
        'term' => $request->term,
        'session' => $request->session,
    ]);

    $notification = array(
        'message' => 'Update Succesful',
        'alert-type' => 'info'
    );

    //redirect back to same page

    return redirect()->route('manage.assign.student.class.subject')->with($notification);

}// end method

public function DeleteAssignStudentClassSubject($id){

    AssignClassSubjectStudent::findOrFail($id)->delete();
         $notification = array(
        'message' => ' Class Subject Assigned Deleted Succesfully',
        'alert-type' => 'info'
    );

    //redirect back to same page

    return redirect()->route('manage.assign.student.class.subject')->with($notification);


}


 
}
