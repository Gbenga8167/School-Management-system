<?php

namespace App\Http\Controllers\backend;

use App\Models\terms;
use Illuminate\Http\Request;
use App\Models\SchoolSetting;
use App\Models\academic_session;
use App\Http\Controllers\Controller;

class SchoolSettingController extends Controller
{
    public function index()
    {
        // Get first setting (we only need one row for the whole school)
        $setting = SchoolSetting::first();
        return view('backend.admin_profile.school_setting.settings', compact('setting'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'address'=> 'nullable|string|max:255',
            'motto'  => 'nullable|string|max:255',
            'logo'   => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'stamp'  => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);
    
        $setting = SchoolSetting::first() ?? new SchoolSetting();
    
        $setting->name = $request->name;
        $setting->address = $request->address;
        $setting->motto = $request->motto;
    
        // Handle logo upload
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/logo_images'), $filename);
    
            // delete old logo if exists
            if ($setting->logo && file_exists(public_path('uploads/logo_images/' . $setting->logo))) {
                unlink(public_path('uploads/logo_images/' . $setting->logo));
            }
    
            $setting->logo = $filename;
        }
    
        // Handle stamp upload
        if ($request->hasFile('stamp')) {
            $stamp = $request->file('stamp');
            $stampName = time() . '.' . $stamp->getClientOriginalExtension();
            $stamp->move(public_path('uploads/stamp_images'), $stampName);
    
            // delete old stamp if exists
            if ($setting->stamp && file_exists(public_path('uploads/stamp_images/' . $setting->stamp))) {
                unlink(public_path('uploads/stamp_images/' . $setting->stamp));
            }
    
            $setting->stamp = $stampName;
        }
    
        $setting->save();
    
        return redirect()->back()->with([
            'message' => 'Settings updated successfully!',
            'alert-type' => 'success'
        ]);
    }
    

    public function SessionIndex()
    {
        $sessions = academic_session::orderBy('id', 'desc')->paginate(2);
        $terms = terms::orderBy('id', 'asc')->get();

        return view('backend.admin_profile.school_setting.term_session_create', compact('sessions', 'terms'));
    }

    public function storeSession(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:academic_sessions,name',
        ]);

        academic_session::create([
            'name' => $request->name,
            'is_current' => 0,
        ]);

        return back()->with('message', 'Academic session added successfully.');
    }

    public function toggleSession($id)
    {
        $session = academic_session::findOrFail($id);
        $session->is_current = !$session->is_current;
        $session->save();

        return back()->with('message', 'Session status updated.');
    }

    public function toggleTerm($id)
    {
        $term = terms::findOrFail($id);
        $term->is_current = !$term->is_current;
        $term->save();

        return back()->with('message', 'Term status updated.');
    }


    public function edit($id)
{
    $session = academic_session::findOrFail($id);
    return view('backend.admin_profile.school_setting.term_session_update', compact('session'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|unique:academic_sessions,name,' . $id,
    ]);

    $session = academic_session::findOrFail($id);
    $session->update([
        'name' => $request->name,
    ]);

    return redirect()->back()->with('message', 'Academic session updated successfully!');
}

public function destroy($id)
{
    $session = academic_session::findOrFail($id);
    $session->delete();

    return redirect()->back()->with('message', 'Academic session deleted successfully!');
}

}
