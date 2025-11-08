<?php

namespace App\Http\Controllers\backend;

use App\Models\TermCalendar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TermCalendarController extends Controller
{
    public function TermCalendar(){

        $records = TermCalendar::orderBy('session', 'desc')->get();
        return view('backend.admin_profile.term_calendar.add_term_calendar', compact('records'));
        
    }//end method

    public function EditTermCalendar($id){
       
        $record = TermCalendar::findOrFail($id);
        $records = TermCalendar::orderBy('session', 'desc')->get();
        return view('backend.admin_profile.term_calendar.add_term_calendar', compact('record', 'records'));
    }// end method
    

    public function StoreTermCalendar(Request $request){

        $request->validate([ 
            'session' => 'required|string',
            'term' => 'required|string',
            'next_term_begins' => 'required|date',
        ]);


        //if editing
        if($request->has('id')){
           $existing = TermCalendar::where(
                'session', $request->session)->where('term', $request->term)
                ->where('id', '!=', $request->id)->first();

             if($existing){
                return back()->with('error', 'Another record already exist with this 
                session and term please use a different one');
             }

             TermCalendar::where('id', $request->id)->update([
                'session' => $request->session,
                'term' => $request->term,
                'next_term_begins' => $request->next_term_begins,
             ]);

             $notification = array(

                'message' => 'Updated Successfully',
                'alert-type' => 'info'
    
            ); //redirect back to same page
         
         return redirect()->back()->with($notification);
        }else{
            //if creating new

            $exists =  TermCalendar::where(
                'session', $request->session)
                ->where('term', $request->term)
                ->exists();


             if($exists){
                return back()->with('error', 'This 
                session and term  already exist, Try editing the record instead');
             }
            TermCalendar::create([
                'session' => $request->session,
                'term' => $request->term,
                'next_term_begins' => $request->next_term_begins,
            ]);

        }

        $notification = array(

            'message' => 'Saved',
            'alert-type' => 'info'

        ); //redirect back to same page
     
     return redirect()->back()->with($notification);

    } //end method

    public function destroy($id){
        TermCalendar::findOrFail($id)->delete();
        return back()->with('error', 'Record Deleted Successfully');
    

    }
    
}
