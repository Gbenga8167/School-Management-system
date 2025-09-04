
@extends('backend.student_account.student_dashboard')
@section('student')
<div class="container mt-4">
    {{-- ================= HEADER ================= --}}
    @php
        use Carbon\Carbon;
        use Illuminate\Support\Facades\Auth;
        use App\Models\AssignClassSubjectStudent;
        use App\Models\CBTTest;
        use App\Models\Result;

        $student = \App\Models\Student::where('user_id', Auth::id())->first();

        $hour = Carbon::now()->format('H');
        if ($hour < 12) {
            $greeting = "Good Morning";
        } elseif ($hour < 18) {
            $greeting = "Good Afternoon";
        } else {
            $greeting = "Good Evening";
        }

        $currentDate = Carbon::now()->format('l, jS F Y - h:i A');

        // Current Term & Session
        $currentTerm = \App\Models\terms::where('is_current', true)->value('name');
        $currentSession = \App\Models\academic_session::where('is_current', true)->value('name');

        // Student's class_id from pivot
        $classId = AssignClassSubjectStudent::where('student_id', $student->id)
            ->where('term', $currentTerm)
            ->where('session', $currentSession)
            ->value('class_id');

        // Quick Stats
        $totalSubjects = AssignClassSubjectStudent::where('student_id', $student->id)
            ->where('term', $currentTerm)
            ->where('session', $currentSession)
            ->distinct('subject_id')
            ->count('subject_id');

        $totalCBTTests = CBTTest::where('class_id', $classId)
            ->where('term', $currentTerm)
            ->where('session', $currentSession)
            ->count();

        $totalResults = Result::where('student_id', $student->id)
            ->where('term', $currentTerm)
            ->where('session', $currentSession)
            ->count();

        // Recent CBTs & Results
        $recentCBTs = CBTTest::where('class_id', $classId)
            ->where('term', $currentTerm)
            ->where('session', $currentSession)
            ->latest()->take(5)->get();

        $recentResults = Result::where('student_id', $student->id)
            ->where('term', $currentTerm)
            ->where('session', $currentSession)
            ->latest()->take(5)->get();
    @endphp

    <div class="mb-4">
        <h3>{{ $greeting }}, {{ $student->name }}</h3>
        <p>{{ $currentDate }}</p>
    </div>

    {{-- ================= QUICK STATS CARDS ================= --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary shadow rounded zoom-card">
                <div class="card-body text-center">
                    <h5>Total Subjects</h5>
                    <h2>{{ $totalSubjects }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success shadow rounded zoom-card">
                <div class="card-body text-center">
                    <h5>CBT Tests</h5>
                    <h2>{{ $totalCBTTests }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-danger shadow rounded zoom-card">
                <div class="card-body text-center">
                    <h5>Results</h5>
                    <h2>{{ $totalResults }}</h2>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= RECENT CBTs ================= --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow rounded mb-4 zoom-card">
                <div class="card-header bg-info text-white">
                    Recent CBT Tests
                </div>
                <div class="card-body">
                    @if($recentCBTs->isEmpty())
                        <p>No CBT Tests available</p>
                    @else
                        <ul class="list-group">
                            @foreach($recentCBTs as $cbt)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $cbt->title }}
                                    <a href="{{ route('student.cbt.test', $cbt->id) }}" class="btn btn-sm btn-primary">Take Test</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        {{-- ================= RECENT RESULTS ================= --}}
        <div class="col-md-6">
            <div class="card shadow rounded mb-4 zoom-card">
                <div class="card-header bg-warning text-dark">
                    Recent Results
                </div>
                <div class="card-body">
                    @if($recentResults->isEmpty())
                        <p>No Results available</p>
                    @else
                        <ul class="list-group">
                            @foreach($recentResults as $result)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $result->subject->name ?? 'N/A' }} - <strong>{{ $result->grade ?? 'N/A' }}</strong>
                                    <a href="{{ route('student.result.form', $result->id) }}" class="btn btn-sm btn-success">View</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ================= CSS EFFECTS ================= --}}
<style>
    .zoom-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .zoom-card:hover {
        transform: scale(1.05);
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }
</style>
@endsection
