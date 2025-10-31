@extends('backend.student_account.student_dashboard')
@section('student')



    <div class="container-fluid">

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <h4 class="p-2 bg-info text-white rounded" style="text-align:center">CHECK | RESULT</h4>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title">Select Class, Term and Academic Session  </h4>
              
                <form action="{{route('student.result.view')}}" method="post">
                @csrf

                    @if(session('error'))
                    <div class="alert alert-danger">
                        {{session('error')}}
                    </div>
                    @endif
                    
                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label" style="font-size:15px">Class</label>
                    <div class="col-sm-10">
                    <select  name="class_id" required  aria-label="Default select example" class="form-select">
                         <option selected value="">-- Select Class --</option>

                         @foreach($classes as $class)
                        <option value="{{$class->id}}">{{$class->class_name}}</option>
                         @endforeach
                                                    
                        </select>
                    </div>
                   
                </div> 
                   <!-- end row -->


                   <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label" style="font-size:15px">Term</label>
                    <div class="col-sm-10">
                    <select  name="term" required class="form-select" aria-label="Default select example">
                         <option selected value="">--Select Term--</option>

                         @foreach($terms as $term)
                        <option value="{{$term}}">{{strtoupper($term)}}</option>
                         @endforeach
                                                    
                        </select>
                    </div>
                   
                </div>
                     <!-- end row -->
                


                     <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label" style="font-size:15px">Session</label>
                    <div class="col-sm-10">
                    <select  name="session" required class="form-select" aria-label="Default select example">
                         <option selected value="">--Select Session--</option>

                         @foreach($sessions as $session)
                        <option value="{{$session}}">{{$session}}</option>
                         @endforeach
                                                    
                        </select>
                    </div>
                   
                </div>
                

                <!-- end row -->

                <button type="submit" class="btn btn-info waves-effect waves-light"> Check Result  </button>
                
                </form>
              
            </div>
        </div>
    </div> 
</div>
  




  

@endsection

