
@extends('backend.admin_profile.admin.admin_dashboard');
@section('admin')

<div class="container-fluid">

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">MANAGE ASSIGNED SUBJECT TEACHER </h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Manage Assign</a></li>
                    <li class="breadcrumb-item active"> Teachers</li>
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
        
                                        <h4 class="card-title">Manage Assign Teachers Info</h4>

        
                                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Teacher</th>
                                                <th>Class</th>
                                                <th>Subject</th>
                                                <th>Actions</th>
                                            
                                            </tr>
                                            </thead>
        
        
                                            <tbody>
                                            @foreach($manageAssigns as $key => $assign)

                                                <tr>
                                                <td>{{ $key+1 }}</td>
                                                <td>{{ $assign->teacher->name }}</td>
                                                <td>{{ $assign->class->class_name }}</td>
                                                <td>{{ $assign->subject->subject_name }}</td>
                                                <td > <a href="{{route('edit.assign.subject.teacher', $assign->id)}}">
                                                <button type="submit" class="btn btn-primary waves-effect waves-light">Edit</button>
                                                </a> 

                                                
                                                <a href="{{route('delete.assign.subject.teacher', $assign->id)}}" id="delete">
                                                <button type="submit"  class="btn btn-danger waves-effect waves-light">Delete</button>
                                                </a>

                                            </td>
                                               
                                            </tr>
                                            

                                                @endforeach

                                            </tr>
                                            </tbody>
                                        </table>
        
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->





@endsection