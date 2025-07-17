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

        
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
        
                                        <h4 class="card-title">Result - Upload </h4>

        
                                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing:0; width: 100%;" border="0">
                                        
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

                                            <tr id="row{{$index}}">

                                               <td>{{ $index + 1}}</td>

                                                <td><h4 class="card-title">{{ $student->name ?? 'Name Missing'}} </h4></td>

                                             @php 
                                            $existing = $existingResults[$student->id] ?? null;
                                            @endphp

                                                <td class="data-column">
                                                    <input type ="number"  name="results[{{$index}}][ca1]" max="20" min="0" 
                                                    value="{{round($existing?->ca1) ?? 0}}" class="score-input" 
                                                    data-index="{{$index}}" data-type="ca1" 
                                                    data-student-id="{{$student->id}}" size="3px" style="text-align:center">
                                            </td>

                                                
                                            <td class="data-column">
                                                    <input type ="number"  name="results[{{$index}}][ca2]" max="20" min="0" 
                                                    value="{{round($existing?->ca2) ?? 0}}" class="score-input" 
                                                    data-index="{{$index}}" data-type="ca2" 
                                                    data-student-id="{{$student->id}}" size="3px" style="text-align:center">
                                            </td>

                                            <td class="data-column">
                                                    <input type ="number"  name="results[{{$index}}][exam]" max="60" min="0" 
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

                                        <input type="submit" class="btn btn-primary waves-effect waves-light" value="Submit All Results">
                                        </form>

                                        </table>
        
        </div>
    </div>
</div> <!-- end col -->
</div> <!-- end row -->

 
                                        
<script>
/*
const maxScores = { ca1:20, ca2:20, exam:60};
const term ="{{$term}}";
const session ="{{$session}}";
const classId ="{{$class_id}}";
const subjectId ="{{$subject_id}}";

document.querySelectorAll('.score-input').forEach(input=>{
    input.addEventListener('input', function({
        const index = this.dataset.index;
        const studentId = this.dataset.studentId;
        const row = document.getElementById(`row-${index}`);

        const ca1 = parseFloat(document.querySelector(`[data-index="${index}"][data-type="ca1"]`).value)|| 0;
        const ca2 = parseFloat(document.querySelector(`[data-index="${index}"][data-type="ca2"]`).value)|| 0;
        const exam = parseFloat(document.querySelector(`[data-index="${index}"][data-type="exam"]`).value)|| 0;

        //validation
        let hasError = false;
        ['ca1', 'ca2', 'exam'].forEach(type =>{
            const inp = document.querySelector(`[data-index="${index}"][data-type="${type}"]`);
            if(parseFloat(inp.value) > maxScores[type]){
                inp.classList.add('input-error');
                hasError = true;

            }else{
                inp.classList.remove('input-error');
            }
        });
        if(hasError) return;

        //Ajax save

        fetch("{{}}", {
            method : 'POST',
            headers : {
                'content-Type' : 'application/json',
                'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },

            body: JSON.stringify({
                student_id : subjectId,
                class_id : classId,
                subject_id :subjectId,
                term:term,
                session:session,
                ca1:ca1;
                ca2 : ca2,
                exam: exam
        
            })
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById(`total-${index}`).value = data.total;
            document.getElementById(`grade-${index}`).value = data.grade;
            document.getElementById(`remark-${index}`).value = data.remark;
            row.classList.add('row-success');
        })

        .catch(err => console.error(err));
    }));
});


</script>








@endsection
