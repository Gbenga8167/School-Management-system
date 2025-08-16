@extends('backend.student_account.student_dashboard')
@section('student')

<div class="container">
    <h4 class="text-center my-2" style="color:green">{{ $cbtTest->subject->subject_name ?? 'CBT Test' }}({{$cbtTest->assessment_type}}) </h4>
    <h5 class="text-center" style="color:red"> {{$cbtTest->title }}</h5>
    <p class="text-center warning" style="color:green">Answer All Questions</p>

    <div id="timer" class="alert alert-info text-center"></div>

    <form id="cbtForm">
        @csrf
        @foreach($questions as $index => $question)
            @php
                $existingAnswer = $attempt->answers()->where('cbt_question_id', $question->id)->first();
                $selectedOption = $existingAnswer ? strtoupper($existingAnswer->selected_option) : null;
            @endphp

            <div class="card my-3 p-3 question-card" data-index="{{ $index }}" style="display: {{ $index === 0 ? 'block' : 'none' }}">
                <p><strong>Q{{ $index + 1 }}:</strong> {{ ucfirst($question->question_text) }}</p>

                {{-- Option A --}}
                <label>
                    <input type="radio" name="question_{{ $question->id }}" value="A"
                        data-attempt="{{ $attempt->id }}" 
                        data-question="{{ $question->id }}"
                        {{ $selectedOption === 'A' ? 'checked' : '' }}>
                    A. {{ ucfirst($question->option_a) }}
                </label><br>

                {{-- Option B --}}
                <label>
                    <input type="radio" name="question_{{ $question->id }}" value="B"
                        data-attempt="{{ $attempt->id }}" 
                        data-question="{{ $question->id }}"
                        {{ $selectedOption === 'B' ? 'checked' : '' }}>
                    B. {{ ucfirst($question->option_b) }}
                </label><br>

                {{-- Option C --}}
                <label>
                    <input type="radio" name="question_{{ $question->id }}" value="C"
                        data-attempt="{{ $attempt->id }}" 
                        data-question="{{ $question->id }}"
                        {{ $selectedOption === 'C' ? 'checked' : '' }}>
                    C. {{ ucfirst($question->option_c) }}
                </label><br>

                {{-- Option D --}}
                <label>
                    <input type="radio" name="question_{{ $question->id }}" value="D"
                        data-attempt="{{ $attempt->id }}" 
                        data-question="{{ $question->id }}"
                        {{ $selectedOption === 'D' ? 'checked' : '' }}>
                    D. {{ ucfirst($question->option_d) }}
                </label>
            </div>
        @endforeach

        <div class="text-center my-4">
            <button type="button" id="prevBtn" class="btn btn-secondary" style="display:none">Previous</button>
            <button type="button" id="nextBtn" class="btn btn-primary">Next</button>
            <button type="button" id="submitBtn" class="btn btn-success" style="display:none">Submit Test</button>
        </div>
    </form>
</div>

<script>
    // ==========================
    // Countdown Timer (Fixed)
    // ==========================
    const startedAt = new Date("{{ $attempt->started_at->toIso8601String() }}").getTime();
    const durationMs = {{ $cbtTest->duration_minutes }} * 60 * 1000;
    const endTime = startedAt + durationMs;

    const timerEl = document.getElementById('timer');

    const timerInterval = setInterval(() => {
        const now = new Date().getTime();
        const distance = endTime - now;

        if (distance <= 0) {
            clearInterval(timerInterval);
            timerEl.innerHTML = 'Time is up! Auto-submitting...';
            submitTest(true);
        } else {
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            timerEl.innerHTML = `Time Remaining: ${minutes}m ${seconds}s`;
        }
    }, 1000);

    // ==========================
    // Save Answer via AJAX
    // ==========================
    document.querySelectorAll('input[type="radio"]').forEach(option => {
        option.addEventListener('change', function(){
            const attemptId = this.dataset.attempt;
            const questionId = this.dataset.question;
            const selected = this.value;

            fetch("{{ url('student/cbt/save-answer') }}/" + attemptId + "/" + questionId, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ selected_option: selected })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) console.log('Answer saved for question ' + questionId);
            })
            .catch(err => console.error(err));
        });
    });

    // ==========================
    // Question Navigation
    // ==========================
    const questionCards = document.querySelectorAll('.question-card');
    let currentIndex = 0;

    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');

    function showQuestion(index) {
        questionCards.forEach((card, i) => {
            card.style.display = (i === index) ? 'block' : 'none';
        });
        prevBtn.style.display = (index === 0) ? 'none' : 'inline-block';
        nextBtn.style.display = (index === questionCards.length - 1) ? 'none' : 'inline-block';
        submitBtn.style.display = (index === questionCards.length - 1) ? 'inline-block' : 'none';
    }

    nextBtn.addEventListener('click', () => {
        if (currentIndex < questionCards.length - 1) {
            currentIndex++;
            showQuestion(currentIndex);
        }
    });

    prevBtn.addEventListener('click', () => {
        if (currentIndex > 0) {
            currentIndex--;
            showQuestion(currentIndex);
        }
    });

    showQuestion(currentIndex);

    // ==========================
    // Submit Test
    // ==========================
    submitBtn.addEventListener('click', () => submitTest(false));

    function submitTest(auto = false) {
        if (!auto) {
            const confirmSubmit = confirm("Are you sure you want to submit your test? You cannot change your answers after submitting.");
            if (!confirmSubmit) return;
        }

        const attemptId = "{{ $attempt->id }}";

        fetch("{{ url('student/cbt/submit') }}/" + attemptId, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            },
        })
        .then(() => {
            window.location.href = "{{ route('student.index') }}";
        })
        .catch(err => console.error(err));
    }
</script>

@endsection



@extends('backend.student_account.student_dashboard')
@section('student')

<div class="container">
    <h3 class="text-center my-4">{{ $cbtTest->subject->subject_name ?? 'CBT Test' }}</h3>

    <div id="timer" class="alert alert-info text-center"></div>

    <form id="cbtForm">
        @csrf
        @foreach($questions as $index => $question)
            @php
                $existingAnswer = $attempt->answers()->where('cbt_question_id', $question->id)->first();
                $selectedOption = $existingAnswer ? strtoupper($existingAnswer->selected_option) : null;
            @endphp

            <div class="card my-3 p-3 question-card" data-index="{{ $index }}" style="display: {{ $index === 0 ? 'block' : 'none' }}">
                <p><strong>Q{{ $index + 1 }}:</strong> {{ $question->question_text }}</p>

                {{-- Option A --}}
                <label>
                    <input type="radio" name="question_{{ $question->id }}" value="A"
                        data-attempt="{{ $attempt->id }}" 
                        data-question="{{ $question->id }}"
                        {{ $selectedOption === 'A' ? 'checked' : '' }}>
                    A. {{ $question->option_a }}
                </label><br>

                {{-- Option B --}}
                <label>
                    <input type="radio" name="question_{{ $question->id }}" value="B"
                        data-attempt="{{ $attempt->id }}" 
                        data-question="{{ $question->id }}"
                        {{ $selectedOption === 'B' ? 'checked' : '' }}>
                    B. {{ $question->option_b }}
                </label><br>

                {{-- Option C --}}
                <label>
                    <input type="radio" name="question_{{ $question->id }}" value="C"
                        data-attempt="{{ $attempt->id }}" 
                        data-question="{{ $question->id }}"
                        {{ $selectedOption === 'C' ? 'checked' : '' }}>
                    C. {{ $question->option_c }}
                </label><br>

                {{-- Option D --}}
                <label>
                    <input type="radio" name="question_{{ $question->id }}" value="D"
                        data-attempt="{{ $attempt->id }}" 
                        data-question="{{ $question->id }}"
                        {{ $selectedOption === 'D' ? 'checked' : '' }}>
                    D. {{ $question->option_d }}
                </label>
            </div>
        @endforeach

        <div class="text-center my-4">
            <button type="button" id="prevBtn" class="btn btn-secondary" style="display:none">Previous</button>
            <button type="button" id="nextBtn" class="btn btn-primary">Next</button>
            <button type="button" id="submitBtn" class="btn btn-success" style="display:none">Submit Test</button>
        </div>
    </form>
</div>

<script>
    // Countdown Timer
    const endTime = new Date("{{ $cbtTest->end_time ?? now()->addMinutes($duration)->toIso8601String() }}").getTime();
    const timerEl = document.getElementById('timer');

    const timerInterval = setInterval(() => {
        const now = new Date().getTime();
        const distance = endTime - now;

        if (distance <= 0) {
            clearInterval(timerInterval);
            timerEl.innerHTML = 'Time is up! Auto-submitting...';
            submitTest(true); // auto submit without confirmation
        } else {
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            timerEl.innerHTML = `Time Remaining: ${minutes}m ${seconds}s`;
        }
    }, 1000);

    // Save answer via AJAX when radio changes
    document.querySelectorAll('input[type="radio"]').forEach(option => {
        option.addEventListener('change', function(){
            const attemptId = this.dataset.attempt;
            const questionId = this.dataset.question;
            const selected = this.value;

            fetch("{{ url('student/cbt/save-answer') }}/" + attemptId + "/" + questionId, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ selected_option: selected })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    console.log('Answer saved for question ' + questionId);
                }
            })
            .catch(err => console.error(err));
        });
    });

    // Question navigation
    const questionCards = document.querySelectorAll('.question-card');
    let currentIndex = 0;

    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');

    function showQuestion(index) {
        questionCards.forEach((card, i) => {
            card.style.display = (i === index) ? 'block' : 'none';
        });
        prevBtn.style.display = (index === 0) ? 'none' : 'inline-block';
        nextBtn.style.display = (index === questionCards.length - 1) ? 'none' : 'inline-block';
        submitBtn.style.display = (index === questionCards.length - 1) ? 'inline-block' : 'none';
    }

    nextBtn.addEventListener('click', () => {
        if (currentIndex < questionCards.length - 1) {
            currentIndex++;
            showQuestion(currentIndex);
        }
    });

    prevBtn.addEventListener('click', () => {
        if (currentIndex > 0) {
            currentIndex--;
            showQuestion(currentIndex);
        }
    });

    showQuestion(currentIndex);

    // Submit Test with confirmation
    submitBtn.addEventListener('click', () => submitTest(false));

    function submitTest(auto = false) {
        if (!auto) {
            const confirmSubmit = confirm("Are you sure you want to submit your test? You cannot change your answers after submitting.");
            if (!confirmSubmit) return;
        }

        const attemptId = "{{ $attempt->id }}";

        fetch("{{ url('student/cbt/submit') }}/" + attemptId, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            },
        })
        .then(() => {
            window.location.href = "{{ route('student.index') }}";
        })
        .catch(err => console.error(err));
    }
</script>

@endsection









namespace App\Http\Controllers\backend\StudentAccount;

use session;
use Carbon\Carbon;
use App\Models\terms;
use App\Models\CBTTest;
use App\Models\student;
use App\Models\CBTAnswer;
use App\Models\CBTAttempt;
use App\Models\CBTQuestion;
use Illuminate\Http\Request;
use App\Models\academic_session;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\AssignClassSubjectStudent;

class StudentCBTController extends Controller
{
    public function Index(){

       
        $student = student::where('user_id', Auth::id())->firstOrFail(); 
        //get current term $ session from the admin setting
        $currentTerm = terms ::where('is_current', true)->first()?->name;
        $currentSession = academic_session::where('is_current', true)->first()?->name;

        if(!$student){
            return back()->with('error', 'Student record not found.');
        }


        //1. get student  class and subjects from assigned_class_subject_students
        // for the current term and session
        $assignments = AssignClassSubjectStudent::where('student_id', $student->id)
        ->where('term', $currentTerm)
        ->where('session', $currentSession)->get();

        $classIds = $assignments->pluck('class_id')->toArray();
        $subjectIds = $assignments->pluck('subject_id')->toArray();

        //2. fetch eligible CBT tests
        
        $cbtTests = CBTTest::whereIn('class_id',  $classIds)
        ->whereIn('subject_id', $subjectIds)
        ->where('term', $currentTerm)
        ->where('session', $currentSession)->get();

        return view('backend.student_account.cbt_question.index', compact('cbtTests'));
    }//end method


    public function StartTest($id){
      
       // $studentId = student::where('user_id', Auth::id())->firstOrFail(); 
        $cbtTest = CBTTest::findOrFail($id);
        
        //get current time in lagos timezone
        $now = Carbon::now('Africa/Lagos');
        
        $startTime = Carbon::parse($cbtTest->start_time, 'Africa/Lagos');

        //Decide if the has started
        $testStatus = $now->lt($startTime) ? 'not_started' : 'started';
        return view('backend.student_account.cbt_question.student_cbt_test',[
            'cbtTest' => $cbtTest,
            'testStatus' => $testStatus,
            'startTime' => $startTime->toIso8601String(), //for js countdown

        ]);
    }//end method

    
    //Begin Test
    public function BeginTest($id){
    $student = Student::where('user_id', Auth::id())->firstOrFail();
    $cbtTest = CBTTest::findOrFail($id);

    // Check if attempt already exists
    $attempt = CBTAttempt::firstOrCreate(
        [
            'cbt_test_id' => $cbtTest->id,
            'student_id' => $student->id,
        ],
        [
            'started_at' => now('Africa/Lagos'),
            'status' => 'in_progress',
        ]
    );

    // Ensure started_at is set
    if (!$attempt->started_at) {
        $attempt->started_at = now('Africa/Lagos');
        $attempt->save();
    }

    // Get questions in random order
    $questions = $cbtTest->questions()->inRandomOrder()->get();

    // Calculate fixed end time (either CBT fixed end_time or started_at + duration)
    $fixedEndTime = $cbtTest->end_time
        ? Carbon::parse($cbtTest->end_time)
        : Carbon::parse($attempt->started_at)->addMinutes($cbtTest->duration_minutes);

    // Pass endTime as timestamp (milliseconds) for JS accuracy
    return view('backend.student_account.cbt_question.questions', [
        'cbtTest'   => $cbtTest,
        'attempt'   => $attempt,
        'questions' => $questions,
        'duration'  => $cbtTest->duration_minutes,
        'endTime'   => $fixedEndTime->timestamp * 1000, // ✅ use JS timestamp
    ]);
}



// Save Test
public function saveAnswer(Request $request, $attemptId, $questionId)
{
    $student = Student::where('user_id', Auth::id())->firstOrFail();

    $attempt = CBTAttempt::where('id', $attemptId)
        ->where('student_id', $student->id)
        ->where('status', 'in_progress')
        ->firstOrFail();

    $question = CBTQuestion::findOrFail($questionId);

    $isCorrect = strtolower($request->selected_option) === strtolower($question->correct_option);

    CBTAnswer::updateOrCreate(
        [
            'cbt_attempt_id'  => $attempt->id,
            'cbt_question_id' => $question->id
        ],
        [
            'selected_option' => strtolower($request->selected_option),
            'is_correct'      => $isCorrect
        ]
    );

    return response()->json(['success' => true]);
}
//end method


//Method to Submit the Test When the student finishes (or time runs out):
public function submitTest($attemptId)
{
    $student = Student::where('user_id', Auth::id())->firstOrFail();

    $attempt = CBTAttempt::where('id', $attemptId)
        ->where('student_id', $student->id)
        ->where('status', 'in_progress')
        ->firstOrFail();

    // Calculate score
    $score = $attempt->answers()->where('is_correct', true)->count();

    $attempt->update([
        'score'         => $score,
        'submitted_at'  => now('Africa/Lagos'),
        'duration_used' => now('Africa/Lagos')->diffInMinutes($attempt->started_at),
        'status'        => 'completed',
    ]);

    // Redirect to results page or student dashboard
    return redirect()->route('student.index')
        ->with('success', 'Test submitted successfully! Your score: ' . $score);
}

//end method
}







index.blade

@extends('backend.student_account.student_dashboard')
@section('student')
<div class="container-fluid" style="background-color:white; width:100%">

<!-- start page title -->
<div class="row" >
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">AVAILABLE CBT TEST</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Available</a></li>
                    <li class="breadcrumb-item active"> CBT Tests</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
<div i class="container-fluid">
@if($cbtTests->isEmpty())
    <p class="bg-success p-4 " style="color:red; font-size:18px">No CBT tests available at the moment</p>
@else
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Class</th>
                    <th>Subject</th>
                    <th>Start Time</th>
                    <th>Duration</th>
                    <th>End Time</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cbtTests as $test)

                <tr>
                    <td>{{ $test->class->class_name }}</td>
                    <td>{{ $test->subject->subject_name }}</td>
                    <td>{{$test->start_time }}</td>
                    <td>{{ $test->duration_minutes }}</td>
                    <td>{{ $test->end_time ?? 'No End Time ' }}</td>
                    <td>
                             
                          <a href="{{ route('student.cbt.test', $test->id) }}" class="btn btn-primary">Start Test</a>
                           
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
</div>

@endsection









@extends('backend.student_account.student_dashboard')
@section('student')

<div class="container">
    <h4 class="text-center my-2" style="color:green">{{ $cbtTest->subject->subject_name ?? 'CBT Test' }}({{$cbtTest->assessment_type}}) </h4>
    <h5 class="text-center" style="color:red"> {{$cbtTest->title }}</h5>
    <p class="text-center warning" style="color:green">Answer All Questions</p>

    <div id="timer" class="alert alert-info text-center"></div>

    <form id="cbtForm">
        @csrf
        @foreach($questions as $index => $question)
            @php
                $existingAnswer = $attempt->answers()->where('cbt_question_id', $question->id)->first();
                $selectedOption = $existingAnswer ? strtoupper($existingAnswer->selected_option) : null;
            @endphp

            <div class="card my-3 p-3 question-card" data-index="{{ $index }}" style="display: {{ $index === 0 ? 'block' : 'none' }}">
                <p><strong>Q{{ $index + 1 }}:</strong> {{ ucfirst($question->question_text) }}</p>

                @foreach(['A','B','C','D'] as $opt)
                <label>
                    <input type="radio" name="question_{{ $question->id }}" value="{{ $opt }}"
                        data-attempt="{{ $attempt->id }}" 
                        data-question="{{ $question->id }}"
                        {{ $selectedOption === $opt ? 'checked' : '' }}>
                    {{ $opt }}. {{ ucfirst($question->{'option_'.strtolower($opt)}) }}
                </label><br>
                @endforeach
            </div>
        @endforeach

        <div class="text-center my-4">
            <button type="button" id="prevBtn" class="btn btn-secondary" style="display:none">Previous</button>
            <button type="button" id="nextBtn" class="btn btn-primary">Next</button>
            <button type="button" id="submitBtn" class="btn btn-success" style="display:none">Submit Test</button>
        </div>
    </form>
</div>

<script>
    const endTime = new Date("{{ $endTime }}").getTime();
    const timerEl = document.getElementById('timer');

    function updateTimer() {
        const now = new Date().getTime();
        const distance = endTime - now;

        if(distance <= 0){
            clearInterval(timerInterval);
            timerEl.innerHTML = 'Time is up! Auto-submitting...';
            submitTest(true);
            return;
        }

        const minutes = Math.floor((distance % (1000*60*60)) / (1000*60));
        const seconds = Math.floor((distance % (1000*60)) / 1000);
        timerEl.innerHTML = `Time Remaining: ${minutes}m ${seconds}s`;
    }

    const timerInterval = setInterval(updateTimer, 1000);
    updateTimer();

    // Save answer AJAX
    document.querySelectorAll('input[type="radio"]').forEach(option => {
        option.addEventListener('change', function(){
            fetch("{{ url('student/cbt/save-answer') }}/" + this.dataset.attempt + "/" + this.dataset.question, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ selected_option: this.value })
            }).then(res => res.json()).then(data => {
                if(data.success) console.log('Answer saved for question ' + this.dataset.question);
            });
        });
    });

    // Question navigation
    const questionCards = document.querySelectorAll('.question-card');
    let currentIndex = 0;
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');

    function showQuestion(index) {
        questionCards.forEach((c,i)=> c.style.display = (i===index)?'block':'none');
        prevBtn.style.display = (index===0)?'none':'inline-block';
        nextBtn.style.display = (index===questionCards.length-1)?'none':'inline-block';
        submitBtn.style.display = (index===questionCards.length-1)?'inline-block':'none';
    }

    nextBtn.addEventListener('click', ()=>{ if(currentIndex<questionCards.length-1){currentIndex++;showQuestion(currentIndex);} });
    prevBtn.addEventListener('click', ()=>{ if(currentIndex>0){currentIndex--;showQuestion(currentIndex);} });
    showQuestion(currentIndex);

    function submitTest(auto=false){
        if(!auto){
            if(!confirm("Are you sure you want to submit your test?")) return;
        }

        fetch("{{ url('student/cbt/submit/' . $attempt->id) }}",{
            method:'POST',
            headers:{
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept':'application/json'
            }
        })
        .then(res=>res.json())
        .then(data=>{
            if(data.success){
                alert('Test submitted! Your score: ' + data.score);
                window.location.href = "{{ route('student.index') }}";
            }
        });
    }

    submitBtn.addEventListener('click', ()=> submitTest(false));
</script>

@endsection









@extends('backend.student_account.student_dashboard')
@section('student')

<div class="container">
    <h4 class="text-center my-2" style="color:green">{{ $cbtTest->subject->subject_name ?? 'CBT Test' }}({{$cbtTest->assessment_type}}) </h4>
    <h5 class="text-center" style="color:red"> {{$cbtTest->title }}</h5>
    <p class="text-center warning" style="color:green">Answer All Questions</p>

    <div id="timer" class="alert alert-info text-center"></div>

    <form id="cbtForm">
        @csrf
        @foreach($questions as $index => $question)
            @php
                $existingAnswer = $attempt->answers()->where('cbt_question_id', $question->id)->first();
                $selectedOption = $existingAnswer ? strtoupper($existingAnswer->selected_option) : null;
            @endphp

            <div class="card my-3 p-3 question-card" data-index="{{ $index }}" style="display: {{ $index === 0 ? 'block' : 'none' }}">
                <p><strong>Q{{ $index + 1 }}:</strong> {{ ucfirst($question->question_text) }}</p>

                @foreach(['A','B','C','D'] as $opt)
                <label>
                    <input type="radio" name="question_{{ $question->id }}" value="{{ $opt }}"
                        data-attempt="{{ $attempt->id }}" 
                        data-question="{{ $question->id }}"
                        {{ $selectedOption === $opt ? 'checked' : '' }}>
                    {{ $opt }}. {{ ucfirst($question->{'option_'.strtolower($opt)}) }}
                </label><br>
                @endforeach
            </div>
        @endforeach

        <div class="text-center my-4">
            <button type="button" id="prevBtn" class="btn btn-secondary" style="display:none">Previous</button>
            <button type="button" id="nextBtn" class="btn btn-primary">Next</button>
            <button type="button" id="submitBtn" class="btn btn-success" style="display:none">Submit Test</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const testDurationMinutes = {{ $cbtTest->duration }}; // from DB
    const form = document.getElementById('cbtForm');
    const timerElement = document.getElementById('timer');
    const testKey = "cbt_" + {{ $cbtTest->id }}; // unique for this test
    const startTimeKey = testKey + "_startTime";
    const answersKey = testKey + "_answers";

    // 1️⃣ Start time logic
    let startTime = localStorage.getItem(startTimeKey);
    if (!startTime) {
        startTime = new Date().getTime();
        localStorage.setItem(startTimeKey, startTime);
    }

    // 2️⃣ Countdown function
    function updateTimer() {
        const now = new Date().getTime();
        const elapsed = Math.floor((now - startTime) / 1000); // seconds passed
        const totalDuration = testDurationMinutes * 60; // seconds
        const remaining = totalDuration - elapsed;

        if (remaining <= 0) {
            timerElement.innerHTML = "Time's up!";
            localStorage.removeItem(startTimeKey);
            form.submit();
            return;
        }

        const minutes = Math.floor(remaining / 60);
        const seconds = remaining % 60;
        timerElement.innerHTML = `Time left: ${minutes}m ${seconds}s`;
    }

    setInterval(updateTimer, 1000);
    updateTimer();

    // 3️⃣ Save answers in localStorage on change
    form.addEventListener('change', function (e) {
        if (e.target.type === 'radio') {
            let savedAnswers = JSON.parse(localStorage.getItem(answersKey) || "{}");
            savedAnswers[e.target.name] = e.target.value;
            localStorage.setItem(answersKey, JSON.stringify(savedAnswers));
        }
    });

    // 4️⃣ Load saved answers on page load
    let savedAnswers = JSON.parse(localStorage.getItem(answersKey) || "{}");
    Object.keys(savedAnswers).forEach(name => {
        let input = document.querySelector(`input[name="${name}"][value="${savedAnswers[name]}"]`);
        if (input) input.checked = true;
    });
});
</script>


@endsection
