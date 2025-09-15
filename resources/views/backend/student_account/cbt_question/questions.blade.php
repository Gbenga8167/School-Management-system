@extends('backend.student_account.student_dashboard')
@section('student')

<div class="container">
    <h4 class="text-center my-2" style="color:green"> 
        Subject : {{ $cbtTest->subject->subject_name ?? 'CBT Test' }} - {{ $cbtTest->assessment_type }}
    </h4>
    <h5 class="text-center" style="color:red">Title : {{ ucwords($cbtTest->title) }}</h5>
    <p class="text-center warning" style="color:green">Answer All Questions.</p>

    <div id="timer" class="alert alert-info text-center">Loading timer…</div>

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
                        <input type="radio"
                               name="question_{{ $question->id }}"
                               value="{{ $opt }}"
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
(function () {
    // ==== TIMER (server-locked, timezone safe) ====
    const endTime   = Number(@json($endTime));   // UTC timestamp from server
    const serverNow = Number(@json($serverNow)); // UTC now from server
    const clientNow = Date.now();

    // adjust for client/server clock drift
    const offset = serverNow - clientNow;

    const timerEl = document.getElementById('timer');
    function formatTwo(n){ return n < 10 ? '0'+n : n; }

    function tick() {
        const now = Date.now() + offset; // always aligned to server UTC
        const diff = endTime - now;

        if (diff <= 0) {
            timerEl.textContent = 'Time is up! Submitting...';
            submitTest(true);
            return;
        }


        const totalSeconds = Math.floor(diff / 1000);
        const hours = Math.floor(totalSeconds / 3600);
        const minutes = Math.floor((totalSeconds % 3600) / 60);
        const seconds = totalSeconds % 60;
        timerEl.textContent = hours > 0
            ? `Time Remaining: ${hours}h ${formatTwo(minutes)}m ${formatTwo(seconds)}s`
            : `Time Remaining: ${minutes}m ${formatTwo(seconds)}s`;
    }
    const timerInterval = setInterval(tick, 1000);
    tick();

    // ==== SAVE ANSWER (AJAX) ====
    document.querySelectorAll('input[type="radio"]').forEach(option => {
        option.addEventListener('change', function(){
            const attemptId = this.dataset.attempt;
            const questionId = this.dataset.question;
            const selected = this.value;

            fetch("{{ url('student/cbt/save-answer') }}/" + attemptId + "/" + questionId, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ selected_option: selected })
            })
            .catch(err => {
                console.error('Save failed', err);
                alert('Failed to save answer. Please check your connection.');
            });
        });
    });

    // ==== NAVIGATION ====
    const questionCards = document.querySelectorAll('.question-card');
    let currentIndex = 0;

    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');

    function showQuestion(index) {
        questionCards.forEach((card, i) => card.style.display = (i === index) ? 'block' : 'none');
        prevBtn.style.display   = (index === 0) ? 'none' : 'inline-block';
        nextBtn.style.display   = (index === questionCards.length - 1) ? 'none' : 'inline-block';
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

    // ==== SUBMIT (auto or manual) via AJAX ====
    function submitTest(auto = false) {
        clearInterval(timerInterval);

        if (!auto) {
            const ok = confirm("Are you sure you want to submit your test? You cannot change your answers after submitting.");
            if (!ok) {
                setInterval(tick, 1000);
                return;
            }
        }

        fetch("{{ route('student.cbt.submit', $attempt->id) }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json'
            }
        })
        .then(res => res.ok ? res.json() : Promise.reject(res))
        .then(data => {
            if (data && data.success) {
                alert('Test submitted! Your score: ' + data.score);
                window.location.href = "{{ route('student.index') }}";
            } else {
                alert('Could not submit. Please try again.');
            }
        })
        .catch(err => {
            console.error('Submit failed', err);
            window.location.href = "{{ route('student.index') }}";
        });
    }

    document.getElementById('submitBtn').addEventListener('click', () => submitTest(false));
})();
</script>


@endsection
