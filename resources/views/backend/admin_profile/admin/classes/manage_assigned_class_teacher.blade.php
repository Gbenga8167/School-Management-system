@extends('backend.admin_profile.admin.admin_dashboard');
@section('admin')

<div class="container-fluid">

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0"> MANAGE ASSIGNED CLASS TEACHER </h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Manage Assigned</a></li>
                    <li class="breadcrumb-item active">Class Teacher</li>
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
        
                                        <h4 class="card-title">View Assigned Class Teacher</h4>

        
                                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Class Name</th>
                                                <th>Class Teacher</th>
                                                <th>Email</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
        
        
                                            <tbody>

                                                @foreach($assignedClasses as $key => $class)

                                                <tr>
                                                <td>{{ $key+1 }}</td>
                                                <td>{{ $class->class_name }}</td>
                                                <td>{{ $class->classTeacher->name  ?? 'N/A'}}</td>
                                                <td>{{ $class->classTeacher->user->email ?? 'N/A'}}</td>
                                           <td>
                                                <form action="{{route('remove.assigned.class.teacher',  $class->id )}}"  method="POST" onsubmit ="return confirm('Are you sure you want to remove this class teacher?');">
                                                    @csrf
                                                    @method('DELETE')
                                                <button type="submit" class="btn btn-danger waves-effect waves-light">Remove Class Teacher</button>
                                                 </form>

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