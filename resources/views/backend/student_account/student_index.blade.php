@extends('backend.student_account.student_dashboard')
@section('student')

@php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\AssignClassSubjectStudent;
use App\Models\CBTTest;
use App\Models\Result;

// 1. Logged-in student
$student = \App\Models\Student::where('user_id', Auth::id())->first();

// 2. Current term & session
$currentTerm = DB::table('terms')->where('is_current', 1)->value('name');
$currentSession = DB::table('academic_sessions')->where('is_current', 1)->value('name');

// 3. Total Subjects (for this student in current term/session)
$totalSubjects = AssignClassSubjectStudent::where('student_id', $student->id)
    ->where('term', $currentTerm)
    ->where('session', $currentSession)
    ->distinct('subject_id')
    ->count('subject_id');

// 4. Total CBT Tests (available for this student’s class in current term/session)
// Get the student's class for the current term & session
$studentClassId = AssignClassSubjectStudent::where('student_id', $student->id)
    ->where('term', $currentTerm)
    ->where('session', $currentSession)
    ->value('class_id');

// If found, count CBT for that class
 $totalCBTTests = CBTTest::where('class_id', $studentClassId)
            ->where('term', $currentTerm)
            ->where('session', $currentSession)
            ->count();

// 5. Total Results (for this student in current term/session)
$totalResults = Result::where('student_id', $student->id)
    ->where('term', $currentTerm)
    ->where('session', $currentSession)
    ->count();

// 6. Recent CBT Tests
$recentCBT = CBTTest::where('class_id', $studentClassId)
    ->where('term', $currentTerm)
    ->where('session', $currentSession)
    ->latest()
    ->take(5)
    ->get();

// Greeting logic
$hour = now()->format('H');
if ($hour < 12) {
    $greeting = "Good Morning";
} elseif ($hour < 17) {
    $greeting = "Good Afternoon";
} else {
    $greeting = "Good Evening";
}
@endphp

<!-- Student Dashboard -->
<!-- ================= Student Dashboard ================= -->

<div class="container-fluid">

    <!-- 1. Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center p-4 bg-primary text-white rounded-4 shadow">
                <div>
                    <h5 class="mb-1 fw-bold" style="color: #fff;">
                        Good {{ now()->format('A') == 'AM' ? 'Morning' : (now()->format('H') < 17 ? 'Afternoon' : 'Evening') }},
                        {{ $student->name }}
                    </h5>
                    <small class="text-light" style="font-size:18px;">Today is {{ now()->format('l, F j, Y') }}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Quick Stats Cards -->
    <div class="row">

        <!-- Total Subjects -->
        <div class="col-xl-4 col-md-6">
            <div class="card shadow-lg border-0 rounded-4 text-white" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 fw-semibold">My Subjects</p>
                        <h3 class="fw-bold mb-0" style="color: #fff;">{{ $totalSubjects }}</h3>
                    </div>
                    <div class="avatar-sm bg-white bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center">
                        <i class="ri-book-open-line fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total CBT Tests -->
        <div class="col-xl-4 col-md-6">
            <div class="card shadow-lg border-0 rounded-4 text-white" style="background: linear-gradient(135deg, #ff416c, #ff4b2b);">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 fw-semibold">CBT Tests</p>
                        <h3 class="fw-bold mb-0" style="color: #fff;">{{ $totalCBTTests}}</h3>
                    </div>
                    <div class="avatar-sm bg-white bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center">
                        <i class="ri-file-list-3-line fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Availability -->
        <div class="col-xl-4 col-md-6">
            <div class="card shadow-lg border-0 rounded-4 text-white" style="background: linear-gradient(135deg, #11998e, #38ef7d);">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 fw-semibold">My Results</p>
                        <h5 class="fw-bold mb-0" style="color: #fff;">
                            {{ $totalResults ? 'Available' : 'Not Yet' }}
                        </h5>
                    </div>
                    <div class="avatar-sm bg-white bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center">
                        <i class="ri-bar-chart-2-line fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- 3. Recent CBT & Results -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow border-0 rounded-4">
                <div class="card-header bg-gradient text-white rounded-top-4" style="background: linear-gradient(135deg, #36d1dc, #5b86e5);">
                    <h6 class="mb-0 fw-bold">Recent CBT Tests</h6>
                </div>
                <div class="card-body">
                    @if($recentCBT->isEmpty())
                        <p class="text-muted">No CBT tests available.</p>
                    @else
                        
                                    <p class="text-primary fw-bold">Attempt cbt</p>
                                    <a href="{{ route('student.index') }}" class="btn btn-sm btn-primary">Take Test</a>
                             
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow border-0 rounded-4">
                <div class="card-header bg-gradient text-white rounded-top-4" style="background: linear-gradient(135deg, #ff9a9e, #fecfef);">
                    <h6 class="mb-0 fw-bold">Recent Results</h6>
                </div>
                <div class="card-body">
                    @if(!$totalResults)
                        <p class="text-muted">No results available yet.</p>
                    @else
                        <p class="text-success fw-bold">Your latest results are available 🎉</p>
                        <a href="{{ route('student.result.form') }}" class="btn btn-sm btn-success">View Results</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
