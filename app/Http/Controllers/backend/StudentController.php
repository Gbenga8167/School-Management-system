<?php

namespace App\Http\Controllers\backend;

use App\Models\User;
use App\Models\Term;
use App\Models\AcademicSession;
use App\Models\Classes;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\AssignClassSubjectStudent;

class StudentController extends Controller
{
    public function AddStudent(){
        $classes = Classes::all();

        //AUTO STUDENT ID GENERATO
            $year = date('y'); // e.g. 25 for 2025
            // Get last roll_id for this year
                $lastStudent = Student::where('roll_id', 'like', "AGM/$year/%")
                          ->orderBy('id', 'desc')
                          ->first();
          
               if ($lastStudent) {
                   $lastSeq = (int) substr($lastStudent->roll_id, strrpos($lastStudent->roll_id, '/') + 1);
                   $nextSeq = $lastSeq + 1;
               } else {
                   $nextSeq = 1;
               }

               $nextRollId = "AGM/$year/" . str_pad($nextSeq, 4, '0', STR_PAD_LEFT);


        return view('backend.student.add_student_view', compact('classes', 'nextRollId'));
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
    ]);  */



         // To disallow multi-User roll-id

    $AlreadyExist = Student::where('roll_id', $request->roll_id)->first();
    if($AlreadyExist){

             $notification = array(
            'message' => ' Student Roll Id Already exist',
            'alert-type' => 'info'
        );

        //redirect back to same page

  return redirect()->back()->with($notification);
        

    }


          $year = date('y'); // e.g. 25 for 2025

           // Get last roll_id for this year
           $lastStudent = Student::where('roll_id', 'like', "AGM/$year/%")
                                 ->orderBy('id', 'desc')
                                 ->first();
           
           if ($lastStudent) {
               // Extract the sequence number (after last slash)
               $lastSeq = (int) substr($lastStudent->roll_id, strrpos($lastStudent->roll_id, '/') + 1);
               $nextSeq = $lastSeq + 1;
           } else {
               $nextSeq = 1; // First student of the year
           }
           
           $roll_id = "AGM/$year/" . str_pad($nextSeq, 4, '0', STR_PAD_LEFT);




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
            'roll_id' => $roll_id, // ✅ auto-generated
            'dob' => $request->dob,
            'gender' => $request->gender,
            'parent_name' => $request->parent_name,
            'parent_occupation' => $request->parent_occupation,
            'parent_gender' => $request->parent_gender,
            'State_of_origin' => $request->State_of_origin,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'nationality' => $request->nationality,
            'class_id' => $request->class_id,

            
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




public function ManageStudent()
{
    $students = Student::orderBy('id', 'desc')->get();
    return view('backend.student.manage_student', compact('students'));
}


    public function EditStudent($id){
        $students= Student::find($id);
        $classes = Classes::all();
        return view('backend.student.edit_student_view', compact('students', 'classes'));



    }// end method

    public function UpdateStudent(Request $request){
      $id = $request->id;
      $student = Student::find($id);
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
       $student->class_id = $request->class_id;



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
        
        
        $student = Student::find($id);
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
   public function AssignStudentClassSubject()
{
    $classes  = Classes::all();

    // Fetch only current term & session set by admin
     // Current term & session
        $terms = Term::where('is_current', true)->get();
        $sessions = AcademicSession::where('is_current', true)->get();
    return view('backend.student.assign_student_class_subject', compact('classes', 'terms', 'sessions'));
}

// Fetch subjects for selected class
public function FetchSubjects(Request $request)
{
    $class_id = $request->class_id;
    $class = Classes::with('subjects')->where('id', $class_id)->first();
    $class_subjects = $class->subjects;

    $subject_data = [];
    $subject_data[] = '<input type="checkbox" id="select_all_subjects"> <label><strong>Select All Subjects</strong></label><br>';

    foreach ($class_subjects as $subject) {
        $subject_data[] =
            '<input class="form-check-input subject-checkbox" name="subject_ids[]" value="' . $subject->id . '" type="checkbox">
             <label>' . $subject->subject_name . '</label><br>';
    }

    return response()->json(['subjects' => $subject_data]);
}

// Fetch students for a class
public function FetchStudents(Request $request)
{
    $class_id = $request->class_id;
    $students = Student::where('class_id', $class_id)
    ->orderBy('id', 'desc')->get();

    $student_data = [];
    $student_data[] = '<input type="checkbox" id="select_all_students"> <label><strong>Select All Students</strong></label><br>';

    foreach ($students as $student) {
        $student_data[] =
            '<input class="form-check-input student-checkbox" name="student_ids[]" value="' . $student->id . '" type="checkbox">
             <label>' . $student->name . '</label><br>';
    }

    return response()->json(['students' => $student_data]);
}

// Store Assignments
public function StoreStudentClassSubject(Request $request)
{
    $request->validate([
        'student_ids' => 'required|array',
        'class_id' => 'required',
        'subject_ids' => 'required|array',
        'session' => 'required|string',
        'term' => 'required|string'
    ]);

    foreach ($request->student_ids as $student_id) {
        foreach ($request->subject_ids as $subject_id) {
            $alreadyExist = AssignClassSubjectStudent::where('student_id', $student_id)
                ->where('class_id', $request->class_id)
                ->where('subject_id', $subject_id)
                ->where('session', $request->session)
                ->where('term', $request->term)
                ->first();

            if (!$alreadyExist) {
                AssignClassSubjectStudent::create([
                    'student_id' => $student_id,
                    'subject_id' => $subject_id,
                    'class_id' => $request->class_id,
                    'session' => $request->session,
                    'term' => $request->term,
                ]);
            }
        }
    }

    return redirect()->back()->with([
        'message' => 'Assigned Successfully',
        'alert-type' => 'success'
    ]);
}
// end method



public function ManageAssignStudentClassSubject(){

    $manageAssigns = AssignClassSubjectStudent::with(['student', 'subject', 'class'])
    ->orderBy('id', 'desc')
    ->get();


    return view('backend.student.manage_assign_student_class_subject', compact('manageAssigns'));
  
}
// end method


public function EditAssignStudentClassSubject($id){
    $AssignSubjectstudent = AssignClassSubjectStudent::findOrFail($id);
    $students = Student::all();
    $subjects = Subject::all();
    $classes  = Classes::all();

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
 
}// end method