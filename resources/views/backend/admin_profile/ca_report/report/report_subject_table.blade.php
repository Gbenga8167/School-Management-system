@php
if (!function_exists('caGrade')) {
    function caGrade($total) {
        if ($total >= 30) return ['A', 'Excellent'];
        if ($total >= 25) return ['B', 'Very Good'];
        if ($total >= 20) return ['C', 'Good'];
        if ($total >= 15) return ['D', 'Fair'];
        if ($total >= 10) return ['E', 'Pass'];
        return ['F', 'Fail'];
    }
}
@endphp


<table class="table table-bordered deep-border table-striped mb-4 fixed-header table-responsive" style="width:90%;" align="center">
    <thead class="table-dark text-center align-middle">
    <tr>
        <th>SN</th>
        <th>Subjects</th>
        <th>CA1</th>
        <th>CA2</th>
        <th>CA3</th>
        <th>Total</th>
        <th>Grade</th>
        <th>Remark</th>
    </tr>
    </thead>

    <tbody class="text-center">

    <tr>
        <th></th>
        <th></th>
        <th><b>10%</b></th>
        <th><b>10%</b></th>
        <th><b>10%</b></th>
        <th><b>30%</b></th>
        <th></th>
        <th></th>
    </tr>

    @php $count = 1 @endphp
    @foreach($results as $result)
    @php 
        $totalCA = round($result->ca1 + $result->ca2 + $result->ca3);
        [$grade, $remark] = caGrade($totalCA);
    @endphp
    <tr>
        <td>{{$count++}}</td>
        <td class="text-start"><b>{{ ucwords(strtolower($result->subject->subject_name ?? 'N/A')) }}</b></td>
        <td><b>{{ round($result->ca1) }}</b></td>
        <td><b>{{ round($result->ca2) }}</b></td>
        <td><b>{{ round($result->ca3) }}</b></td>
        <td><b>{{ $totalCA }}</b></td>
        <td><b>{{ $grade }}</b></td>
        <td><b>{{ $remark }}</b></td>
    </tr>
    @endforeach
    </tbody>
</table>
