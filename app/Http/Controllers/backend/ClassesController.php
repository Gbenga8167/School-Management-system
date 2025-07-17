<?php

namespace App\Http\Controllers\backend;

use App\Models\classes;
use App\Models\teacher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClassesController extends Controller
{
    public function CreateClasses(){
        return view('backend.admin_profile.admin.classes.create_classes');
    }//end method

    public function StoreClasses(Request $request){

        //validate user input
        $request->validate([

            'class_name' => 'required',
            'status' => 'required',
        ]);

//checking if class already exist in the database
        $AlreadyExist = classes::where('class_name', $request->class_name)->first();
        if( $AlreadyExist){
            $notification = array(
                'message' => ' Class Already exist',
                'alert-type' => 'info'
            );

            //redirect back to same page
  
      return redirect()->back()->with($notification);

        }else{
             //first method of inserting into database
             $class = new classes();
             $class->class_name = $request->class_name;
             $class->status = $request->status;
             $class->save();
            //end insert

             $notification = array(
             'message' => 'Class Created Succesfully',
             'alert-type' => 'info'
 );

 //redirect back to same page

 return redirect()->route('manage.classes')->with($notification);
        }
    
      
    

    }//end method


    public function ManageClasses(){
        $classes = classes::all();
        return view('backend.admin_profile.admin.classes.manage_clases', compact('classes'));
    }// end method

    
    public function EditClass($id){
        $class = classes::Find($id);
        return view('backend.admin_profile.admin.classes.edit_class', compact('class'));

    }// end method


    public function UpdateClass(Request $request){
        //validate user input
        $request->validate([

          'class_name' => 'required',
          'status' => 'required',
      ]);

      $id = $request->id;
      classes::Find( $id )->update([
          'class_name' => $request->class_name,
          'status' => $request->status
        
      ]);
      
      

      $notification = array(
          'message' => 'Student Class Updated Succesfully',
          'alert-type' => 'info'
      );
  
      //redirect back to same page
  
      return redirect()->route('manage.classes')->with($notification);
  

}// end method

public function DeleteClass($id){

    classes::Find($id)->delete();

    $notification = array(
        'message' => 'Student Class Deleted Succesfully',
        'alert-type' => 'info');

        return redirect()->back()->with($notification);

   }


   //assigned class teacher view
   public function AssignedClassTeacher(){

    $classes = classes::all();
    $teachers = teacher::all();
    return view('backend.admin_profile.admin.classes.assigned_class_teacher', compact('classes', 'teachers'));
}//end method



// STORE assigned class teacher
public function StoreAssignedClassTeacher(Request $request){

     //validate user input
     $request->validate([

        'class_id' => 'required|exists:classes,id',
        'teacher_id' => 'required|exists:teachers,id',
    ]);

    $class = classes:: findOrFail($request->class_id);

    //chech if the class already has a class teacher

    if($class->class_teacher_id !== null){

        $notification = array(
            'message' => 'This class already has a class teacher assigned',
            'alert-type' => 'info');
    
            return redirect()->back()->with($notification);
        }

// store assigned class teacher in classes models inside class_teacher_id column
    $class->class_teacher_id = $request->teacher_id;
    $class->save();

    $notification = array(
        'message' => 'Teacher Assigned Succesfully',
        'alert-type' => 'info');

        return redirect()->back()->with($notification);

}//end method  


//manage assigned class teacher
public function ManageAssignedClassTeacher(){

    $assignedClasses = classes::with('classTeacher')->whereNotNull('class_teacher_id')->get();
    return view('backend.admin_profile.admin.classes.manage_assigned_class_teacher', compact('assignedClasses'));
}//end method


//remove assigned class teacher
public function RemoveAssignedClassTeacher(classes $class){

    $class->class_teacher_id = null;

    $class->save();

    $notification = array(
        'message' => 'Class Teacher Removed Succesfully',
        'alert-type' => 'info');

        return redirect()->back()->with($notification);

   }


}
