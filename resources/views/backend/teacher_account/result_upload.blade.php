@extends('backend.teacher_account.teacher_dashboard')
@section('teacher')

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
      <form action="{{route('teacher.result.store')}}" method="POST">
                    @csrf 
                    <input type="hidden" name="class_id" value="{{$class_id}}">
                    <input type="hidden" name="term" value="{{$term}}">
                    <input type="hidden" name="session" value="{{$session}}">
                    <input type="hidden" name="subject_id" value="{{$subject_id}}">
                            
        
                  
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                       <h4 class="card-title"> Result Entry for  {{$classes->class_name}} - {{$subjects->subject_name}} - 
                                        {{ucfirst($term)}} - {{$session}} </h4>

                                        <h4 class="card-title">Result - Upload </h4>

        
                                        <table id="datatable" class="table table-bordered dt-responsive nowrap" 
                                        style="border-collapse: collapse; border-spacing:0; width: 100%;" border="0">
                                         
                                             <thead class="table-dark">
                                            <tr align="center">
                                                
                                                <th>S/N</th>
                                                <th>Student Name</th>
                                                <th>CA1</th>
                                                <th>CA2</th>
                                                <th>CA3</th>
                                                <th>Exam</th>
                                                <th>Total</th>
                                                <th>Grade</th>
                                                <th>Remark</th>
                                        
                                                
                                            
                                            </tr>
                                            </thead>
                                            
                                            <tbody>
                                                @foreach($students as $index => $student)

                                            <tr id="row{{$index}}">

                                               <td>{{ $index + 1}}</td>

                                                <td><h4 class="card-title">{{ $student->name ?? 'Name Missing'}} </h4></td>

                                             @php 
                                            $existing = $existingResults[$student->id] ?? null;
                                            @endphp

                                                <td class="data-column">
                                                    <input type ="number"  name="results[{{$index}}][ca1]" max="10" min="0" 
                                                    value="{{round($existing?->ca1) ?? 0}}" class="score-input" 
                                                    data-index="{{$index}}" data-type="ca1" 
                                                    data-student-id="{{$student->id}}" size="3px" style="text-align:center">
                                            </td>

                                                
                                            <td class="data-column">
                                                    <input type ="number"  name="results[{{$index}}][ca2]" max="10" min="0" 
                                                    value="{{round($existing?->ca2) ?? 0}}" class="score-input" 
                                                    data-index="{{$index}}" data-type="ca2" 
                                                    data-student-id="{{$student->id}}" size="3px" style="text-align:center">
                                            </td>


                                            <td class="data-column">
                                                    <input type ="number"  name="results[{{$index}}][ca3]" max="10" min="0" 
                                                    value="{{round($existing?->ca3) ?? 0}}" class="score-input" 
                                                    data-index="{{$index}}" data-type="ca3" 
                                                    data-student-id="{{$student->id}}" size="3px" style="text-align:center">
                                            </td>


                                            <td class="data-column">
                                                    <input type ="number"  name="results[{{$index}}][exam]" max="70" min="0" 
                                                    value="{{round($existing?->exam) ?? 0}}" class="score-input" 
                                                    data-index="{{$index}}" data-type="exam" 
                                                    data-student-id="{{$student->id}}" size="3px" style="text-align:center">
                                            </td>

                                            <td class="data-column">
                                                   <input type="text" readonly id="total{{$index}}" value="{{round($existing?->total) ?? 0}}" size="3px" style="text-align:center">
                                            </td>

                                            <td class="data-column">
                                                   <input type="text" readonly id="grade{{$index}}" value="{{$existing?->grade ?? ''}}" size="3px" style="text-align:center">
                                            </td>

                                            <td class="data-column">
                                                   <input type="text" readonly id="remark{{$index}}" value="{{$existing?->remark ?? ''}}" size="10px" style="text-align:center">
                                            </td>

                                            <input type="hidden" name="results[{{$index}}][student_id]" value ="{{$student->id}}">

                                            </tr>
                                            @endforeach
                                            
                                            </tbody>
                                        </table>

                                        <input type="submit" class="btn btn-dark waves-effect waves-light" value="Submit All Results">
                                        </form>

        
              </div>
         </div>
    </div> <!-- end col -->
</div> <!-- end row -->



@endsection
