

@extends('backend.teacher_account.teacher_dashboard')
@section('teacher')

<div class="container-fluid">

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">ADD  CBT QUESTIONS</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Add</a></li>
                    <li class="breadcrumb-item active"> CBT Questions</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->


<div class="row">
<div class="col-12">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">My CBT Tests</h4>
        @if($cbtTests->isEmpty())
        <div class="alert alert-info">
            you haven't created any CBT yet.
        </div>
    @else
        

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
        <thead>
            <tr class="table-center">
                <th><b>Title</b></th>
                <th><b>Class</b></th>
                <th><b>Subject</b></th>
                <th><b>Term</b></th>
                <th><b>Session</b></th>
                <th><b>Duration(min)</b></th>
                <th><b>Type</b></th>
                <th><b>Action</b></th>
            </tr>
        </thead>
        <tbody>
        @foreach($cbtTests as $test)
    <tr>
        <td><b>{{$test->title}}</b></td>
        <td>{{$test->class->class_name}}</td>
        <td>{{$test->subject->subject_name}}</td>
        <td>{{$test->term}}</td>
        <td>{{$test->session}}</td>
        <td align="center">{{$test->duration_minutes}}</td>
        <td>{{$test->assessment_type}}</td>
        <td>
            <div class="d-flex">
                <a href="{{ route('cbt.questions.create', $test->id) }}" class="btn btn-success me-2">Add Questions</a>
                <a href="{{ route('cbt.questions.edit', $test->id) }}" class="btn btn-warning me-2">Edit</a>
                <form action="{{ route('cbt.questions.delete.all', $test->id) }}" method="post" onsubmit="return confirm('Are you sure you want to delete this test and all related questions?')" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </td>
    </tr>
    @endforeach
        </tbody>
    </table>
    @endif

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->





@endsection






