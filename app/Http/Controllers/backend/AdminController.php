<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class AdminController extends Controller
{
    public function AdminLogout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }//end method

    public function AdminProfile(){

        $id = Auth::user()->id;
        $adminData = User::findOrFail($id);
        return view('backend.admin_profile.admin.admin_profile_view', compact('adminData'));
    
    
    }//end method

    
    public function AdminProfileUpdate(Request $request){

        $id = Auth::user()->id;
        $admin = User::findOrFail($id);
        $admin->user_name = $request->user_name;
        $admin->email = $request->email;
       
        //checking if admin is also updating his profile photo along with other data
        if( $request->hasFile('photo')){
    
            //save the request photo in a variable
            $file = $request->file('photo');
    
            //update the admin profile image in the image folder directory, to avoid show previous image repeatedly
            @unlink(public_path('uploads/admin_profile/'.$admin->photo));
    
            //generating unique name for the image 
            $imageName = date('YmdHi'). '.' .$file->getClientOriginalName(); // sample-> 20250118.pic_name.png
    
            //move the photo to the uploads directory
            $file->move(public_path('uploads/admin_profile'), $imageName);
    
            //save new admin profile image in the database
            $admin['photo'] = $imageName;
    
        }
    
        //save data
        $admin->save();
    
        $notification = array(
            'message' => 'Admin Profile Updated Successfully!',
            'alert-type' => 'success'
        );
    
        //redirect back to same page
    
        return redirect()->back()->with($notification);
    
    
    
    }//end method

    public function AdminPasswordChange(){

        return view('backend.admin_profile.admin.password_change');
    
    }//end method

    public function AdminPasswordUpdate(Request $request){

        $request->validate([
    
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);
    
        if(!Hash::check($request->old_password, Auth::user()->password)){
            $notification = array(
                'message' => 'Old Password Does Not Match!',
                'alert-type' => 'error');
      
            //redirect back to same page
        
            return redirect()->back()->with($notification);
        }

        User::whereId(Auth::user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);
    
        $notification = array(
            'message' => 'Password Updated Succesfully',
            'alert-type' => 'success'
        );
    
        //redirect back to same page
    
        return redirect()->back()->with($notification);
    }
    

}
