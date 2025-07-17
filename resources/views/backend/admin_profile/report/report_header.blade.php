<div class="table-responsive" style="max-height:400px; overflow:auto">

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
           <h6 style="font-weight:bold;"> PUPIL'S PERFORMANCE REPORTS FOR {{$session->name}} ACCADEMIC SESSION <br> PUPIL'S ID : {{$student->roll_id}} </h6>
    </td>
    </tr>

    <tr style="font-size:13px;font-weight:bold;">
        <td style="text-align: center;">
           <h6 style="font-weight:bold; font-size:13px;"> Academic Session : {{$session->name}} </h6>
        </td>

        <td style="text-align:center;">
        <h6 style="font-weight:bold;">TERM : {{strtoupper($term->name)}}</h6>
        </td>

        <td style="text-align:center;">
        <h6 style="font-weight:bold;"> CLASS : {{ strtoupper($class)}} </h6>
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

</div>