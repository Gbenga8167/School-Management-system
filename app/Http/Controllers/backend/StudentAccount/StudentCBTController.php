<?php

namespace App\Http\Controllers\backend\StudentAccount;

use Carbon\Carbon;
use App\Models\terms;
use App\Models\CBTTest;
use App\Models\student;
use App\Models\CBTAnswer;
use App\Models\CBTAttempt;
use App\Models\CBTQuestion;
use Illuminate\Http\Request;
use App\Models\academic_session;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\AssignClassSubjectStudent;

class StudentCBTController extends Controller
{
    // Display all CBT tests for the student
    public function Index()
    {
        $student = student::where('user_id', Auth::id())->firstOrFail();
        $currentTerm = terms::where('is_current', true)->first()?->name;
        $currentSession = academic_session::where('is_current', true)->first()?->name;

        $assignments = AssignClassSubjectStudent::where('student_id', $student->id)
            ->where('term', $currentTerm)
            ->where('session', $currentSession)
            ->get();

        $classIds = $assignments->pluck('class_id')->toArray();
        $subjectIds = $assignments->pluck('subject_id')->toArray();

        $cbtTests = CBTTest::whereIn('class_id', $classIds)
            ->whereIn('subject_id', $subjectIds)
            ->where('term', $currentTerm)
            ->where('session', $currentSession)
            ->get();

        return view('backend.student_account.cbt_question.index', compact('cbtTests'));
    }

    // Start Test page
    public function StartTest($id)
    {
        $cbtTest = CBTTest::findOrFail($id);
        $now = Carbon::now('Africa/Lagos');
        $startTime = Carbon::parse($cbtTest->start_time, 'Africa/Lagos');
        $testStatus = $now->lt($startTime) ? 'not_started' : 'started';

        return view('backend.student_account.cbt_question.student_cbt_test', [
            'cbtTest'   => $cbtTest,
            'testStatus'=> $testStatus,
            'startTime' => $startTime->toIso8601String(),
        ]);
    }

    // Begin Test and show questions
    public function BeginTest($id)
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();
        $cbtTest = CBTTest::findOrFail($id);


        //check if student already attempted this test
        $hasAttempt = CBTAttempt :: where('student_id', $student->id)
        ->where('cbt_test_id', $cbtTest->id )
        ->where('status', 'completed')->exists();

        if ($hasAttempt) {
            $notification = array(
                'message' => 'You have already completed this test',
                'alert-type' => 'error'
            );
        
            //redirect back to same page
        
            return redirect()->route('student.index')->with($notification);
        }


        // If a fixed end_time exists and is already past, block
        if ($cbtTest->end_time && Carbon::now('Africa/Lagos')->gte(Carbon::parse($cbtTest->end_time))) {
            $notification = array(
                'message' => ' This test has expired.',
                'alert-type' => 'error'
            );
        
            //redirect back to same page
        
            return redirect()->route('student.index')->with($notification);
           // return redirect()->route('student.index')->with('error', 'This test has expired.');
        }

        // Create attempt if it doesn't exist
        $attempt = CBTAttempt::firstOrCreate(
            [
                'cbt_test_id' => $cbtTest->id,
                'student_id'  => $student->id,
            ],
            [
                'started_at'  => now('Africa/Lagos'),
                'status'      => 'in_progress',
            ]
        );

        if (!$attempt->started_at) {
            $attempt->started_at = now('Africa/Lagos');
            $attempt->save();
        }

        // Get questions (random order)
        $questions = $cbtTest->questions()->inRandomOrder()->get();

        // ---- IMPORTANT TIMER LOGIC ----
        // If end_time IS set -> test expires at that exact time (ignore duration).
        // If end_time is NULL -> use attempt start + duration_minutes.
        if ($cbtTest->end_time) {
            $effectiveEnd = Carbon::parse($cbtTest->end_time);
        } else {
            $effectiveEnd = Carbon::parse($attempt->started_at)->addMinutes($cbtTest->duration_minutes);
        }

        // Pass milliseconds timestamp for JS (number, not string)
        $endTimeMs = $effectiveEnd->getTimestampMs();

        return view('backend.student_account.cbt_question.questions', [
            'cbtTest'   => $cbtTest,
            'attempt'   => $attempt,
            'questions' => $questions,
            'endTime'   => $endTimeMs, // number
        ]);
    }

    // Save an answer (AJAX)
    public function saveAnswer(Request $request, $attemptId, $questionId)
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();

        $attempt = CBTAttempt::where('id', $attemptId)
            ->where('student_id', $student->id)
            ->where('status', 'in_progress')
            ->firstOrFail();

        $question = CBTQuestion::findOrFail($questionId);

        $selected = strtolower($request->input('selected_option')); // 'a' | 'b' | 'c' | 'd'
        $isCorrect = $selected === strtolower($question->correct_option);

        CBTAnswer::updateOrCreate(
            [
                'cbt_attempt_id'  => $attempt->id,
                'cbt_question_id' => $question->id
            ],
            [
                'selected_option' => $selected,
                'is_correct'      => $isCorrect
            ]
        );

        return response()->json(['success' => true]);
    }

    // Submit test (manual or auto) — returns JSON for AJAX, or redirect for normal
    public function submitTest(Request $request, $attemptId)
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();

        $attempt = CBTAttempt::where('id', $attemptId)
            ->where('student_id', $student->id)
            ->firstOrFail();

        // If already completed, just respond politely
        if ($attempt->status === 'completed') {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'score'   => (int)($attempt->score ?? 0),
                    'message' => 'Already submitted.',
                ]);
            }
            return redirect()->route('student.index')
                ->with('success', 'Test already submitted. Your score: ' . (int)($attempt->score ?? 0));
        }

        $score = $attempt->answers()->where('is_correct', true)->count();

        $attempt->update([
            'score'         => $score,
            'submitted_at'  => now('Africa/Lagos'),
            'duration_used' => now('Africa/Lagos')->diffInMinutes($attempt->started_at),
            'status'        => 'completed',
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'score'   => $score,
                'message' => 'Submitted',
            ]);
        }

        return redirect()->route('student.index')
            ->with('success', 'Test submitted successfully! Your score: ' . $score);
    }
}









/*
namespace App\Http\Controllers\backend\StudentAccount;

use Carbon\Carbon;
use App\Models\terms;
use App\Models\CBTTest;
use App\Models\student;
use App\Models\CBTAnswer;
use App\Models\CBTAttempt;
use App\Models\CBTQuestion;
use Illuminate\Http\Request;
use App\Models\academic_session;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\AssignClassSubjectStudent;

class StudentCBTController extends Controller
{
    // Display all CBT tests for the student
    public function Index()
    {
        $student = student::where('user_id', Auth::id())->firstOrFail(); 
        $currentTerm = terms::where('is_current', true)->first()?->name;
        $currentSession = academic_session::where('is_current', true)->first()?->name;

        $assignments = AssignClassSubjectStudent::where('student_id', $student->id)
            ->where('term', $currentTerm)
            ->where('session', $currentSession)
            ->get();

        $classIds = $assignments->pluck('class_id')->toArray();
        $subjectIds = $assignments->pluck('subject_id')->toArray();

        $cbtTests = CBTTest::whereIn('class_id', $classIds)
            ->whereIn('subject_id', $subjectIds)
            ->where('term', $currentTerm)
            ->where('session', $currentSession)
            ->get();

        return view('backend.student_account.cbt_question.index', compact('cbtTests'));
    }

    // Start Test page
    public function StartTest($id)
    {
        $cbtTest = CBTTest::findOrFail($id);
        $now = Carbon::now('Africa/Lagos');
        $startTime = Carbon::parse($cbtTest->start_time, 'Africa/Lagos');
        $testStatus = $now->lt($startTime) ? 'not_started' : 'started';

        return view('backend.student_account.cbt_question.student_cbt_test', [
            'cbtTest' => $cbtTest,
            'testStatus' => $testStatus,
            'startTime' => $startTime->toIso8601String(),
        ]);
    }

    // Begin Test and show questions
    public function BeginTest($id)
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();
        $cbtTest = CBTTest::findOrFail($id);

        // Create attempt if it doesn't exist
        $attempt = CBTAttempt::firstOrCreate(
            [
                'cbt_test_id' => $cbtTest->id,
                'student_id' => $student->id,
            ],
            [
                'started_at' => now('Africa/Lagos'),
                'status' => 'in_progress',
            ]
        );

        if (!$attempt->started_at) {
            $attempt->started_at = now('Africa/Lagos');
            $attempt->save();
        }

        // Get questions in random order
        $questions = $cbtTest->questions()->inRandomOrder()->get();

        // Calculate fixed end time
        $fixedEndTime = $cbtTest->end_time
            ? Carbon::parse($cbtTest->end_time)
            : Carbon::parse($attempt->started_at)->addMinutes($cbtTest->duration_minutes);

        return view('backend.student_account.cbt_question.questions', [
            'cbtTest'   => $cbtTest,
            'attempt'   => $attempt,
            'questions' => $questions,
            'duration'  => $cbtTest->duration_minutes,
            'endTime'   => $fixedEndTime->timestamp * 1000, // milliseconds for JS
        ]);
    }

    // Save an answer
    public function saveAnswer(Request $request, $attemptId, $questionId)
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();

        $attempt = CBTAttempt::where('id', $attemptId)
            ->where('student_id', $student->id)
            ->where('status', 'in_progress')
            ->firstOrFail();

        $question = CBTQuestion::findOrFail($questionId);

        $isCorrect = strtolower($request->selected_option) === strtolower($question->correct_option);

        CBTAnswer::updateOrCreate(
            [
                'cbt_attempt_id'  => $attempt->id,
                'cbt_question_id' => $question->id
            ],
            [
                'selected_option' => strtolower($request->selected_option),
                'is_correct'      => $isCorrect
            ]
        );

        return response()->json(['success' => true]);
    }

    // Submit test (manual or auto)
    public function submitTest($attemptId)
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();

        $attempt = CBTAttempt::where('id', $attemptId)
            ->where('student_id', $student->id)
            ->where('status', 'in_progress')
            ->firstOrFail();

        $score = $attempt->answers()->where('is_correct', true)->count();

        $attempt->update([
            'score'         => $score,
            'submitted_at'  => now('Africa/Lagos'),
            'duration_used' => now('Africa/Lagos')->diffInMinutes($attempt->started_at),
            'status'        => 'completed',
        ]);

        return redirect()->route('student.index')
            ->with('success', 'Test submitted successfully! Your score: ' . $score);
    }
}
*/