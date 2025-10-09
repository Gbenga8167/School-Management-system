@extends('backend.teacher_account.teacher_dashboard')
@section('teacher')

@php

// START CODE TO Get all class IDs assigned to this teacher AND Count all students in those classes

use App\Models\AssignedClassSubjectTeacher;
use App\Models\AssignClassSubjectStudent;

$teacher = \App\Models\Teacher::where('user_id', Auth::id())->first();
 
// 1) Current Term & Session
$currentTerm = DB::table('terms')->where('is_current', 1)->value('name');
$currentSession = DB::table('academic_sessions')->where('is_current', 1)->value('name');

$teacherId = \App\Models\Teacher::where('user_id', Auth::id())->first();

// Step 1: Get all class IDs assigned to this teacher
$teacherClassIds = AssignedClassSubjectTeacher::where('teacher_id', $teacherId->id)
        ->where('term', $currentTerm)
        ->where('session', $currentSession)
        ->pluck('class_id')
        ->toArray();

// Step 2: Count all students in those classes
$studentCount = AssignClassSubjectStudent::whereIn('class_id', $teacherClassIds)
        ->where('term', $currentTerm)
        ->where('session', $currentSession)
    ->distinct('student_id') // avoid duplicates if a student is in multiple subjects
    ->count('student_id');

    // END CODE TO Get all class IDs assigned to this teacher AND Count all students in those classes
@endphp


@php

// START student performance and grade

use Illuminate\Support\Facades\DB;

// 1) Current Term & Session
$currentTerm = DB::table('terms')->where('is_current', 1)->value('name');
$currentSession = DB::table('academic_sessions')->where('is_current', 1)->value('name');

// 2) Get Teacher row (by user_id) and its teacher_id
$teacherRow = DB::table('teachers')->where('user_id', Auth::id())->first();
$teacherId = $teacherRow->id ?? null;

// 3) Class & Subject IDs assigned to this teacher (pivot: assigned_class_subject_teachers)
$classIds = [];
$subjectIds = [];

if ($teacherId) {
    $pivot = DB::table('assigned_class_subject_teachers')
        ->where('teacher_id', $teacherId)
        ->pluck('class_id', 'id');
    $classIds = array_values($pivot->toArray());

    $subjectIds = DB::table('assigned_class_subject_teachers')
        ->where('teacher_id', $teacherId)
        ->pluck('subject_id')
        ->toArray();
}

// 4) Fetch results for those classes + subjects in current term/session
$results = collect();
if (!empty($classIds) && !empty($subjectIds) && $currentTerm && $currentSession) {
    $results = DB::table('results')
        ->select('ca1', 'ca2', 'ca3', 'exam', 'total', 'grade')
        ->whereIn('class_id', $classIds)
        ->whereIn('subject_id', $subjectIds)
        ->where('term', $currentTerm)
        ->where('session', $currentSession)
        ->get();
}

// 5) Averages (fallback to 0 if empty)
$avgCA1   = round((float) ($results->avg('ca1')   ?? 0), 2);
$avgCA2   = round((float) ($results->avg('ca2')   ?? 0), 2);
$avgCA3   = round((float) ($results->avg('ca3')   ?? 0), 2);
$avgExam  = round((float) ($results->avg('exam')  ?? 0), 2);
$avgTotal = round((float) ($results->avg('total') ?? 0), 2);

// 6) Grade distribution
$gradeDistribution = $results->groupBy('grade')->map->count()->sortKeys();
$gradeLabels = $gradeDistribution->keys()->values();
$gradeCounts = $gradeDistribution->values();

// End student performance and grade
@endphp






<style>
    .dashboard-card {
        transition: all 0.3s ease-in-out;
    }
    .dashboard-card:hover {
        transform: translateY(-8px) scale(1.03);
        box-shadow: 0 12px 25px rgba(0,0,0,0.2) !important;
    }
    .dashboard-card .avatar-sm i {
        transition: transform 0.3s ease-in-out, color 0.3s ease-in-out;
    }
    .dashboard-card:hover .avatar-sm i {
        transform: scale(1.2);
        color: #fff;
    }

    /*Animation Styles*/
    .animate-card {
        opacity: 0;
        transform: translateY(30px);
        animation: popUp 0.8s ease forwards;
        animation-delay: var(--delay, 0s);
    }
    @keyframes popUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="container-fluid">
    <!-- 1. Welcome Header -->
    <div class="row mb-4 animate-card" style="--delay:0s;">
        <div class="col-12">
            <div class="d-flex align-items-center p-4 text-white rounded-4 shadow"
                 style="background: linear-gradient(135deg, #007bff, #00c6ff); border-radius:5px;">
                <div>
                    <h5 class="mb-1 fw-bold" style="color: #fff;">
                       Hi,  {{ $teacher->name }}
                    </h5>
                <small class="text-light" style="font-size:18px;">Welcome back to your dashboard 👋</small><br>
                
                </div>
            </div>
        </div>
    </div>



    <!-- Quick Stats Cards -->
<div class="row">
    <!-- Total Classes -->
    <div class="col-xl-4 col-md-6 animate-card" style="--delay:0.2s;">
        <div class="card dashboard-card shadow-lg border-0 rounded-4 text-white" style="background: linear-gradient(135deg, #6a11cb, #2575fc);">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="mb-1 fw-semibold">Classes Assigned</p>
                        <h3 class="fw-bold mb-0" style="color: #fff;">
                            {{ \App\Models\AssignedClassSubjectTeacher::where('teacher_id', $teacher->id)
                                ->where('term', $currentTerm)
                                ->where('session', $currentSession)
                                ->distinct('class_id')
                                ->count('class_id') }}
                        </h3>
                    </div>
                    <div class="avatar-sm bg-white bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center">
                        <i class="ri-building-line fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Subjects -->
    <div class="col-xl-4 col-md-6 animate-card" style="--delay:0.4s;">
        <div class="card dashboard-card shadow-lg border-0 rounded-4 text-white" style="background: linear-gradient(135deg, #00b09b, #96c93d);">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="mb-1 fw-semibold">Subjects Assigned</p>
                        <h3 class="fw-bold mb-0" style="color: #fff;">
                            {{ \App\Models\AssignedClassSubjectTeacher::where('teacher_id', $teacher->id)
                                ->where('term', $currentTerm)
                                ->where('session', $currentSession)
                                ->distinct('subject_id')
                                ->count('subject_id') }}
                        </h3>
                    </div>
                    <div class="avatar-sm bg-white bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center">
                        <i class="fas fa-book-open fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Students -->
    <div class="col-xl-4 col-md-6 animate-card" style="--delay:0.6s;">
        <div class="card dashboard-card shadow-lg border-0 rounded-4 text-white" style="background: linear-gradient(135deg, #ff416c, #ff4b2b);">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="mb-1 fw-semibold" style="color: #fff;">My Students Across all classes</p>
                        <h3 class="fw-bold mb-0" style="color: #fff;">{{ $studentCount }}</h3>
                    </div>
                    <div class="avatar-sm bg-white bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center">
                        <i class="ri-user-3-line fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Optional Charts -->
<div class="row mt-4">
    <!-- Performance -->
    <div class="col-xl-6 col-md-12 animate-card" style="--delay:0.8s;">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0" style="color:#fff;">
                    Performance ({{ $currentTerm }} • {{ $currentSession }})
                </h5>
            </div>
            <div class="card-body">
                @if($results->isEmpty())
                    <div class="alert alert-info mb-3">No results yet for your assigned classes/subjects in the current term & session.</div>
                @endif
                <canvas id="perfChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Grade Distribution -->
    <div class="col-xl-6 col-md-12 animate-card" style="--delay:1s;">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0" style="color:#fff;">
                    Grade Distribution ({{ $currentTerm }} • {{ $currentSession }})
                </h5>
            </div>
            <div class="card-body">
                @if($results->isEmpty())
                    <div class="alert alert-info mb-3">No grades yet for your assigned classes/subjects in the current term & session.</div>
                @endif
                <canvas id="gradeChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

</div> <!-- closed div class="container-fluid" -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Performance chart (bar)
new Chart(document.getElementById('perfChart'), {
    type: 'bar',
    data: {
        labels: ['CA1','CA2','CA3','Exam','Total'],
        datasets: [{
            label: 'Average',
            data: [{{ $avgCA1 }}, {{ $avgCA2 }}, {{ $avgCA3 }}, {{ $avgExam }}, {{ $avgTotal }}],
            backgroundColor: 'rgba(37, 117, 252, 0.6)',
            borderColor: 'rgba(37, 117, 252, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: { y: { beginAtZero: true } },
        plugins: { legend: { display: false } }
    }
});

// Grade distribution (doughnut)
new Chart(document.getElementById('gradeChart'), {
    type: 'doughnut',
    data: {
        labels: {!! $gradeLabels->toJson() !!},
        datasets: [{
            data: {!! $gradeCounts->toJson() !!},
            backgroundColor: [
                'rgba(102,126,234,0.7)',
                'rgba(0,176,155,0.7)',
                'rgba(255,65,108,0.7)',
                'rgba(54,209,220,0.7)',
                'rgba(255,206,86,0.7)',
                'rgba(153,102,255,0.7)'
            ],
            borderWidth: 1
        }]
    },
    options: { responsive: true }
});
</script>

@endsection
