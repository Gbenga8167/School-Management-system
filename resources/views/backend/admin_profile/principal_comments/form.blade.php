@extends('backend.admin_profile.admin.admin_dashboard')
@section('admin')

<div class="container-fluid">

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Principal | Comment </h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Principal</a></li>
                    <li class="breadcrumb-item active">Comment</li>
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

                <h4 class="card-title">principal Comment</h4>

                @if(session('error'))
                <div class="alart alert-danger" style="padding:10px 5px; margin-buttom:15px; font-size:17px">
                    {{session('error')}}
                </div>
                 @endif

                 @if(session('success'))
                <div class="alart alert-success" style="padding:10px 5px; margin-buttom:15px; font-size:17px">
                    {{session('success')}}
                </div>
                 @endif
              
                <form action="{{route('principal.comment.load')}}" method="get">
                  @csrf

                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label" style="font-size:15px">Class</label>
                    <div class="col-sm-10">
                    <select  name="class_id" required class="form-select" aria-label="Default select example">
                       
                        <option  value="">-- Select Class --</option>
                        @foreach($classes as $class)
                        <option value="{{$class->id}}" {{isset($selected_class_id) && $selected_class_id == $class->id ? 'selected' : ''}}>{{$class->class_name}}</option>
                        @endforeach
                    </select>
                    </div>
                   
               </div>
                 <!-- end row -->



                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label" style="font-size:15px">Term</label>
                    <div class="col-sm-10">
                    <select  name="term" required class="form-select" aria-label="Default select example">
                       
                        <option  value="">-- Select Class --</option>
                        @foreach($terms as $term)
                        <option value="{{$term->name}}" {{isset($selected_term) && $selected_term == $class->name ? 'selected' : ''}}>{{$term->name}}</option>
                        @endforeach
                    </select>
                    </div>
                   
               </div>
                <!-- end row -->


                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label" style="font-size:15px">Academic Session</label>
                    <div class="col-sm-10">
                    <select  name="session" required class="form-select" aria-label="Default select example">
                       
                        <option  value="">-- Select Session --</option>
                        @foreach($sessions as $session)
                        <option value="{{$session->name}}" {{isset($selected_session) && $selected_session == $class->name? 'selected' : ''}}>{{$session->name}}</option>
                        @endforeach
                    </select>
                    </div>
                   
               </div>
                <!-- end row -->

                <button type="submit" class="btn btn-primary waves-effect waves-light"> Load Students </button>

                </form>


               
              
                @isset($students)
                @if(count($students)>0)
                
                <form action="{{route('principal.comment.submit')}}" method="post">
                @csrf
                <input type="hidden" name="class_id" value="{{$selected_class_id}}">
                <input type="hidden" name="term" value="{{$selected_term}}">
                <input type="hidden" name="session" value="{{$selected_session}}">

                <hr>
                @php
                $selectedClass = $classes->firstwhere('id', $selected_class_id);
                @endphp

                @if($selectedClass)
                <h5 class="text-center mb-3"> 
                    Students Comments for <strong>{{$selectedClass->class_name}}</strong>
                <span class="text-muted">{{$selected_term}} | {{$selected_session}}</span>
               </h5>
               @endif
                

                
              <table class="table table-bordered">
             <thead class="table-dark text-center">
             <tr>
            <th>Student Name</th>
            <th>Principal's Comment</th>
            </tr>

            </thead>
            <tbody class="text-center">
            @foreach($students as $student)
            <tr>
            <td>
                {{ucwords(strtolower($student->name))}}
            </td>

            <td>
              <textarea name="comments[{{$student->id}}]" class="form-control" rows="2">{{ucwords(strtolower($student->assessment->principal_comment ?? ''))}}</textarea>  
            </td>
           </tr>

            @endforeach
           

          </tbody>
        

           </table>
           <input type="submit" class="btn btn-success" value="Save Comments" >
            </form>

            @endif
             @endisset

</div>
</div>
</div>
</div>


@endsection




