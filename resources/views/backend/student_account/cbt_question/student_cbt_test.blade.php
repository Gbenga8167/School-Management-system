@extends('backend.student_account.student_dashboard')
@section('student')

<div class="container">

<h4 style="text-align:center; margin-top:45px; color:green" >{{$cbtTest->subject->subject_name ?? 'CBT Test'}} - CBT Test</h4>

@if($testStatus === 'not_started')
<div class="alert alert-warning" style="text-align:center; padding-bottom:200px">
   <p style="margin-top:100px"> Test has not started yet. <br><br>
    It will start in :
    <span id="countdown" class="alert alert-danger"></span></p>
</div>

@else
<center>
    <br>
    <a href="{{route('student.begin.test', $cbtTest->id)}}" class="btn btn-success">Begin Test</a>

</center>

@endif
 
</div>

@if($testStatus === "not_started")
<script>
    const startTime = new Date("{{$startTime}}").getTime();
    const countdownEl = document.getElementById('countdown');
    const timer = setInterval(function(){
        const now = new Date().getTime();
        const distance = startTime - now;
        if(distance <= 0){
            clearInterval(timer);
            //reload page when countdown finishes
            location.reload();
        }else{
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            let countdownText = "";
            if(days>0) countdownText += days + "d -- ";
            countdownText += hours + "h -- " +  minutes + "m -- " + seconds + "s";
            countdownEl.innerHTML = countdownText.trim();

            //change to green if less than 1 hour
            if(distance<=3600000){
                countdownEl.style.color='green';
                countdownEl.style.fontWeight = 'bold';
            }

            if(distance<=600000){
                countdownEl.style.color='red';
                countdownEl.style.fontWeight = 'bold';
                countdownEl.style.animation = 'blink 1s step-start infinite';
            }
        }   
    }, 1000);

    //Blinking animation style 
    const style = document.createElement('style');
    style.innerHTML = `@keyframes blink {
        50% { opacity: 0;}
    }`;
    document.head.appendChild(style);
</script>
@endif
@endsection