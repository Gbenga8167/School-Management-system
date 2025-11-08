@extends('backend.teacher_account.teacher_dashboard')
@section('teacher')

<div class="container my-4">
    <h4 class="text-center text-purple-700">CBT Results - {{ strtoupper($currentTerm) }} ({{ $currentSession }}) Accademic Session</h4>

    @if($results->isEmpty())
        <div class="alert alert-info text-center">
            No results available for the current term & session.
        </div>
    @else
        {{-- Group results by subject --}}
        @php
            $groupedResults = $results->groupBy('subject_name');
        @endphp

        @foreach($groupedResults as $subject => $subjectResults)
            <div class="card mb-4 shadow-sm border-0 rounded-lg">
                <div class="card-header" style="background: linear-gradient(90deg, #6f42c1, #5a32a3); color: white;">
                    <h5 class="mb-0" style="color:white">{{ ucwords(strtolower($subject)) }}</h5>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-bordered table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Student Name</th>
                                <th>Class</th>
                                <th>Assessment Type</th>
                                <th>Score</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subjectResults as $result)
                                <tr>
                                    <td><b>{{ucwords(strtolower($result->student_name)) }}</b></td>
                                    <td><b>{{strtoupper($result->class_name) }}</b></td>
                                    <td>{{ ucfirst($result->assessment_type) }}</td>
                                    <td><span class="fw-bold text-purple-700">{{ $result->score }}</span></td>
                                    <td>
    <form id="retake-form-{{ $result->attempt_id }}" 
          action="{{ route('teacher.cbt.retake', $result->attempt_id) }}" 
          method="POST" 
          style="display:inline;">
        @csrf
        <button type="button" 
                class="btn btn-sm btn-warning" 
                onclick="confirmRetake({{ $result->attempt_id }})">
            Retake Test
        </button>
    </form>
</td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    @endif
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmRetake(attemptId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This will delete the student's attempt and answers, allowing a fresh retake.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, allow retake!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Submit the form if confirmed
            document.getElementById('retake-form-' + attemptId).submit();
        }
    });
}
</script>


@endsection
