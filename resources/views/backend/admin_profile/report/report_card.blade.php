@extends('backend.admin_profile.admin.admin_dashboard')
@section('admin')

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
    /*.report-card-page{
        page-break-after:always;
        border:1px solid black;
        padding:20px;
        margin:0;
    }
    
    .report-card-page table{
       border-collapse:collapse;
       width:100%;
    }
    .report-card-page table th,
    .report-card-page table td{
        border:1px solid black;
        padding:6px;
    }
    html, body{
        
    }*/
        
    
    @media print{
        body{
           -webkit-print-color-adjust:exact !important;
           print-color-adjust:exact !important;
           color-adjust:exact !important;
        }

        /*Hide element that shouldnt print*/

        .no-print{
            display:none !important;

        }
        img{
            max-width:100%;
            height:auto;
        }

        /*Ensure each student starts on a new page*/
        .page-break{
            page-break-after:always;

        }
        
        /* prevent margin collapse and spacing issues*/
        .student-report{
            margin-top:0;
            padding-top:0;
        }

        /*add school watermark*/
        body::before{
            content:"";
            position:fixed;
            background:url('{{asset("uploads/logo_images/logo.jpg")}}');
            background-size:cover;
            background-position:top;
            opacity:0.05;
            width:100%;
            height:70%;
            margin-top:300px;
            z-index:-1;
        }

        /*clean up table border

        table,th,td{
            border:1px solid black !important;
            border-collapse:collapse !important;

        }
        th, td{
            padding:6px !important;

        }*/

        /* Normal layout spacing for student section*/
        .student-report{
            margin-bottom:20px;
            border:1px solid #ccc;
            padding:15px;
            border-radius:10px;
        }

        
    }
 


    @media(max-width:576px){
        .table-responsive{
            max-height:400px;
        }
    }

    


</style>


<div class="no-print" >
<h4 style="text-align:center; padding-top:20px;" >
     <u>
     Report Cards &mdash;
    {{$session->name}} &bull;
    {{$term->name}}&bull;
    {{$class->class_name ?? $students->first()->report_class->name ?? 'unknown class'}}
     </u>
    
</h4><br><br>
</div>


<!-- Print All Result Button i-->
<div class="d-flex justify-content-around mb-3 no-print " >
        <button class="btn btn-sm btn-success clear-all"
         data-class-id="{{$class->id}}" 
         data-term="{{$term}}" 
         data-session="{{$session->name}}">
           Clear All Students
        </button>

        <button class="btn btn-sm btn-dark" onclick="window.print()">
           Print All Result
        </button>
    </div>

<div id="all-report-cards">

   @forelse($students as $student)
   {{--Result fetch per student to check if there are
    students assigned to the selected class--}}

    <div id="report-card-{{ $student->id }}" class="border rounded p-4 shadow-sm  student-report">
      @include('backend.admin_profile.report.report_header', [
      'settings' => $settings,
       'student' => $student,
      'class' => $student->report_class,
      'term' => $term,
      'session' => $session,
      'totals' => $student->score_summary,
       ])

      @php
      $results = \App\Models\Result::where([
        'student_id' =>$student->id,
        'term' =>$term->name,
        'session' =>$session->name,
        ])->get();

       
     @endphp

    
 
     @if($results->isEmpty())
    
     <p style="font-size:18px; text-align:center; color:orange;">No Result for {{$student->name}}</p>
     @else

     {{-- Display Score Table --}}
     @include('backend.admin_profile.report.report_subject_table', [
     'results' => $results,
     ])

      {{-- Display Psychomotor Table --}}
      @include('backend.admin_profile.report.psycho_moto_result_table', [
       'psychomotor' => $student->psychomotor,
     ])

     {{-- Table Footer --}}
     <table class="table mt-5" style="width:90%;" align="center">
        <tr >
        <td style="width:33%">
            <strong>Class Teacher's Comment</strong> : 
              {{ucwords(strtolower($student->psychomotor->teacher_comment ?? '_______'))}}
           <!-- how to add student name at the comment session
             {{ucwords(strtolower($student->name?? 'N/A'))}} -->
       </td>

        <td style="width:33%; text-align:center">
            <strong>Principal's Comment</strong><br>
            {{ucwords(strtolower($student->psychomotor->principal_comment ?? '_______'))}}<br>
           
       </td>

       <td style="width:34%; text-align:center" >
            <strong>Next Term Begins</strong><br>
            {{isset($nextTermBegins) ? \carbon\carbon::parse($nextTermBegins)->format('l, jS F, Y') :'________'}}<br>
        
       </td>
       

       <td style="width:20%; padding:4px; text-align:center;">
            <img src="{{asset('uploads/school_stamp/stamp.jpg')}}"
             alt="School Stamp" style="width:80px; height:auto;">
        </td>
        <tr>

      </table>  


     <div class="d-flex justify-content-around mt-3 no-print" >
        {{-- INDIVIDUAL CLEAR BUTTON PER STUDENT--}}
        <button class="btn btn-sm btn-warning clear-student" 
        data-student-id="{{$student->id}}" 
         data-class-id="{{$class->id}}" 
         data-term="{{$term}}" 
         data-session="{{$session->name}}">
            {{ $student->clearance?->is_cleared? 'Uncleared Student' : 'Clear Student'}}

        </button>

        {{-- INDIVIDUAL PRINT BUTTON PER STUDENT --}}
            <button class="btn btn-sm btn-primary print-single"  data-student-id="{{$student->id}}">
           Print Student Result
        </button>
        
    </div>

    {{-- FORCE PAGE BREAK AFTER EACH STUDENT --}}
    @if(!$loop->last)
    <div class="page-break"></div>
    @endif

    
  @endif
  <hr style="margin:25px 0; height:10px; width:100%;" class="no-print">
  
  </div> <!-- close div for single-report-card --> 
  @empty


 <p style="font-size:18px; text-align:center; color:red;">
    No Results Found For This Class, Term, or Session
  </p>

 
 
  @endforelse


</div><!--close div for print all -->




    <script scr="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      

<script>

    $(document).ready(function(){
       //Clear Single Student
       $(document).on('click', '.clear-student', function(){
        const studentId = $(this).data('student-id');
        const classId = $(this).data('class-id');
        const term = $(this).data('term');
        const session = $(this).data('session');
        
        $.ajax({
            url: `/admin/clearance/toggle/${studentId}`,
            type: 'POST',
            data: {
                class_id: classId,
                term: term,
                session: session,
                _token: '{{csrf_token()}}'
            },
            success: function(response){
                alert(response.message);
                location.reload();//Refresh view to update button
            },
            error: function(){
                alert('Failed to update clearance.');
                console.error(err);
            }
        });
       });

       //print single student result
       
       $('.print-single').on('click', function(){
        const studentId = $(this).data('student-id');

        //hide all report cards
        $('[id^="report-card-"]').hide();
        
        //show only the selected student's report
        $(`#report-card-${studentId}`).show();

        //hide elements not meant for printing
        $('.no-print').hide();

        //Trigger browser print
        window.print();
        
         //After printing restore all content
         setTimeout(()=>{
            $('[id^="report-card-"]').show();
            $('.no-print').show();
        }, 1000)
   
    });


       //Clear All Student
       $(document).on('click', '.clear-all', function(){
        if(!confirm('Are you sure you want to clear all student?'))
        return;
    
        const classId = $(this).data('class-id');
        const term = $(this).data('term');
        const session = $(this).data('session');

        $.ajax({
            url: `/admin/clearance/clear-all`,
            type: 'POST',
            data: {
                class_id: classId,
                term: term,
                session: session,
                _token: '{{csrf_token()}}'
            },
            success: function(response){
                alert(response.message);
                //Refresh all button
                location.reload();
            },
            error: function(){
                alert('Fail to clear all students.');
                
            }
        });
       });


       // print all
       $('#print-all-btn').on('click', function(){
        const allContent = document.getElementById('all-report-cards');
        if(!allContent){
            alert('Report content not found');
            return;
        }
        const win = window.open('', '_blank');
        win.document.write(`<html> <head>
         <title>Print Report</title></head>
         <body>
       ${allContent.innerHTML} 
       </body>
       </html> `);

        win.document.close();
        win.focus();
        win.print();
        win.close();
       });


    });





</script>



@endsection