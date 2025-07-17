@extends('backend.teacher_account.teacher_dashboard')
@section('teacher')

    <div class="container-fluid">

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">PSYCHOMOTOR | COMMENT</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Pyschomotor</a></li>
                    <li class="breadcrumb-item active"> Comment</li>
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

                <h4 class="card-title">Select Class, Term and Academic Session  </h4>
              
                <form action="{{route('load.psychomotor')}}" method="get">
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
                    <select  name="term_id" required class="form-select" aria-label="Default select example">
                         <option selected value="">--Select Term--</option>

                         @foreach($terms as $term)
                        <option value="{{$term->id}}">{{$term->name}}</option>
                         @endforeach
                                                    
                        </select>
                    </div>
                   
                </div>
                     <!-- end row -->
                


                     <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label" style="font-size:15px">Session</label>
                    <div class="col-sm-10">
                    <select  name="session_id" required class="form-select" aria-label="Default select example">
                         <option selected value="">--Select Session--</option>

                         @foreach($sessions as $session)
                        <option value="{{$session->id}}">{{$session->name}}</option>
                         @endforeach
                                                    
                        </select>
                    </div>
                   
                </div>
                

                <!-- end row -->

                <button type="submit" class="btn btn-primary waves-effect waves-light"> Load Student  </button>
                
                </form>
              
            </div>
        </div>
    </div> 
</div>
  




  

@endsection



