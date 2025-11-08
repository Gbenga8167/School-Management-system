@extends('backend.admin_profile.admin.admin_dashboard')
@section('admin')

<div class="container-fluid">

    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Promote Students</h4>
            </div>
        </div>
    </div>

    <!-- Promotion Card -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h4 class="card-title">Student Promotion Form</h4>

                    <form action="{{ route('promotion.store') }}" method="POST">
                        @csrf

                        <!-- Old Class -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">From Class</label>
                            <div class="col-sm-10">
                                <select name="old_class_id" required class="form-select dynamic-class">
                                    <option value="">-- Select Old Class --</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Students -->
                        <div class="row mb-3 showStudents">
                            <label class="col-sm-2 col-form-label">Students</label>
                            <div class="col-sm-10 students-box">
                                <!-- Students checkboxes will load here -->
                            </div>
                        </div>

                        <hr>

                        <!-- New Class -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">To Class</label>
                            <div class="col-sm-10">
                                <select name="new_class_id" required class="form-select dynamic-subject">
                                    <option value="">-- Select New Class --</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Subjects -->
                        <div class="row mb-3 showSubjects">
                            <label class="col-sm-2 col-form-label">Subjects</label>
                            <div class="col-sm-10 subjects-box">
                                <!-- Subjects checkboxes will load here -->
                            </div>
                        </div>

                        <!-- Term -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">New Term</label>
                            <div class="col-sm-10">
                                <select name="term" class="form-select" required>
                                    <option value="">-- Select Term --</option>
                                    @foreach($terms as $term)
                                        <option value="{{ $term->name }}">{{ $term->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Session -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">New Session</label>
                            <div class="col-sm-10">
                                <select name="session" class="form-select" required>
                                    <option value="">-- Select Session --</option>
                                    @foreach($sessions as $session)
                                        <option value="{{ $session->name }}">{{ $session->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Promote Students</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

</div>

<!-- jQuery Script -->
<script>
    // Fetch Students
    $('.dynamic-class').on('change', function(){
        let class_id = $(this).val();
        let _token = "{{ csrf_token() }}";

        $.ajax({
            url: "{{ route('promotion.fetch.students') }}",
            method: "GET",
            data: {class_id: class_id, _token: _token},
            success: function(result){
                let selectAll = `
                    <div>
                        <input type="checkbox" id="select_all_students" class="form-check-input">
                        <label for="select_all_students"><strong>Select All Students</strong></label>
                    </div><hr>
                `;
                $('.students-box').html(selectAll + result.students);
                $('.showStudents').show();

                // Handle select all
                $('#select_all_students').on('change', function(){
                    $('input[name="student_ids[]"]').prop('checked', this.checked);
                });
            }
        });
    });

    // Fetch Subjects
    $('.dynamic-subject').on('change', function(){
        let class_id = $(this).val();
        let _token = "{{ csrf_token() }}";

        $.ajax({
            url: "{{ route('promotion.fetch.subjects') }}",
            method: "GET",
            data: {class_id: class_id, _token: _token},
            success: function(result){
                let selectAll = `
                    <div>
                        <input type="checkbox" id="select_all_subjects" class="form-check-input">
                        <label for="select_all_subjects"><strong>Select All Subjects</strong></label>
                    </div><hr>
                `;
                $('.subjects-box').html(selectAll + result.subjects);
                $('.showSubjects').show();

                // Handle select all
                $('#select_all_subjects').on('change', function(){
                    $('input[name="subject_ids[]"]').prop('checked', this.checked);
                });
            }
        });
    });
</script>


@endsection

