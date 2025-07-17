<div class="table-responsive" style="max-height:auto; overflow:auto">

<table class="table table-bordered deep-border table-striped mb-4 fixed-header" style="width:90%;" align="center">
    <thead class="table-dark text-center align-middle">

    <tr>
        <th>SN</th>
        <th >Subjects</th>
        <th>CA1</th>
        <th>CA2</th>
        <th >Exam</th>
        <th >Total</th>
        <th>Grade</th>
        <th>Remark</th>
    </tr>
</thead>

<tbody class="text-center">

<tr>
        <th></th>
        <th></th>
        <th><b>20%</b></th>
        <th><b>20%</b></th>
        <th> <b>60%</b></th>
        <th><b>100%</b></th>
        <th></th>
        <th></th>
    </tr>

    @php $count=1 @endphp
    @foreach($results as $result)
    <tr>
        <td>{{$count++}}</td>
        <td class="text-start"><b>{{ucwords(strtolower($result->subject->subject_name ?? 'N/A'))}}</b></td>
        <td><b>{{round($result->ca1)}}</b></td>
        <td><b>{{round($result->ca2)}}</b></td>
        <td><b>{{round($result->exam)}}</b></td>
        <td><b>{{round($result->total)}}</b></td>
        <td><b>{{$result->grade}}</b></td>
        <td class="text-center"><b>{{$result->remark}}</b></td>


    </tr>
    @endforeach
</tbody>
</table>

<p style="text-align:center; font-size:15px;"><b>Hints : A-Excellent:70 above, B-Good:60-69, C-Credit:50-59, D-Pass:44-49, E-Weak Pass: 40-44, F-Fail:0-39</b></p>

</div>