@extends('backend.admin_profile.admin.admin_dashboard');
@section('admin')

    <div class="container-fluid">

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">ASSIGN STUDENT SUBJECT & CLASS</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Assign Student</a></li>
                    <li class="breadcrumb-item active"> Subject Class</li>
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

                <h4 class="card-title">Assign Student Class - Subject </h4>
              
                <form action="{{route('store.student.class.subject')}}" method="post">
                    @csrf 

                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label" style="font-size:15px">Student Name</label>
                    <div class="col-sm-10">
                    <select  name="student_id" required class="form-select" aria-label="Default select example">
                         <option selected value="">--Select Student--</option>

                         @foreach($students as $student)
                        <option value="{{$student->id}}">{{$student->name}}</option>
                         @endforeach
                                                    
                        </select>
                    </div>
                   
                </div>
                     <!-- end row -->


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




                 <div class="row mb-3 showSubject">
                    <label for="example-text-input" class="col-sm-2 col-form-label" style="font-size:15px">Subject</label>
                  <div class="col-sm-10 sub">
   
                   </div>
                </div>
          <!-- end row -->
                

                
          <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label" style="font-size:15px">Team</label>
                    <div class="col-sm-10">
                    <select  name="term" required class="form-select" aria-label="Default select example">
                    <option value="first_term" selected> First Term </option>  
                    <option value="second_term"> Second Term </option> 
                    <option value="third_term"> Third Term </option>                         
                     </select>
                    </div>
                   
                </div>
            <!-- end row -->


                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label" style="font-size:15px">Academic Session</label>
                    <div class="col-sm-10">
                    <select  name="session" required class="form-select" aria-label="Default select example">
                    <option value="2025/2026" selected> 2025/2026 </option>                          
                     </select>
                    </div>
                   
                </div>


                <!-- end row -->

                <button type="submit" class="btn btn-primary waves-effect waves-light">Assign Student Subject & Class </button>
                
                </form>
               
                

              
            </div>
        </div>
    </div> 
</div>
  

<script>
    $(document).ready(function(){
        $('.showSubject').hide();
        $('.dynamic').on('change', function(){
            let class_id = $(this).val();
            let dependent = $(this).data('dependant');
            let _token = "{{ csrf_token() }}";
            $.ajax({
                url:"{{route('fetch.student')}}",
                method:"GET",
                datatype:"json",
                data: {class_id:class_id, _token:_token},
                success: function(result){
                    $('.sub').html(result.subjects);
                    $('.showSubject').show();

                    
                    
                }
            });

        });


    });


</script>
  

@endsection



