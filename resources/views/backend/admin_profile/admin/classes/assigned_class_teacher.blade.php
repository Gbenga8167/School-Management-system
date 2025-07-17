@extends('backend.admin_profile.admin.admin_dashboard');
@section('admin')

    <div class="container-fluid">

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">ASSIGN CLASS TEACHER </h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);"> Assign Class</a></li>
                    <li class="breadcrumb-item active"> Teacher</li>
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

                <h4 class="card-title">Assign Class - Teacher </h4>
                
                <form action="{{route('store.assigned.class.teacher')}}" method="post">
                    @csrf 

                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label" style="font-size:15px">Class</label>
                    <div class="col-sm-10">
                    <select  name="class_id" required  aria-label="Default select example" class="form-select dynamic" data-dependant="student">
                         <option selected value="">-- Select Class --</option>

                         @foreach($classes as $class)
                        <option value="{{$class->id}}">{{$class->class_name}}</option>
                         @endforeach
                                                    
                        </select>
                    </div>
                   
                </div> 
                   <!-- end row -->


                   <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label" style="font-size:15px">Teacher</label>
                    <div class="col-sm-10">
                    <select  name="teacher_id" required class="form-select" aria-label="Default select example">
                         <option selected value="">--Select Teacher--</option>

                         @foreach($teachers as $teacher)
                        <option value="{{$teacher->id}}">{{$teacher->name}}</option>
                         @endforeach
                                                    
                        </select>
                    </div>
                   
                </div>
                     <!-- end row -->
                

                <!-- end row -->

                <button type="submit" class="btn btn-primary waves-effect waves-light">Assign Classs Teacher  </button>
                
                </form>
               
                

              
            </div>
        </div>
    </div> 
</div>
  



@endsection



