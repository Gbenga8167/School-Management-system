@extends('backend.admin_profile.admin.admin_dashboard')
@section('admin')

<div class="container py-4">
    <h4 class="p-2 bg-info text-white rounded" style="text-align: center;">Edit Promotion</h4>


    <!-- ✅ Form Card -->
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('promotion.update', $promotion->id) }}">
                @csrf

                <!-- Student (readonly) -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Student</label>
                    <input type="text" class="form-control" value="{{ $promotion->student->name ?? 'N/A' }}" readonly>
                </div>

                <!-- Class -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Class</label>
                    <select name="class_id" class="form-select" required>
                        <option value="">-- Select Class --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ $promotion->class_id == $class->id ? 'selected' : '' }}>
                                {{ $class->class_name ?? $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Subject -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Subject</label>
                    <select name="subject_id" class="form-select" required>
                        <option value="">-- Select Subject --</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ $promotion->subject_id == $subject->id ? 'selected' : '' }}>
                                {{ $subject->subject_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Term -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Term</label>
                    <select name="term" class="form-select" required>
                        <option value="">-- Select Term --</option>
                        @foreach($terms as $term)
                            <option value="{{ $term }}" {{ $promotion->term == $term ? 'selected' : '' }}>
                                {{ $term }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Session -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Session</label>
                    <select name="session" class="form-select" required>
                        <option value="">-- Select Session --</option>
                        @foreach($sessions as $session)
                            <option value="{{ $session }}" {{ $promotion->session == $session ? 'selected' : '' }}>
                                {{ $session }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Buttons -->
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">Update Promotion</button>
                    <a href="{{ route('promotion.manage') }}" class="btn btn-secondary flex-fill">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
