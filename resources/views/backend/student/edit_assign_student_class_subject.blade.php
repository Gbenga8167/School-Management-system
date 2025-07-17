@extends('backend.admin_profile.admin.admin_dashboard');
@section('admin')

<div class="container-fluid">

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Edit - Assigned Student Class Subject</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Edit Assigned</a></li>
                    <li class="breadcrumb-item active"> Student Class Subject</li>
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

                <h4 class="card-title">Edit - Assigned Student Class Subject </h4>
              
                <form action="{{route('update.assign.student.class.subject')}}" method="post">
                    @csrf

                    <input type="hidden" name="id" value="{{$AssignSubjectstudent->id}}">

                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Student Name</label>
                    <div class="col-sm-10">
                    <select  name="student_id" class="form-select" aria-label="Default select example">
                                                   
                         @foreach($students as $student)
                         <option {{$AssignSubjectstudent->student_id == $student->id? 'selected' : ''}} value="{{$student->id}}">{{$student->name}}</option>
                         @endforeach
                         
                         </select>
                    </div>
                   
                </div>



                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Class</label>
                    <div class="col-sm-10">
                    <select  name="class_id" class="form-select" aria-label="Default select example" required>
                                                
                       @foreach($classes as $class)
                       <option {{$AssignSubjectstudent->class_id == $class->id? 'selected' : ''}} value="{{$class->id}}">{{$class->class_name}}</option>
                       @endforeach
                       
                       </select>
                    </div>
                   
                </div>



                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Subject</label>
                    <div class="col-sm-10">
                    <select  name="subject_id" class="form-select" aria-label="Default select example" required>

                        @foreach($subjects as $subject)
                        <option {{$AssignSubjectstudent->subject_id == $subject->id? 'selected' : ''}} value="{{$subject->id}}">{{$subject->subject_name}}</option>
                        @endforeach
                                                    
                    </select>
                    </div>
                   
                </div>




                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Term</label>
                    <div class="col-sm-10">
                    <select  name="term" class="form-select" aria-label="Default select example" required>
                             
                    <option {{$AssignSubjectstudent->term == 'first_term'? 'selected' : ''}}>First Term</option>
                    <option {{$AssignSubjectstudent->term == 'second_term'? 'selected' : ''}}>Second Term</option>
                    <option {{$AssignSubjectstudent->term == 'third_term'? 'selected' : ''}}>Third Term</option>
                                                    
                                                    
                    </select>
                    </div>
                   
                </div>


                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Session</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="session"  type="text" value="{{$AssignSubjectstudent->session}}" required>
                       
                    </div>
                   
                </div>



           

                <!-- end row -->

                <button type="submit" class="btn btn-primary waves-effect waves-light">Update Assigned student Class subject</button>
                
                </form>
               
                

              
            </div>
        </div>
    </div> 
</div>
  


@endsection



