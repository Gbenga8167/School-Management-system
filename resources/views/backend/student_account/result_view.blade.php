@extends('backend.student_account.student_dashboard')
@section('student')

@php
    use App\Models\SchoolSetting;
    $schoolSetting = SchoolSetting::first();
@endphp

<style>

table.deep-border tbody td,
    table.deep-border tbody th{
        border:0.5px solid black;
    }

    .table-responsive{
        position:relative;
    }
    .fixed-header thead th{
        position:sticky;
        top:0;
        z-index:10;
        background-color:#343a40;
        color:#fff;
    }


/*hide on print elements*/
@media print{
    body::before{
            content:"";
            position:fixed;
            background: url('{{ $schoolSetting && $schoolSetting->logo ? asset("uploads/logo_images/" . $schoolSetting->logo) : asset("uploads/default.png") }}');
            background-size:cover;
            background-position:top;
            opacity:0.1;
            width:100%;
            height:70%;
            margin-top:300px;
            z-index:-1;
        }

        body{
           -webkit-print-color-adjust:exact !important;
           print-color-adjust:exact !important;
           color-adjust:exact !important;
        }

        /*Hide element that shouldnt print*/

        .no-print{
            display:none !important;

        }
}

@media(max-width:576px){
        .table-responsive{
            max-height:100vh;
        }
    }


</style>


<div class="no-print">

<h4 class="text-center">Result Sheet</h4>
    <div class="mb-4">
        <p><strong>Student : </strong><b style="color:green">{{ucwords(strtolower($student->name))}}</b></p>
        <p> <strong>Class : </strong> {{$class->class_name}}</p>
        <p> <strong>Term : </strong> {{$terms}}</p> 
        <p> <strong>Session : </strong> {{$sessions}}</p>
    </div>
    <hr style="color:green">
</div>
   


{{-- student result info goes here --}}

<div class="table-responsive" style="max-height:auto; overflow:auto" id="result-section">

     <table class="border-collapse:collapse;" align='center' style="width:90%;">
    <tr>
        <td style="width:20%; padding:4px; text-align:right;">
            @if(!empty($settings?->logo))
            <img src="{{asset('uploads/logo_images/'. $settings->logo)}}" alt="School Logo" style="width:80px; height:auto;">

            @else
            <div style="width:80px; height:80px; background:#eee; 
            display:flex; align-item:center;justify-content:center;font-size:12px;">
            LOGO
            </div>
           @endif
        </td>

        <td style="width:60%; padding:4px; text-align:center;">
            <h3 style="margin-top:50px;">{{strtoupper($settings?->name ?? 'AGM SMART SCHOOL')}}</h3>
            <small style="font-size:15px">{{$settings?->address ?? 'Address here'}}</small>
            
            @if($settings->motto)
            <br>

            <em style="font-size:15px;"><h6 style="font-weight:bold;">Motto : {{ucwords(strtolower($settings->motto))}}</h6</em>
            @endif

        </td>


        <td style="width:20%; padding:4px; text-align:left;">
            
        @if($student->photo)
            <img src="{{asset('uploads/student_photos/'. $student->photo)}}" alt="" style="width:80px; height:80px; object-fit:cover;border-radius:4px;">

         @else
            <div style="width:80px; height:80px; background:#eee; 
            display:flex; align-item:center; justify-content:center; font-size:11px; border-radius:4px;">
            {{\Illuminate\support\str::limit($student->name,12)}}
            
            </div>
           @endif
        </td>
       
    </tr>

    <tr style="width:20%; padding:4px; text-align:center; font-size:13px;font-weight:bold; ">
    <td colspan="3"  style="height:50px; font-size:18px;">
           <h6 style="font-weight:bold;"> PUPIL'S PERFORMANCE REPORTS FOR {{$sessions}} ACCADEMIC SESSION <br> PUPIL'S ID : {{$student->roll_id}} </h6>
    </td>
    </tr>

    <tr style="font-size:13px;font-weight:bold;">
        <td style="text-align: center;">
           <h6 style="font-weight:bold; font-size:13px;"> Academic Session : {{$sessions}} </h6>
        </td>

        <td style="text-align:center;">
        <h6 style="font-weight:bold;">TERM : {{strtoupper($terms)}}</h6>
        </td>

        <td style="text-align:center;">
        <h6 style="font-weight:bold;"> CLASS : {{ strtoupper($class->class_name)}} </h6>
        </td>


    </tr>

    <tr style="font-size:13px;">
    <td style="text-align:center;">
           <h6 style="font-weight:bold;" > PUPIL'S NAME :  <b style="color:green">{{strtoupper($student->name)}}</b> </h6>
        </td>

        <td style="text-align:center;">
        <h6 style="font-weight:bold;">Total Score : {{$totals['score']}}</h6> 
        </td>

        <td style="text-align:center;">
        <h6 style="font-weight:bold;">

            Percentage Scores : {{$totals['percentage']}}% <br>
            &nbsp; Remark : {{strtoupper($totals['remark'])}}

 </h6>
       
       
        </td>
    </tr>



 </table>


<!--END HEADER -->


    <table class="table table-bordered deep-border table-striped mb-4 fixed-header" style="width:90%;" align="center">
    <thead class="table-dark text-center align-middle">
            <tr>
                <th>SN</th>
                <th>Subjects</th>
                <th>CA1</th>
                <th>CA2</th>
                <th>CA3</th>
                <th>EXAM</th>
                <th>TOTAL</th>
                <th>GRADE</th>
                <th>REMARK</th>
            </tr>
        </thead>

        <tbody >

    <tr class="text-center">
       
        <th></th>
        <th></th>
        <th><b>10%</b></th>
        <th><b>10%</b></th>
        <th><b>10%</b></th>
        <th><b>70%</b></th>
        <th><b>100%</b></th>
        <th></th>
        <th></th>      
    </tr>

           @php $count=1 @endphp
            @foreach($results as $r)
            <tr class="text-center">
                <td>{{$count++}}</td>
                <td class="text-start"><b>{{ucwords(strtolower($r->subject->subject_name ?? 'N/A'))}}</b></td>
                <td><b>{{round($r->ca1)}}</b></td>
                <td><b>{{round($r->ca2)}}</b></td>
                <td><b>{{round($r->ca3)}}</b></td>
                <td><b>{{round($r->exam)}}</b></td>
                <td><b>{{round($r->total)}}</b></td>
                <td><b>{{$r->grade}}</b></td>
                <td><b>{{$r->remark}}</b></td>

            </tr>
            @endforeach  
        </tbody>
    </table>

    @if($assessment)
    <table class="table table-bordered table-sm mb-4 deep-border table-striped" style="width:90%;" align="center">

   <thead class="table-secondary text-center">
      <tr>
             <th colspan="5" class="table-dark">Psychomotor / Affective Assessment</th>
      </tr>
      <tr class="fw-bold" >
             <td>Attendance</td>
             <td>Punctuality</td>
             <td>Neatness</td>
             <td>Honesty</td>
             <td>Music</td>
      </tr>
</thead>
<tbody class="text-center">
      <tr>
             <td><b>{{$assessment->attendance}}</b></td>
             <td><b>{{$assessment->punctuality}}</b></td>
             <td><b>{{$assessment->neatness}}</b></td>
             <td><b>{{$assessment->honesty}}</b></td>
             <td><b>{{$assessment->musical}}</b></td>
      </tr>

      <tr class="fw-bold table-secondary">
             <th>initiative</th>
             <th>creativity</th>
             <th>sport</th>
             <th>perseverance</th>
             <th>co-operation</th>
      </tr>
      <tr>
             <td><b>{{$assessment->initiative}}</b></td>
             <td><b>{{$assessment->creativity}}</b></td>
             <td><b>{{$assessment->sport}}</b></td>
             <td><b>{{$assessment->perseverance}}</b></td>
             <td><b>{{$assessment->cooperation}}</b></td>
      </tr>
</tbody>
</table>
@endif

<table class="table" style="width:90%;" align="center">
        <tr >
        <td style="width:70%" colspan ="3">
            <strong style="font-size:15px;"><u>Class Teacher's Comment</u> : </strong> 
              {{ucwords(strtolower($assessment->teacher_comment ?? '_______'))}}
              <br>

              <strong style="font-size:15px;"><u>Principal's Comment</u> : </strong>
            {{ucwords(strtolower($assessment->principal_comment ?? '_______'))}}
            <br>

            <strong style="font-size:15px;"><u>Next Term Begins </u>: </strong>
            {{isset($nextTermBegins) ? \carbon\carbon::parse($nextTermBegins)->format('l, jS F, Y') :'________'}}<br>
           <!-- how to add student name at the comment session
             {{ucwords(strtolower($student->name?? 'N/A'))}} -->
       </td>

       {{--END Table Footer  TEACHER AND PRINCIPAL COMMENT --}}


       {{-- Table Footer  SCHOOL STAMP --}}
       <td style="width:20%; padding:4px; text-align:center;"> 
           @if(!empty($schoolSetting->stamp))
           <img src="{{ asset('uploads/stamp_images/'.$schoolSetting->stamp) }}" 
           alt="School Stamp" width="120" style="width:80px; height:auto;">
           @endif
        </td>
        {{-- END Table Footer  SCHOOL STAMP --}}
      </tr>
      <td>
 
    <button onclick="window.print()" class="btn btn-primary no-print">Print Results</button>
   <!-- <button onclick="downloadPDF()" class="btn btn-danger no-print">Download as PDF</button> -->


     </td>
      </table>  
      {{-- end student result info goes here --}}



</div>



<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
    function downloadPDF(){
        const element = document.getElementById('result-section');
        const options = {
            margin:0,
            filename: 'student-result.pdf',
            image:{type: 'jpeg',
                quality:0.98
            },
            html2canvas: {scale:2},
            jsPDF:{unit:'in', format: 'A4', orientation:'portrait'},

        };
        html2pdf().set(options).from(element).save();
    }
</script>





@endsection

