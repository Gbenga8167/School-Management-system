@extends('backend.admin_profile.admin.admin_dashboard')
@section('admin')


    <style>
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button{
    -webkit-appearance:none;
    margin:0;
}

input[type=number]{
    -moz-appearance:textfield;
}

input[type=text]{
    border:none;
    outline:none;
}

th{
    color:#fff;
    font-size:16px;
}

.data-column{
    text-align:center;
}


    </style>

    

<div class="row">
     <div class="col-12">
            <div class="card">
                <div class="card-body">

                  <form action="{{route('store.admin.result')}}" method="POST">
                    @csrf 
                    <input type="hidden" name="class_id" value="{{$classId}}">
                    <input type="hidden" name="term" value="{{$terms->name}}">
                    <input type="hidden" name="session" value="{{$sessions->name}}">
                            
                                    <h4 class="card-title"> Result Entry For -{{$classes->class_name}} - 
                                    {{ucfirst($terms->name)}} - {{$sessions->name}}</h4>

                                     @foreach($subjects as $subject)
                                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing:0; width: 100%;" border="0">
                                        
                                        @php $serialNumber = 1 @endphp

                                        <h4 class="card-title">{{$subject->subject_name}}</h4>

                                             <thead bgcolor="dodgerblue">
                                            <tr align="center">
                                                
                                                <th>S/N</th>
                                                <th>Student Name</th>
                                                <th>CA1</th>
                                                <th>CA2</th>
                                                <th>Exam</th>
                                                <th>Total</th>
                                                <th>Grade</th>
                                                <th>Remark</th>
                                        
                                                
                                            
                                            </tr>
                                            </thead>
                                            
                                            <tbody>
                                                @foreach($students as $index => $student)


                                             @php 
                                             $key = $subject->id.'_'.$student->id;
                                            $existing = $results[$key][0] ?? null;
                                            @endphp

                                            @if(isset($assignments[$key]))

                                            <tr>

                                               <td>{{ $serialNumber++ }}</td>

                                                <td><h4 class="card-title">{{ $student->name ?? 'Name Missing'}} </h4></td>

                                            

                                                <td class="data-column">
                                                    <input type ="number"  name="results[{{$subject->id}}][{{$index}}][ca1]" max="20" min="0" 
                                                    value="{{round($existing?->ca1) ?? 0}}" class="score-input" 
                                                    data-index="{{$index}}" data-type="ca1" 
                                                    data-student-id="{{$student->id}}" size="3px" style="text-align:center">
                                            </td>

                                                
                                            <td class="data-column">
                                                    <input type ="number"  name="results[{{$subject->id}}][{{$index}}][ca2]" max="20" min="0" 
                                                    value="{{round($existing?->ca2) ?? 0}}" class="score-input" 
                                                    data-index="{{$index}}" data-type="ca2" 
                                                    data-student-id="{{$student->id}}" size="3px" style="text-align:center">
                                            </td>

                                            <td class="data-column">
                                                    <input type ="number"  name="results[{{$subject->id}}][{{$index}}][exam]" max="60" min="0" 
                                                    value="{{round($existing?->exam) ?? 0}}" class="score-input" 
                                                    data-index="{{$index}}" data-type="exam" 
                                                    data-student-id="{{$student->id}}" size="3px" style="text-align:center">
                                            </td>

                                            <td class="data-column">
                                                   <input type="text" readonly id="total{{$index}}" value="{{round($existing?->total) ?? 0}}" size="3px" style="text-align:center">
                                            </td>

                                            <td class="data-column">
                                                   <input type="text" readonly id="grade{{$index}}" value="{{$existing?->grade ?? '-'}}" size="3px" style="text-align:center">
                                            </td>

                                            <td class="data-column">
                                                   <input type="text" readonly id="remark{{$index}}" value="{{$existing?->remark ?? '-'}}" size="10px" style="text-align:center">
                                            </td>

                                            <input type="hidden" name="results[{{$subject->id}}][{{$index}}][student_id]" value ="{{$student->id}}">

                                            </tr>
                                            @endif
                                            @endforeach
                                            </tbody>
                                        </table>
                                        @endforeach

                                        <input type="submit" class="btn btn-primary waves-effect waves-light" value="Submit All Results">
                                        </form>

                                        
        
             </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

 
                                        

@endsection
