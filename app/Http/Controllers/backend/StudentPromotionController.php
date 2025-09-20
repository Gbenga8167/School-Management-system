<?php

namespace App\Http\Controllers\backend;

use App\Models\terms;
use App\Models\classes;
use Illuminate\Http\Request;
use App\Models\academic_session;
use App\Http\Controllers\Controller;
use App\Models\AssignClassSubjectStudent;
use Illuminate\Support\Facades\DB;

class StudentPromotionController extends Controller
{
   // Show promotion form
    public function index()
    {
        $classes = classes::all();
        $currentTerm = terms::where('is_current', 1)->first();
        $currentSession = academic_session::where('is_current', 1)->first();

        $terms = terms::all();
        $sessions = academic_session::all();

        return view('backend.admin_profile.admin.student_promotion.promote_student', compact(
            'classes', 'currentTerm', 'currentSession', 'terms', 'sessions'
        ));
    }

    // Fetch students based on current term & session
    public function fetchStudents(Request $request)
    {
        $class_id = $request->class_id;

        $currentTerm = terms::where('is_current', 1)->first();
        $currentSession = academic_session::where('is_current', 1)->first();

        $students = AssignClassSubjectStudent::with('student')
            ->where('class_id', $class_id)
            ->where('term', $currentTerm->name)
            ->where('session', $currentSession->name)
            ->orderBy('id', 'desc')
            ->get()
            ->pluck('student')
            ->unique('id');

        $student_data = '';
        foreach ($students as $student) {
            $student_data .= '
                <div>
                    <input type="checkbox" name="student_ids[]" value="'.$student->id.'" class="form-check-input student-check">
                    <label>'.$student->name.'</label>
                </div>
            ';
        }

        return response()->json(['students' => $student_data]);
    }

    // Fetch subjects for new class
    public function fetchSubjects(Request $request)
    {
        $class_id = $request->class_id;
        $class = classes::with('subjects')->where('id', $class_id)->first();
        $class_subjects = $class->subjects;

        $subject_data = '';
        foreach ($class_subjects as $subject) {
            $subject_data .= '
                <div>
                    <input type="checkbox" name="subject_ids[]" value="'.$subject->id.'" class="form-check-input subject-check">
                    <label>'.$subject->subject_name.'</label>
                </div>
            ';
        }

        return response()->json(['subjects' => $subject_data]);
    }

    // Store promotion
    // Store promotion
public function storePromotion(Request $request)
{
    $request->validate([
        'student_ids' => 'required|array',
        'new_class_id' => 'required',
        'subject_ids' => 'required|array',
        'term' => 'required|string',
        'session' => 'required|string',
    ]);

    foreach ($request->student_ids as $student_id) {
        foreach ($request->subject_ids as $subject_id) {

            // ✅ Prevent duplicate entries
            $exists = AssignClassSubjectStudent::where([
                'student_id' => $student_id,
                'class_id' => $request->new_class_id,
                'subject_id' => $subject_id,
                'term' => $request->term,
                'session' => $request->session,
            ])->exists();

            if (!$exists) {
                AssignClassSubjectStudent::create([
                    'student_id' => $student_id,
                    'class_id' => $request->new_class_id,
                    'subject_id' => $subject_id,
                    'term' => $request->term,
                    'session' => $request->session,
                ]);
            }
        }
    }

    $notification = [
        'message' => 'Students promoted successfully!',
        'alert-type' => 'success'
    ];

    return redirect()->back()->with($notification);
}


//MANAGE PROMOTION
// ✅ Manage Promotions with filters
    public function managePromotion(Request $request)
    {
        $query = AssignClassSubjectStudent::with(['student', 'class', 'subject']);

        // Apply filters
        if ($request->class_id) {
            $query->where('class_id', $request->class_id);
        }
        if ($request->term) {
            $query->where('term', $request->term);
        }
        if ($request->session) {
            $query->where('session', $request->session);
        }

        $promotions = $query->orderBy('session', 'desc')
            ->orderBy('term', 'desc')
            ->paginate(20);

        // Dropdown data
        $classes = classes::all();
        $terms = terms::pluck('name');
        $sessions = academic_session::pluck('name');

        return view('backend.admin_profile.admin.student_promotion.manage_promotion', compact(
            'promotions', 'classes', 'terms', 'sessions'
        ));
    }

    // ✅ Single delete
    public function deletePromotion($id)
    {
        $promotion = AssignClassSubjectStudent::findOrFail($id);
        $promotion->delete();

        return redirect()->back()->with([
            'message' => 'Promotion deleted successfully!',
            'alert-type' => 'success'
        ]);
    }

    // ✅ Bulk delete
    public function bulkDelete(Request $request)
    {
        if ($request->has('promotion_ids')) {
            AssignClassSubjectStudent::whereIn('id', $request->promotion_ids)->delete();

            return redirect()->back()->with([
                'message' => 'Selected promotions deleted successfully!',
                'alert-type' => 'success'
            ]);
        }

        return redirect()->back()->with([
            'message' => 'No promotions selected!',
            'alert-type' => 'warning'
        ]);
    }





    // ✅ Edit Promotion
public function editPromotion($id)
{
    $promotion = AssignClassSubjectStudent::with(['student','class','subject'])->findOrFail($id);

    $classes = classes::all();
    $subjects = $promotion->class ? $promotion->class->subjects : [];
    $terms = terms::pluck('name');
    $sessions = academic_session::pluck('name');

    return view('backend.admin_profile.admin.student_promotion.edit_promotion', compact(
        'promotion', 'classes', 'subjects', 'terms', 'sessions'
    ));
}

// ✅ Update Promotion
public function updatePromotion(Request $request, $id)
{
    $request->validate([
        'class_id'   => 'required',
        'subject_id' => 'required',
        'term'       => 'required|string',
        'session'    => 'required|string',
    ]);

    $promotion = AssignClassSubjectStudent::findOrFail($id);

    $promotion->update([
        'class_id'   => $request->class_id,
        'subject_id' => $request->subject_id,
        'term'       => $request->term,
        'session'    => $request->session,
    ]);

    return redirect()->route('promotion.manage')->with([
        'message' => 'Promotion updated successfully!',
        'alert-type' => 'success'
    ]);
}

}



