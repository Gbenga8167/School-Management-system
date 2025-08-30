
@extends('backend.admin_profile.admin.admin_dashboard')
@section('admin')


@php
use App\Models\student;
use App\Models\classes;
use App\Models\teacher;

// Student Growth per month (current year)
$monthlyStudents = [];
$months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

foreach($months as $index => $month){
    $monthlyStudents[] = Student::whereYear('created_at', date('Y'))
                                ->whereMonth('created_at', $index+1)
                                ->count();
}

// Teachers per class
$classes = classes::with('classTeacher')->get();
$teacherNames = $classes->map(fn($c) => $c->classTeacher ? $c->classTeacher->name : 'Unassigned'); // Assuming one teacher per class in your setup
$classNames = $classes->pluck('class_name'); // Adjust according to your column name
@endphp




@php

$totalstudent = count(App\Models\student::all());
$totalsubject = count(App\Models\subject::all());
$totalclass = count(App\Models\classes::all());
$totalteachers = count(App\Models\teacher::all());

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
</style>

<div class="container-fluid">
                        
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0">Dashboard</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                                            <li class="breadcrumb-item active">Dashboard</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

<div class="row">
    <!-- Total Students -->
    <div class="col-xl-3 col-md-6">
        <div class="card dashboard-card shadow-lg border-0 rounded-4 text-white" style="background: linear-gradient(135deg, #6a11cb, #2575fc);">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="mb-1 fw-semibold">Total Students</p>
                        <h3 class="fw-bold mb-0" style="color: #fff;">{{ $totalstudent }}</h3>
                    </div>
                    <div class="avatar-sm bg-white bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center">
                        <i class="ri-user-3-line fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Subjects -->
    <div class="col-xl-3 col-md-6">
        <div class="card dashboard-card shadow-lg border-0 rounded-4 text-white" style="background: linear-gradient(135deg, #00b09b, #96c93d);">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="mb-1 fw-semibold">Total Subjects</p>
                        <h3 class="fw-bold mb-0" style="color: #fff;">{{ $totalsubject }}</h3>
                    </div>
                    <div class="avatar-sm bg-white bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center">
                        <i class="fas fa-book-open fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Classes -->
    <div class="col-xl-3 col-md-6">
        <div class="card dashboard-card shadow-lg border-0 rounded-4 text-white" style="background: linear-gradient(135deg, #ff416c, #ff4b2b);">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="mb-1 fw-semibold">Total Classes</p>
                        <h3 class="fw-bold mb-0" style="color: #fff;">{{ $totalclass }}</h3>
                    </div>
                    <div class="avatar-sm bg-white bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center">
                        <i class="ri-building-line fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Teachers -->
    <div class="col-xl-3 col-md-6">
        <div class="card dashboard-card shadow-lg border-0 rounded-4 text-white" style="background: linear-gradient(135deg, #36d1dc, #5b86e5);">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="mb-1 fw-semibold">Total Teachers</p>
                        <h3 class="fw-bold mb-0" style="color: #fff;">{{ $totalteachers }}</h3>
                    </div>
                    <div class="avatar-sm bg-white bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center">
                        <i class="fas fa-book-reader fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


                        
                    </div>




                    <div class="row mt-4">
    <!-- Student Growth Chart -->
    <div class="col-xl-6 col-md-12">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0" style="color: #fff;">Student Growth (Monthly)</h5>
            </div>
            <div class="card-body">
                <canvas id="studentGrowthChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Teachers per Class Chart -->
    <div class="col-xl-6 col-md-12">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0" style="color: #fff;">Teachers per Class</h5>
            </div>
            <div class="card-body">
                <canvas id="teachersPerClassChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

         



<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Student Growth Chart
    const studentCtx = document.getElementById('studentGrowthChart').getContext('2d');
    const studentGrowthChart = new Chart(studentCtx, {
        type: 'line',
        data: {
            labels: @json($months),
            datasets: [{
                label: 'Students',
                data: @json($monthlyStudents),
                backgroundColor: 'rgba(102, 126, 234, 0.2)',
                borderColor: 'rgba(102, 126, 234, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });

    // Teachers per Class Chart
  const classNames = @json($classNames);
  const teacherNames = @json($teacherNames);

new Chart(document.getElementById('teachersPerClassChart'), {
    type: 'bar',
    data: {
        labels: classNames,
        datasets: [{
            label: 'Class Teachers',
            data: teacherNames.map(name => 1), // each teacher counts as 1
            backgroundColor: 'rgba(54, 209, 220, 0.7)',
            borderColor: 'rgba(54, 209, 220, 1)',
        }]
    },
    options: {
        indexAxis: 'x',
        plugins: {
            tooltip: {
                callbacks: {
                    label: (context) => teacherNames[context.dataIndex]
                }
            }
        }
    }
});

</script>


    @endsection