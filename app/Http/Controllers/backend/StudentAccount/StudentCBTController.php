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
    // Start Test page
    public function StartTest($id)
{
    $cbtTest = CBTTest::findOrFail($id);

    // Current time in UTC
    $nowUtc = now()->utc();

    // IMPORTANT: DB time is assumed to be saved in Africa/Lagos local time.
    // Convert from Africa/Lagos -> UTC before comparing/sending to JS.
    $startTimeUtc = \Carbon\Carbon::parse($cbtTest->start_time, 'Africa/Lagos')->utc();

    $testStatus = $nowUtc->lt($startTimeUtc) ? 'not_started' : 'started';

    return view('backend.student_account.cbt_question.student_cbt_test', [
        'cbtTest'    => $cbtTest,
        'testStatus' => $testStatus,
        'startTime'  => $startTimeUtc->getTimestampMs(), // UTC ms
        'serverNow'  => $nowUtc->getTimestampMs(),       // UTC ms (for drift correction)
    ]);
}

    

    public function BeginTest($id)
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();
        $cbtTest = CBTTest::findOrFail($id);

        // ✅ Block if already completed
        $hasAttempt = CBTAttempt::where('student_id', $student->id)
            ->where('cbt_test_id', $cbtTest->id)
            ->where('status', 'completed')
            ->exists();

        if ($hasAttempt) {
            return redirect()->route('student.index')->with([
                'message' => 'You have already completed this test',
                'alert-type' => 'error',
            ]);
        }


        // ✅ Block if test expired
        if ($cbtTest->end_time && now()->utc()->gte(Carbon::parse($cbtTest->end_time, 'Africa/Lagos')->utc())) {
            return redirect()->route('student.index')->with([
                'message' => 'This test has expired.',
                'alert-type' => 'error',
            ]);
        }

        // ✅ Only reuse active attempt
        $attempt = CBTAttempt::where('cbt_test_id', $cbtTest->id)
            ->where('student_id', $student->id)
            ->where('status', 'in_progress')
            ->first();

        // ✅ If no active attempt, create fresh
        if (!$attempt) {
            $attempt = CBTAttempt::create([
                'cbt_test_id' => $cbtTest->id,
                'student_id'  => $student->id,
                'started_at'  => now()->utc(), // 🔒 always store UTC
                'status'      => 'in_progress',
            ]);
        }

        // ✅ Fetch randomized questions
        $questions = $cbtTest->questions()->inRandomOrder()->get();

        // ✅ TIMER LOGIC → started_at (UTC) + duration
        $effectiveEnd = Carbon::parse($attempt->started_at)->addMinutes($cbtTest->duration_minutes);

        $endTimeMs   = $effectiveEnd->timestamp * 1000;  // send UTC timestamp
        $serverNowMs = now()->utc()->timestamp * 1000;  // send current UTC timestamp

        return view('backend.student_account.cbt_question.questions', [
            'cbtTest'   => $cbtTest,
            'attempt'   => $attempt,
            'questions' => $questions,
            'endTime'   => $endTimeMs,
            'serverNow' => $serverNowMs,
        ]);
    }

    // Save an answer (AJAX)
    // Save an answer (AJAX)
public function saveAnswer(Request $request, $attemptId, $questionId)
{
    $student = Student::where('user_id', Auth::id())->firstOrFail();

    $attempt = CBTAttempt::where('id', $attemptId)
        ->where('student_id', $student->id)
        ->where('status', 'in_progress')
        ->firstOrFail();

    $cbtTest = $attempt->test; // related test
    $now     = now()->utc();

    // ✅ Compute effective end time: started_at + duration
    $effectiveEnd = Carbon::parse($attempt->started_at)->addMinutes($cbtTest->duration_minutes);

    // ✅ If test has a global end_time, respect it too
    $absoluteEnd = $cbtTest->end_time 
        ? Carbon::parse($cbtTest->end_time)->utc() 
        : null;

    // ✅ Final deadline = min(effectiveEnd, absoluteEnd if exists)
    $finalDeadline = $absoluteEnd 
        ? ($effectiveEnd->lt($absoluteEnd) ? $effectiveEnd : $absoluteEnd)
        : $effectiveEnd;

    // 🔒 Block saving after deadline
    if ($now->gt($finalDeadline)) {
        return response()->json([
            'success' => false,
            'message' => 'Time expired. You cannot save more answers.',
        ], 403);
    }

    $question = CBTQuestion::findOrFail($questionId);

    $selected  = strtolower($request->input('selected_option')); // 'a' | 'b' | 'c' | 'd'
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


    // Submit test (manual or auto)
public function submitTest(Request $request, $attemptId)
{
    $student = Student::where('user_id', Auth::id())->firstOrFail();

    $attempt = CBTAttempt::where('id', $attemptId)
        ->where('student_id', $student->id)
        ->firstOrFail();

    $cbtTest = $attempt->test; // fetch related test
    $now     = now()->utc();

    // ✅ If already completed, return politely
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

    // ✅ Compute effective end time: started_at + duration
    $effectiveEnd = Carbon::parse($attempt->started_at)->addMinutes($cbtTest->duration_minutes);

    // ✅ If test has a global end_time, respect it too
    $absoluteEnd = $cbtTest->end_time 
        ? Carbon::parse($cbtTest->end_time)->utc() 
        : null;

    // ✅ Final deadline = min(effectiveEnd, absoluteEnd if exists)
    $finalDeadline = $absoluteEnd 
        ? $effectiveEnd->lt($absoluteEnd) ? $effectiveEnd : $absoluteEnd 
        : $effectiveEnd;

    // ✅ If student tries to submit after deadline → auto-force complete with saved answers only
    if ($now->gt($finalDeadline)) {
        $score = $attempt->answers()->where('is_correct', true)->count();

        $attempt->update([
            'score'         => $score,
            'submitted_at'  => $now,
            'duration_used' => $now->diffInMinutes($attempt->started_at),
            'status'        => 'completed',
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'score'   => $score,
                'message' => 'Submission closed. Auto-submitted by server.',
            ]);
        }

        return redirect()->route('student.index')
            ->with('error', 'Time is up! Your test was auto-submitted. Score: ' . $score);
    }

    // ✅ Normal on-time submission
    $score = $attempt->answers()->where('is_correct', true)->count();

    $attempt->update([
        'score'         => $score,
        'submitted_at'  => $now,
        'duration_used' => $now->diffInMinutes($attempt->started_at),
        'status'        => 'completed',
    ]);

    if ($request->wantsJson()) {
        return response()->json([
            'success' => true,
            'score'   => $score,
            'message' => 'Submitted successfully',
        ]);
    }

    return redirect()->route('student.index')
        ->with('success', 'Test submitted successfully! Your score: ' . $score);
}

}
