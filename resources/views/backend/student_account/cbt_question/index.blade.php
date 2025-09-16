@extends('backend.student_account.student_dashboard')
@section('student')
<div class="container-fluid" style="background-color:white; width:100%">

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="col-12">
        <h4 class="p-2 bg-primary text-white rounded" style="text-align:center">Student | profile</h4>
    </div>
</div>

<div class="card-body">

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

 @if(session('error'))
 <div class="alert alert-danger">
 {{session('error')}}
 </div>
 @endif
 

<!-- end page title -->
<div i class="container-fluid">
@if($cbtTests->isEmpty())
    <p class="bg-success p-4 " style="color:red; font-size:18px">No CBT tests available at the moment</p>
@else
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Class</th>
                    <th>Subject</th>
                    <th>Start Time</th>
                    <th>Duration</th>
                    <th>End Time</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cbtTests as $test)

                <tr>
                    <td>{{ $test->class->class_name }}</td>
                    <td>{{ $test->subject->subject_name }}</td>
                    <td>{{$test->start_time }}</td>
                    <td>{{ $test->duration_minutes }}</td>
                    <td>{{ $test->end_time ?? 'No End Time ' }}</td>
                    <td>
                             
                          <a href="{{ route('student.cbt.test', $test->id) }}" class="btn btn-primary">Start Test</a>
                           
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
</div>

@endsection